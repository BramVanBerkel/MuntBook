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
        foreach ($vins as $vinData) {
            $vin = Vin::updateOrCreate([
                'transaction_id' => $transaction->id
            ], [
                'prevout_type' => $vinData->get('prevout_type'),
                'coinbase' => $vinData->get('coinbase'),
                'tx_height' => $vinData->get('tx_height', null),
                'tx_index' => $vinData->get('tx_index', null),
                'scriptSig_asm' => $vinData->get('scriptSig_asm', null),
                'scriptSig_hex' => $vinData->get('scriptSig_hex', null),
                'rbf' => $vinData->get('rbf'),
                'transaction_id' => $transaction->id
            ]);

            if($vinData->get('txid') !== null) {
                $referencingTransaction = Transaction::firstWhere('txid', '=', $vinData->get('txid'));

                if($referencingTransaction !== null) {
                    $vout = Vout::where('transaction_id', '=', $referencingTransaction->id)->where('n', '=', $vinData->get('vout'))->first();
                    $vin->vout()->associate($vout);
                    $vin->save();
                }
            }
        }
    }
}
