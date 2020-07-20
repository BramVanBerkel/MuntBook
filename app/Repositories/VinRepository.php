<?php


namespace App\Repositories;


use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;

class VinRepository
{
    public static function create(Collection $data, string $transaction_id): Vin
    {
        $vin = Vin::create([
            'prevout_type' => $data->get('prevout_type'),
            'txid' => $data->get('txid'),
            'coinbase' => $data->get('coinbase'),
            'tx_height' => $data->get('tx_height') !== "" ? $data->get('tx_height') : null,
            'tx_index' => $data->get('tx_index') !== "" ? $data->get('tx_index') : null,
            'scriptSig_asm' => $data->get('scriptSig_asm') !== "" ? $data->get('scriptSig_asm') : null,
            'scriptSig_hex' => $data->get('scriptSig_hex') !== "" ? $data->get('scriptSig_hex') : null,
            'rbf' => $data->get('rbf'),
            'transaction_id' => $transaction_id
        ]);

        if($data->get('txid') !== null) {
            $vout = Vout::where('transaction_id', '=', $data->get('txid'))->where('n', '=', $data->get('vout'))->first();
            $vin->vout()->associate($vout);
            $vin->save();
        }

        return $vin;
    }
}
