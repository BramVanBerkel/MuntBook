<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;

class VinRepository
{
    public static function syncVins(Collection $vins, Transaction $transaction): void
    {
        foreach ($vins as $vin) {
            $vin = Vin::updateOrCreate([
                'transaction_id' => $transaction->id
            ], [
                'prevout_type' => $data->get('prevout_type'),
                'coinbase' => $data->get('coinbase'),
                'tx_height' => $data->get('tx_height') !== "" ? $data->get('tx_height') : null,
                'tx_index' => $data->get('tx_index') !== "" ? $data->get('tx_index') : null,
                'scriptSig_asm' => $data->get('scriptSig_asm') !== "" ? $data->get('scriptSig_asm') : null,
                'scriptSig_hex' => $data->get('scriptSig_hex') !== "" ? $data->get('scriptSig_hex') : null,
                'rbf' => $data->get('rbf'),
                'transaction_id' => $transaction->id
            ]);

            if($data->get('txid') !== null) {
                $transaction = Transaction::firstWhere('txid', '=', $data->get('txid'));

                if($transaction !== null) {
                    $vout = Vout::where('transaction_id', '=', $transaction->id)->where('n', '=', $data->get('vout'))->first();
                    $vin->vout()->associate($vout);
                    $vin->save();
                }
            }
        }
    }
}
