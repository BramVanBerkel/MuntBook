<?php


namespace App\Repositories;


use App\Models\Address;
use App\Models\Transaction;
use App\Models\Vout;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class VoutRepository
{
    function __construct(private AddressRepository $addressRepository) {}

    public function syncVouts(Collection $vouts, Transaction $transaction): void
    {
        foreach ($vouts as $voutData) {
            $address = $this->getAddress($voutData);

            $voutModel = Vout::updateOrCreate([
                'transaction_id' => $transaction->id,
                'n' => $voutData->get('n'),
            ], [
                'address_id' => $address?->id,
                'type' => $this->getType($voutData, $transaction, $address),
                'value' => $voutData->get('value'),
                'standard_key_hash_hex' => optional($voutData->get('standard-key-hash'))->get('hex'),
                'standard_key_hash_address' => optional($voutData->get('standard-key-hash'))->get('address'),
                'scriptpubkey_type' => optional($voutData->get('scriptPubKey'))->get('type'),
                'witness_hex' => optional($voutData->get('PoW²-witness'))->get('hex'),
                'witness_lock_from_block' => optional($voutData->get('PoW²-witness'))->get('lock_from_block'),
                'witness_lock_until_block' => optional($voutData->get('PoW²-witness'))->get('lock_until_block'),
                'witness_fail_count' => optional($voutData->get('PoW²-witness'))->get('fail_count'),
                'witness_action_nonce' => optional($voutData->get('PoW²-witness'))->get('action_nonce'),
                'witness_pubkey_spend' => optional($voutData->get('PoW²-witness'))->get('pubkey_spend'),
                'witness_pubkey_witness' => optional($voutData->get('PoW²-witness'))->get('pubkey_witness'),
                'transaction_id' => $transaction->id,
            ]);

            if ($this->isWitnessVout($voutData) && $voutModel->type !== Vout::TYPE_WITNESS_FUNDING) {
                $this->checkWitnessVout($vouts, $voutData, $transaction);
            }
        }
    }

    /**
     * @param Collection $voutData
     * @return Address|AddressRepository|null
     */
    private function getAddress(Collection $voutData)
    {
        if (Arr::has($voutData, 'scriptPubKey.addresses')) {
            return $this->addressRepository->create(Arr::get($voutData, 'scriptPubKey.addresses')[0]);
        }

        if ($voutData->has('standard-key-hash')) {
            return $this->addressRepository->create(Arr::get($voutData, 'standard-key-hash.address'));
        }

        if ($voutData->has('PoW²-witness')) {
            return $this->addressRepository->create(Arr::get($voutData, 'PoW²-witness.address'));
        }

        return null;
    }

    /**
     * @param Collection $vouts
     * @param Collection $voutData
     * @param Transaction $transaction
     */
    private function checkWitnessVout(Collection $vouts, Collection $voutData, Transaction $transaction)
    {
        $compound = $this->isCompounding($vouts);
        $witnessAddress = $this->addressRepository->create(Arr::get($voutData, 'PoW²-witness.address'));

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

    /**
     * @param Collection $voutData
     * @return bool
     */
    private function isWitnessVout(Collection $voutData): bool
    {
        return $voutData->has('PoW²-witness') ||
            optional($voutData->get('scriptPubKey'))->get('type') === 'pow2_witness';
    }

    /**
     * @param Collection $vouts
     * @return bool|float
     *
     * Returns true if witness is fully compounding
     * Returns float of value that witness is partially compounding
     * Returns false if witness is not compounding
     */
    private function isCompounding(Collection $vouts)
    {
        if ($vouts->count() === 1) {
            return true;
        }

        $reward = $vouts->filter(function ($vout) {
            return !$vout->has('PoW²-witness');
        })->pluck('value')->sum();

        if (floor($reward) === 30.0) {
            return false;
        }

        return $reward;
    }

    private function getType(Collection $data, Transaction $transaction, ?Address $address): string
    {
        if ($data->has('PoW²-witness') || optional($data->get('scriptPubKey'))->get('type') === 'pow2_witness') {
            //TODO refactor "nonstandard" to enum
            if($transaction->vins()->first()->vout?->scriptpubkey_type === 'nonstandard' ||
                $data->get('PoW²-witness')->get('lock_from_block') === 0) {
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
                    'type' => Address::TYPE_MINING,
                ]);
            }

            return Vout::TYPE_MINING;
        }

        return Vout::TYPE_TRANSACTION;
    }
}
