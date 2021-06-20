<?php


namespace App\Services;


use App\Enums\AddressTypeEnum;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\Vout;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class VoutService
{
    public function __construct(
        private AddressService $addressService,
        private GuldenService $guldenService
    ) {}

    public function saveVouts(Collection $vouts, Transaction $transaction): void
    {
        $vouts->each(function(Collection $vout) use($transaction, $vouts) {
            $address = $this->getAddress($vout);

            $voutModel = Vout::updateOrCreate([
                'transaction_id' => $transaction->id,
                'n' => $vout->get('n'),
            ], [
                'address_id' => $address?->id,
                'type' => $this->getType($vout, $transaction, $address),
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
                'value' => Transaction::WITNESS_REWARD,
                'n' => 1,
                'type' => Vout::TYPE_WITNESS_COMPOUND
            ]);
        }

        if (is_numeric($compound)) {
            //partially compounding
            /** @var Vout $compoundingVout */
            $compoundingVout = $transaction->vouts()->create([
                'value' => Transaction::WITNESS_REWARD - $compound,
                'n' => 2,
                'type' => Vout::TYPE_WITNESS_COMPOUND
            ]);
        }

        if($compoundingVout !== null) {
            $compoundingVout->address()->associate($witnessAddress)->save();
        }
    }

    private function isWitnessVout(Collection $vout): bool
    {
        return $vout->has('PoW²-witness') ||
            optional($vout->get('scriptPubKey'))->get('type') === 'pow2_witness';
    }

    /**
     * @param Collection $vouts
     * @param int $blockHeight
     * @return bool|float
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

        $reward = $vouts->filter(function ($vout) {
            return !$vout->has('PoW²-witness');
        })->pluck('value')
            ->sum();

        $witnessReward = $this->guldenService->getWitnessReward($blockHeight);

        if (floor($reward) === $witnessReward) {
            return false;
        }

        return $reward;
    }

    private function getType(Collection $vout, Transaction $transaction, ?Address $address): string
    {
        if ($vout->has('PoW²-witness') || optional($vout->get('scriptPubKey'))->get('type') === 'pow2_witness') {
            //TODO refactor "nonstandard" to enum
            if($transaction->vins()->first()->vout?->scriptpubkey_type === 'nonstandard' ||
                $vout->get('PoW²-witness')->get('lock_from_block') === 0) {
                //TODO: ugly
                $transaction->update(['type' => Transaction::TYPE_WITNESS_FUNDING]);
                return Vout::TYPE_WITNESS_FUNDING;
            }

            return Vout::TYPE_WITNESS;
        }

        if($transaction->type === Transaction::TYPE_MINING) {
            if($address?->address === Address::DEVELOPMENT_ADDRESS) {
                return Vout::TYPE_DEVELOPMENT_REWARD;
            }

            if($address) {
                $address->update([
                    'type' => AddressTypeEnum::MINING(),
                ]);
            }

            return Vout::TYPE_MINING;
        }

        return Vout::TYPE_TRANSACTION;
    }
}
