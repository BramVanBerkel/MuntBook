<?php


namespace App\Repositories;


use App\Models\Vin;
use Illuminate\Support\Collection;

class VinRepository
{
    public static function create(Collection $vin, string $transaction_id)
    {
        return Vin::create([
            'prevout_type' => $vin->get('prevout_type'),
            'txid' => $vin->get('txid'),
            'coinbase' => $vin->get('coinbase'),
            'tx_height' => $vin->get('tx_height') !== "" ? $vin->get('tx_height') : null,
            'tx_index' => $vin->get('tx_index') !== "" ? $vin->get('tx_index') : null,
            'scriptSig_asm' => $vin->get('scriptSig_asm') !== "" ? $vin->get('scriptSig_asm') : null,
            'scriptSig_hex' => $vin->get('scriptSig_hex') !== "" ? $vin->get('scriptSig_hex') : null,
            'vout' => $vin->get('vout'),
            'rbf' => $vin->get('rbf'),
            'transaction_id' => $transaction_id
        ]);
    }
}
