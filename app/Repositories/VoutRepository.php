<?php


namespace App\Repositories;


use App\Models\Address;
use App\Models\Vout;
use Illuminate\Support\Collection;

class VoutRepository
{
    public static function create(Collection $data, string $transaction_id): Vout
    {
        $vout = Vout::create([
            'value' => $data->get('value'),
            'n' => $data->get('n'),
            'standard_key_hash_hex' => optional($data->get('standard-key-hash'))->hex,
            'standard_key_hash_address' => optional($data->get('standard-key-hash'))->address,
            'witness_hex' => optional($data->get('PoW²-witness'))->hex,
            'witness_lock_from_block' => optional($data->get('PoW²-witness'))->lock_from_block,
            'witness_lock_until_block' => optional($data->get('PoW²-witness'))->lock_until_block,
            'witness_fail_count' => optional($data->get('PoW²-witness'))->fail_count,
            'witness_action_nonce' => optional($data->get('PoW²-witness'))->action_nonce,
            'witness_pubkey_spend' => optional($data->get('PoW²-witness'))->pubkey_spend,
            'witness_pubkey_witness' => optional($data->get('PoW²-witness'))->pubkey_witness,
            'witness_address' => optional($data->get('PoW²-witness'))->address,
            'transaction_id' => $transaction_id
        ]);


        if($data->get('scriptPubKey') !== null) {
            if(!property_exists($data->get('scriptPubKey'), 'addresses')){
                return $vout;
            }

            foreach($data->get('scriptPubKey')->addresses as $address) {
                $address = AddressRepository::create($address);

                $vout->addresses()->attach($address);
            }
        }

        return $vout;
    }
}
