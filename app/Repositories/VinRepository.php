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
                'tx_height' => $vinData->get('tx_height') !== "" ? $vinData->get('tx_height') : null,
                'tx_index' => $vinData->get('tx_index') !== "" ? $vinData->get('tx_index') : null,
                'scriptSig_asm' => $vinData->get('scriptSig_asm') !== "" ? $vinData->get('scriptSig_asm') : null,
                'scriptSig_hex' => $vinData->get('scriptSig_hex') !== "" ? $vinData->get('scriptSig_hex') : null,
                'rbf' => $vinData->get('rbf'),
                'transaction_id' => $transaction->id
            ]);

            if ($vinData->get('prevout_type') === 'index') {
                $referencingVout = Vout::where('transaction_id', function ($query) use ($vinData) {
                    return $query->select('id')
                        ->from((new Transaction)->getTable())
                        ->where('block_height', '=', $vinData->get('tx_height'))
                        ->skip($vinData->get('tx_index'))
                        ->take(1);
                    ->take(1)
                    ->first();

                if ($referencingVout !== null) {
                    $vin->vout()->associate($referencingVout);
                    $vin->save();
                }
            }
        }
    }
}
