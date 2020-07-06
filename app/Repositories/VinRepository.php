<?php


namespace App\Repositories;


use App\Models\Vin;
use Illuminate\Support\Collection;

class VinRepository
{
    public static function create(Collection $data, string $transaction_id): Vin
    {
        return Vin::create([
            'prevout_type' => $data->get('prevout_type'),
            'txid' => $data->get('txid'),
            'coinbase' => $data->get('coinbase'),
            'tx_height' => $data->get('tx_height') !== "" ? $data->get('tx_height') : null,
            'tx_index' => $data->get('tx_index') !== "" ? $data->get('tx_index') : null,
            'scriptSig_asm' => $data->get('scriptSig_asm') !== "" ? $data->get('scriptSig_asm') : null,
            'scriptSig_hex' => $data->get('scriptSig_hex') !== "" ? $data->get('scriptSig_hex') : null,
            'vout' => $data->get('vout'),
            'rbf' => $data->get('rbf'),
            'transaction_id' => $transaction_id
        ]);
    }
}
