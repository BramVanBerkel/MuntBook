<?php


namespace App\Repositories;


use App\Models\Vout;
use Illuminate\Support\Collection;

class VoutRepository
{
    public static function create(Collection $vout, string $transaction_id)
    {
        return Vout::create([
            'value' => $vout->get('value'),
            'n' => $vout->get('n'),
            'standard_key_hash_hex' => optional($vout->get('standard-key-hash'))->hex,
            'standard_key_hash_address' => optional($vout->get('standard-key-hash'))->address,
            'witness_hex' => optional($vout->get('PoW²-witness'))->hex,
            'witness_lock_from_block' => optional($vout->get('PoW²-witness'))->lock_from_block,
            'witness_lock_until_block' => optional($vout->get('PoW²-witness'))->lock_until_block,
            'witness_fail_count' => optional($vout->get('PoW²-witness'))->fail_count,
            'witness_action_nonce' => optional($vout->get('PoW²-witness'))->action_nonce,
            'witness_pubkey_spend' => optional($vout->get('PoW²-witness'))->pubkey_spend,
            'witness_pubkey_witness' => optional($vout->get('PoW²-witness'))->pubkey_witness,
            'witness_address' => optional($vout->get('PoW²-witness'))->address,
            'transaction_id' => $transaction_id
        ]);
    }
}
