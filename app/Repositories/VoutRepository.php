<?php


namespace App\Repositories;


use App\Models\Address;
use App\Models\Transaction;
use App\Models\Vout;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class VoutRepository
{
    public static function syncVouts(Collection $vouts, Transaction $transaction): void
    {
        foreach ($vouts as $voutData) {
            $voutModel = Vout::updateOrCreate([
                'transaction_id' => $transaction->id,
                'n' => $voutData->get('n'),
            ], [
                'type' => self::getType($voutData),
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

            self::syncAddresses($voutData, $voutModel, $transaction);

            if (self::isWitnessVout($voutData) && $voutModel->type !== Vout::TYPE_WITNESS_FUNDING) {
                $compound = self::isCompounding($vouts);
                $witnessAddress = AddressRepository::create(Arr::get($voutData, 'PoW²-witness.address'));

                if ($compound === true) {
                    //fully compounding
                    $transaction->vouts()->create([
                        'value' => 30,
                        'n' => 1,
                        'type' => Vout::TYPE_WITNESS_COMPOUND
                    ])->addresses()->attach($witnessAddress);
                }

                if (is_numeric($compound)) {
                    $transaction->vouts()->create([
                        'value' => 30 - $compound, //28
                        'n' => 2,
                        'type' => Vout::TYPE_WITNESS_COMPOUND
                    ])->addresses()->attach($witnessAddress);
                }
            }

//            if ($vout->has('PoW²-witness') || optional($vout->get('scriptPubKey'))->get('type') === 'pow2_witness') {
//                $witnessAddress = AddressRepository::create(Arr::get($vout, 'PoW²-witness.address'));
//                $lockFromBlock = Arr::get($vout, 'PoW²-witness.lock_from_block');
//
//                $compoundEarnings = null;
//
//                if ($voutModel->wasRecentlyCreated) {
//
//                    if ($vouts->count() === 1) {
//                        //witness is fully compounding earnings, manually create a vout to keep track of transactions
//                        Vout::create([
//                            'transaction_id' => $transaction->id,
//                            'value' => 30,
//                            'n' => 1,
//                            'type' => Vout::TYPE_WITNESS_COMPOUND,
//                        ])->addresses()->attach($witnessAddress);
//                    } else {
//
//                        $compoundVout = $vouts->filter(function ($vout) {
//                            return !$vout->contains('PoW²-witness');
//                        })->first();
//
//                        if ($compoundVout->get('value') !== 30) {
//                            $address = AddressRepository::create($compoundVout->get('scriptPubKey')->get('addresses')->first());
//                            Vout::create([
//                                'transaction_id' => $transaction->id,
//                                'value' => $compoundVout->get('value'),
//                                'n' => 1,
//                                'type' => Vout::TYPE_WITNESS_COMPOUND,
//                            ])->addresses()->attach($address);
//
//                            Vout::create([
//                                'transaction_id' => $transaction->id,
//                                'value' => 30 - $compoundVout->get('value'),
//                                'n' => 2,
//                                'type' => Vout::TYPE_WITNESS_COMPOUND,
//                            ])->addresses()->attach($witnessAddress);
//
//                        }
//                    }
//
//                }
//
//
//                //todo: abstract this query away
//                $fundingTransaction = Transaction::whereHas('vouts', function ($query) use ($lockFromBlock, $witnessAddress) {
//                    $query->whereHas('addresses', function ($query) use ($witnessAddress) {
//                        $query->where('address', '=', $witnessAddress);
//                    })->whereHas('transaction', function ($query) use ($lockFromBlock) {
//                        $query->where('block_height', '=', $lockFromBlock);
//                    });
//                })->first();
//
//                if ($fundingTransaction !== null) {
//                    $fundingTransaction->update(['type' => Transaction::TYPE_WITNESS_FUNDING]);
//                }
//
//                $voutModel->update([
//
//                ]);
//
//                $voutModel->save();
//            }
        }
    }

    /**
     * @param Collection $voutData
     * @param Vout $voutModel
     * @param Transaction $transaction
     * @return Address
     */
    private static function syncAddresses(Collection $voutData, Vout $voutModel, Transaction $transaction)
    {
        if (Arr::has($voutData, 'scriptPubKey.addresses')) {
            foreach (Arr::get($voutData, 'scriptPubKey.addresses') as $address) {
                $address = AddressRepository::create($address);
                $voutModel->addresses()->attach($address);
            }
            return $voutModel->addresses->first();
        }

        if ($voutData->has('standard-key-hash')) {
            $address = AddressRepository::create(Arr::get($voutData, 'standard-key-hash.address'));
            $voutModel->addresses()->attach($address);
            return $address;
        }

        if($voutData->has('PoW²-witness')) {
            $address = AddressRepository::create(Arr::get($voutData, 'PoW²-witness.address'));
            $voutModel->addresses()->attach($address);
            return $address;
        }
    }

    /**
     * @param Collection $voutData
     * @return bool
     */
    private static function isWitnessVout(Collection $voutData): bool
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
    private static function isCompounding(Collection $vouts)
    {
        if($vouts->count() === 1) {
            return true;
        }

        $reward = $vouts->filter(function($vout) {
            return !$vout->has('PoW²-witness');
        })->pluck('value')->sum();

        if(floor($reward) === 30.0) {
            return false;
        }

        return $reward;
    }

    private static function getType(Collection $data): string
    {
        if ($data->has('PoW²-witness') || optional($data->get('scriptPubKey'))->get('type') === 'pow2_witness') {
            if($data->get('PoW²-witness')->get('lock_from_block') === 0) {
                return Vout::TYPE_WITNESS_FUNDING;
            }

            return Vout::TYPE_WITNESS;
        }

        //TODO: implement checking for mining type?

        return Vout::TYPE_TRANSACTION;
    }
}
