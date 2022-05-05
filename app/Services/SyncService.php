<?php

namespace App\Services;

use App\Enums\AddressTypeEnum;
use App\Jobs\SyncBlock;
use App\Models\Address;
use App\Models\Block;
use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SyncService
{
    public function __construct(
        private readonly GuldenService $guldenService,
        private readonly AddressService $addressService,
        private readonly BlockService $blockService,
    ) {
    }

    public function syncBlock(int $height)
    {
        $blockData = $this->guldenService->getBlock($this->guldenService->getBlockHash($height), 1);

        DB::beginTransaction();

        $block = $this->saveBlock($blockData);

        // Delete the block's transactions to prevent unconfirmed or double transactions in the DB
        $block->transactions()->delete();

        foreach ($blockData->get('tx') as $txid) {
            $transactionData = $this->guldenService->getTransaction($txid, true);

            $transaction = $this->saveTransaction($transactionData, $block);

            $this->saveVins($transactionData->get('vin'), $transaction);

            $this->saveVouts($transactionData->get('vout'), $transaction);
        }

        DB::commit();

        if ($block->isWitness() && $block->transactions()->count() < 2) {
            dispatch(new SyncBlock($height))->delay(now()->addSeconds(config('gulden.sync_delay')));
        }
    }

    /**
     * Saves a block to the database.
     */
    private function saveBlock(Collection $data): Block
    {
        // Convert empty values to null
        $witnessTime = ($data->get('witness_time') === 0) ? null : new Carbon($data->get('witness_time'));
        $witnessMerkleroot = ($data->get('witness_merkleroot') === Block::EMPTY_WITNESS_MERLKEROOT) ? null : $data->get('witness_merkleroot');
        $witnessVersion = ($data->get('witness_version') === 0) ? null : $data->get('witness_version');

        // Hashrate is not included in the block data, so we have to fetch it separately
        $hashRate = $this->guldenService->getNetworkHashrate(height: $data->get('height'));

        // Convert chainwork to gigahashes using GMP because the chainwork decimal number is too long for php
        $chainwork = gmp_hexdec($data->get('chainwork'));
        $chainwork = gmp_div($chainwork, gmp_init(1_000_000_000));
        $chainwork = gmp_intval($chainwork);

        return Block::updateOrCreate([
            'height' => $data->get('height'),
        ], [
            'hash' => $data->get('hash'),
            'confirmations' => $data->get('confirmations'),
            'strippedsize' => $data->get('strippedsize'),
            'validated' => $data->get('validated'),
            'size' => $data->get('size'),
            'weight' => $data->get('weight'),
            'version' => $data->get('version'),
            'merkleroot' => $data->get('merkleroot'),
            'witness_version' => $witnessVersion,
            'witness_time' => $witnessTime,
            'pow_time' => new Carbon($data->get('pow_time')),
            'witness_merkleroot' => $witnessMerkleroot,
            'time' => new Carbon($data->get('time')),
            'nonce' => $data->get('nonce'),
            'pre_nonce' => $data->get('pre_nonce'),
            'post_nonce' => $data->get('post_nonce'),
            'bits' => $data->get('bits'),
            'difficulty' => $data->get('difficulty'),
            'hashrate' => $hashRate,
            'chainwork' => $chainwork,
            'previousblockhash' => $data->get('previousblockhash'),
            'created_at' => new Carbon($data->get('mediantime')),
        ]);
    }

    private function saveTransaction(Collection $transaction, Block $block): Transaction
    {
        return Transaction::updateOrCreate([
            'txid' => $transaction->get('txid'),
        ], [
            'block_height' => $block->height,
            'size' => $transaction->get('size'),
            'vsize' => $transaction->get('vsize'),
            'version' => $transaction->get('version'),
            'locktime' => $transaction->get('locktime'),
            'blockhash' => $transaction->get('blockhash'),
            'confirmations' => $transaction->get('confirmations'),
            'blocktime' => new Carbon($transaction->get('blocktime')),
            'type' => $this->getTransactionType($transaction),
            'created_at' => new Carbon($transaction->get('time')),
        ]);
    }

    private function getTransactionType(Collection $transaction): string
    {
        // Transactions with empty inputs generate new coins
        if ($transaction->get('vin')->first()->get('coinbase') === '') {
            return Transaction::TYPE_MINING;
        }

        if ($transaction->get('vout')->first()->has('PoW²-witness')) {
            if ($transaction->get('vin')->first()->has('pow2_coinbase')) {
                return Transaction::TYPE_WITNESS;
            }

            return Transaction::TYPE_WITNESS_FUNDING;
        }

        return Transaction::TYPE_TRANSACTION;
    }

    private function saveVins(Collection $vins, Transaction $transaction): void
    {
        $vins->each(function (Collection $vin) use ($transaction) {
            //convert empty strings to null, to prevent inserting empty values in the DB
            $vin = $vin->map(fn ($item) => $item === '' ? null : $item);

            $referencingVout = $this->getReferencingVout($vin);

            $vin = Vin::updateOrCreate([
                'transaction_id' => $transaction->id,
                'tx_height' => $vin->get('tx_height'),
                'tx_index' => $vin->get('tx_index'),
                'vout_id' => $referencingVout?->id,
            ], [
                'transaction_id' => $transaction->id,
                'prevout_type' => $vin->get('prevout_type'),
                'coinbase' => $vin->get('coinbase'),
                'tx_height' => $vin->get('tx_height'),
                'tx_index' => $vin->get('tx_index'),
                'scriptSig_asm' => $vin->get('scriptSig_asm'),
                'scriptSig_hex' => $vin->get('scriptSig_hex'),
                'rbf' => $vin->get('rbf'),
            ]);

            if ($referencingVout instanceof Vout) {
                $vin->vout()->associate($referencingVout);
                $vin->save();
            }
        });
    }

    private function getReferencingVout(Collection $vin): ?Vout
    {
        if ($vin->get('prevout_type') === Vin::PREVOUT_TYPE_INDEX) {
            return $this->getIndexVout($vin);
        }

        if ($vin->get('prevout_type') === Vin::PREVOUT_TYPE_HASH) {
            return $this->getHashVout($vin);
        }

        return null;
    }

    private function getIndexVout(Collection $vin): ?Vout
    {
        return Vout::where('transaction_id', fn($query) => $query->select('id')
            ->from((new Transaction())->getTable())
            ->where('block_height', '=', $vin->get('tx_height'))
            ->orderBy('id')
            ->skip($vin->get('tx_index'))
            ->take(1))->firstWhere('n', '=', $vin->get('vout'));
    }

    private function getHashVout(Collection $vin): ?Vout
    {
        if ($vin->get('txid') === Transaction::EMPTY_TXID) {
            return null;
        }

        return Transaction::firstWhere('txid', '=', $vin->get('txid'))
            ?->vouts
            ->get($vin->get('vout'));
    }

    private function saveVouts(Collection $vouts, Transaction $transaction): void
    {
        $vouts->each(function (Collection $vout) use ($transaction, $vouts) {
            $address = $this->getAddress($vout);

            $voutModel = Vout::updateOrCreate([
                'transaction_id' => $transaction->id,
                'n' => $vout->get('n'),
            ], [
                'address_id' => $address?->id,
                'type' => $this->getVoutType($vout, $transaction, $address),
                'value' => $vout->get('value'),
                'standard_key_hash_hex' => optional($vout->get('standard-key-hash'))->get('hex'),
                'standard_key_hash_address' => optional($vout->get('standard-key-hash'))->get('address'),
                'scriptpubkey_type' => optional($vout->get('scriptPubKey'))->get('type'),
                'witness_hex' => optional($vout->get('PoW²-witness'))->get('hex'),
                'witness_lock_from_block' => optional($vout->get('PoW²-witness'))->get('lock_from_block'),
                'witness_lock_until_block' => optional($vout->get('PoW²-witness'))->get('lock_until_block'),
                'witness_fail_count' => optional($vout->get('PoW²-witness'))->get('fail_count'),
                'witness_action_nonce' => optional($vout->get('PoW²-witness'))->get('action_nonce'),
                'witness_pubkey_spend' => optional($vout->get('PoW²-witness'))->get('pubkey_spend'),
                'witness_pubkey_witness' => optional($vout->get('PoW²-witness'))->get('pubkey_witness'),
                'transaction_id' => $transaction->id,
            ]);

            if ($this->isWitnessVout($vout) && $voutModel->type !== Vout::TYPE_WITNESS_FUNDING) {
                $this->checkWitnessVout($vouts, $vout, $transaction);
            }
        });
    }

    private function getAddress(Collection $vout): ?Address
    {
        if (Arr::has($vout, 'scriptPubKey.addresses')) {
            return $this->addressService->getAddress(Arr::get($vout, 'scriptPubKey.addresses')[0]);
        }

        if ($vout->has('standard-key-hash')) {
            return $this->addressService->getAddress(Arr::get($vout, 'standard-key-hash.address'));
        }

        if ($vout->has('PoW²-witness')) {
            return $this->addressService->getAddress(Arr::get($vout, 'PoW²-witness.address'));
        }

        return null;
    }

    private function checkWitnessVout(Collection $vouts, Collection $voutData, Transaction $transaction)
    {
        $compound = $this->isCompounding($vouts, $transaction->block_height);

        $witnessAddress = $this->addressService->getAddress(Arr::get($voutData, 'PoW²-witness.address'));

        $compoundingVout = null;

        if ($compound === true) {
            //fully compounding
            /** @var Vout $compoundingVout */
            $compoundingVout = $transaction->vouts()->create([
                'value' => $this->blockService->getBlockSubsidy($transaction->block_height)->witness,
                'n' => 1,
                'type' => Vout::TYPE_WITNESS_REWARD,
            ]);
        }

        if (is_numeric($compound)) {
            //partially compounding
            /** @var Vout $compoundingVout */
            $compoundingVout = $transaction->vouts()->create([
                'value' => $this->blockService->getBlockSubsidy($transaction->block_height)->witness - $compound,
                'n' => 2,
                'type' => Vout::TYPE_WITNESS_REWARD,
            ]);
        }

        $compoundingVout?->address()->associate($witnessAddress)->save();
    }

    private function isWitnessVout(Collection $vout): bool
    {
        return $vout->has('PoW²-witness') ||
            optional($vout->get('scriptPubKey'))->get('type') === 'pow2_witness';
    }

    /**
     * @param  int  $blockHeight
     *
     * Returns true if witness is fully compounding
     * Returns float of value that witness is partially compounding
     * Returns false if witness is not compounding
     */
    private function isCompounding(Collection $vouts, int $blockHeight): bool|float
    {
        if ($vouts->count() === 1) {
            return true;
        }

        $reward = (float) $vouts->filter(fn($vout) => ! $vout->has('PoW²-witness'))->pluck('value')
            ->sum();

        $witnessReward = $this->blockService->getBlockSubsidy($blockHeight)->witness;

        if (floor($reward) === $witnessReward) {
            return false;
        }

        return $reward;
    }

    private function getVoutType(Collection $vout, Transaction $transaction, ?Address $address): string
    {
        if (($vout->has('PoW²-witness') || optional($vout->get('scriptPubKey'))->get('type') === 'pow2_witness') && ($transaction->vins()->first()->vout?->scriptpubkey_type === Vout::NONSTANDARD_SCRIPTPUBKEY_TYPE ||
            $vout->get('PoW²-witness')->get('lock_from_block') === 0)) {
            //TODO: ugly
            $transaction->update(['type' => Transaction::TYPE_WITNESS_FUNDING]);
            return Vout::TYPE_WITNESS_FUNDING;
        }

        if ($transaction->type === Transaction::TYPE_WITNESS) {
            if ($vout->has('PoW²-witness')) {
                return Vout::TYPE_WITNESS;
            }

            return Vout::TYPE_WITNESS_REWARD;
        }

        if ($transaction->type === Transaction::TYPE_MINING) {
            if ($address->isDevelopmentAddress()) {
                return Vout::TYPE_DEVELOPMENT_REWARD;
            }

            $address?->update([
                'type' => AddressTypeEnum::MINING,
            ]);

            return Vout::TYPE_MINING;
        }

        return Vout::TYPE_TRANSACTION;
    }
}
