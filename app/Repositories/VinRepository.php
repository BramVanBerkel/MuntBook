<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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

            if($vinData->get('prevout_type') === 'index') {
                //todo: combine these 2 queries into one
                $referencingTransaction = Transaction::where('block_height', '=', $vinData->get('tx_height'))
                    ->skip($vinData->get('tx_index'))
                    ->take(1)
                    ->first();

                if($referencingTransaction !== null) {
                    $referencingVout = $referencingTransaction->vouts()
                        ->skip($vinData->get('vout'))
                        ->take(1)
                        ->first();

                    if($referencingVout !== null) {
                        $vin->vout()->associate($referencingVout);
                        $vin->save();
                    } else {
                        Log::notice(sprintf("couldn't find vout at transaction %d and index %d", $transaction->id, $vinData->get('vout')));
                    }
                } else {
                    Log::notice(sprintf("couldn't find transaction at height %d and index %d", $vinData->get('tx_height'), $vinData->get('tx_index')));
                }
            }
        }
    }
}
