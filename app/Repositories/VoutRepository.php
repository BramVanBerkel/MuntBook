<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Models\Vout;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class VoutRepository
{
    public static function syncVouts(Collection $vouts, Transaction $transaction) {
        //check if vout is a witness vout
        $isWitness = $vouts->contains(function($vout) {
            return $vout->has('PoW²-witness') || optional($vout->get('scriptPubKey'))->get('type') === 'pow2_witness';
        });

        //check if vout is compounding, if vout is compounding we need to manually insert a vout
        $isCompounding = $isWitness && $vouts->count() === 1;

        foreach ($vouts as $vout) {
            $voutModel = Vout::updateOrCreate([
                'transaction_id' => $transaction->id,
                'n' => $vout->get('n'),
            ], [
                'type' => self::getType($vout),
                'value' => $vout->get('value'),
                'standard_key_hash_hex' => optional($vout->get('standard-key-hash'))->get('hex'),
                'standard_key_hash_address' => optional($vout->get('standard-key-hash'))->get('address'),
                'scriptpubkey_type' => optional($vout->get('scriptPubKey'))->get('type'),
                'transaction_id' => $transaction->id,
            ]);

            if(Arr::has($vout, 'scriptPubKey.addresses')) {
                foreach(Arr::get($vout, 'scriptPubKey.addresses') as $address) {
                    $address = AddressRepository::create($address);
                    $voutModel->addresses()->syncWithoutDetaching($address);
                }
            } elseif($vout->has('standard-key-hash')) {
                $address = AddressRepository::create(Arr::get($vout, 'standard-key-hash.address'));
                $voutModel->addresses()->syncWithoutDetaching($address);
            }

            if($vout->has('PoW²-witness')) {
                $witnessAddress = AddressRepository::create(Arr::get($vout, 'PoW²-witness.address'));
                $lockFromBlock = Arr::get($vout, 'PoW²-witness.lock_from_block');

                //todo: abstract this query away
                $fundingTransaction = Transaction::whereHas('vouts', function($query) use($lockFromBlock, $witnessAddress) {
                    $query->whereHas('addresses', function($query) use($witnessAddress) {
                        $query->where('address', '=', $witnessAddress);
                    })->whereHas('transaction', function($query) use($lockFromBlock) {
                        $query->where('block_height', '=', $lockFromBlock);
                    });
                })->first();
                if($fundingTransaction !== null) {
                    $fundingTransaction->update(['type' => Transaction::TYPE_WITNESS_FUNDING]);
                }

                $voutModel->update([
                    'witness_hex' => optional($vout->get('PoW²-witness'))->get('hex'),
                    'witness_lock_from_block' => optional($vout->get('PoW²-witness'))->get('lock_from_block'),
                    'witness_lock_until_block' => optional($vout->get('PoW²-witness'))->get('lock_until_block'),
                    'witness_fail_count' => optional($vout->get('PoW²-witness'))->get('fail_count'),
                    'witness_action_nonce' => optional($vout->get('PoW²-witness'))->get('action_nonce'),
                    'witness_pubkey_spend' => optional($vout->get('PoW²-witness'))->get('pubkey_spend'),
                    'witness_pubkey_witness' => optional($vout->get('PoW²-witness'))->get('pubkey_witness'),
                ]);

                $voutModel->save();
            }
        }

    }

    public static function create(Collection $data, string $transaction_id): Vout
    {
        $vout = Vout::updateOrCreate([
            'transaction_id' => $transaction_id,
            'n' => $data->get('n'),
        ], [
            'type' => self::getType($data),
            'value' => $data->get('value'),
            'standard_key_hash_hex' => optional($data->get('standard-key-hash'))->hex,
            'standard_key_hash_address' => optional($data->get('standard-key-hash'))->address,
            'scriptpubkey_type' => optional($data->get('scriptPubKey'))->type,
            'transaction_id' => $transaction_id,
        ]);

        if($data->has('scriptPubKey')) {
            if(!property_exists($data->get('scriptPubKey'), 'addresses')){
                return $vout;
            }

            foreach($data->get('scriptPubKey')->addresses as $address) {
                $address = AddressRepository::create($address);
                $vout->addresses()->syncWithoutDetaching($address);
            }
        } elseif ($data->has('standard-key-hash')) {
            $address = AddressRepository::create($data->get('standard-key-hash')->address);
            $vout->addresses()->syncWithoutDetaching($address);
        }

        if($data->has('PoW²-witness')) {
            $witness_address = AddressRepository::create($data->get('PoW²-witness')->address);
            $vout->addresses()->syncWithoutDetaching($witness_address);
            $lockFromBlock = $data->get('PoW²-witness')->lock_from_block;

            Vout::whereHas('addresses', function($query) use($witness_address) {
                $query->where('address', '=', $witness_address->address);
            })->whereHas('transaction', function($query) use($lockFromBlock) {
                $query->where('block_height', '=', $lockFromBlock);
            })->update([
                'type' => Vout::TYPE_WITNESS_FUNDING
            ]);

            $vout->update([
                'witness_hex' => optional($data->get('PoW²-witness'))->hex,
                'witness_lock_from_block' => optional($data->get('PoW²-witness'))->lock_from_block,
                'witness_lock_until_block' => optional($data->get('PoW²-witness'))->lock_until_block,
                'witness_fail_count' => optional($data->get('PoW²-witness'))->fail_count,
                'witness_action_nonce' => optional($data->get('PoW²-witness'))->action_nonce,
                'witness_pubkey_spend' => optional($data->get('PoW²-witness'))->pubkey_spend,
                'witness_pubkey_witness' => optional($data->get('PoW²-witness'))->pubkey_witness,
            ]);

            $vout->save();
        }

        return $vout;
    }

    private static function getType(Collection $data): string
    {
        if($data->has('PoW²-witness') || optional($data->get('scriptPubKey'))->get('type') === 'pow2_witness') {
            return Vout::TYPE_WITNESS;
        }

        //TODO: implement checking for mining type?

        return Vout::TYPE_TRANSACTION;
    }
}
