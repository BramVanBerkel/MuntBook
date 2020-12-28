<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;

class VinRepository
{
    /**
     * @param Collection $vins
     * @param Transaction $transaction
     */
    public static function syncVins(Collection $vins, Transaction $transaction): void
    {
        foreach ($vins as $vinData) {
            //convert empty strings to null, to prevent inserting empty values in the DB
            $vinData = $vinData->map(fn($item) => $item === "" ? null : $item);

            $referencingVout = null;

            if ($vinData->get('prevout_type') === Vin::PREVOUT_TYPE_INDEX) {
                $referencingVout = self::getIndexVout($vinData);
            } elseif ($vinData->get('prevout_type') === Vin::PREVOUT_TYPE_HASH) {
                $referencingVout = self::getHashVout($vinData);
            }

            $vin = Vin::updateOrCreate([
                'transaction_id' => $transaction->id,
                'tx_height' => $vinData->get('tx_height'),
                'tx_index' => $vinData->get('tx_index'),
                'vout_id' => $referencingVout?->id,
            ], [
                'transaction_id' => $transaction->id,
                'prevout_type' => $vinData->get('prevout_type'),
                'coinbase' => $vinData->get('coinbase'),
                'tx_height' => $vinData->get('tx_height'),
                'tx_index' => $vinData->get('tx_index'),
                'scriptSig_asm' => $vinData->get('scriptSig_asm'),
                'scriptSig_hex' => $vinData->get('scriptSig_hex'),
                'rbf' => $vinData->get('rbf'),
            ]);

            if ($referencingVout !== null) {
                $vin->vout()->associate($referencingVout);
                $vin->save();
            }
        }
    }

    /**
     * @param Collection $vinData
     * @return Vout|null
     */
    private static function getIndexVout(Collection $vinData): ?Vout
    {
        return Vout::where('transaction_id', function ($query) use ($vinData) {
            return $query->select('id')
                ->from((new Transaction)->getTable())
                ->where('block_height', '=', $vinData->get('tx_height'))
                ->skip($vinData->get('tx_index'))
                ->take(1);
        })->skip($vinData->get('vout'))
            ->take(1)
            ->first();
    }

    /**
     * @param Collection $vinData
     * @param Transaction $transaction
     * @return Vout|null
     */
    private static function getHashVout(Collection $vinData): ?Vout
    {
        if ($vinData->get('txid') === Transaction::EMPTY_TXID) {
            return null;
        }

        return Transaction::firstWhere('txid', '=', $vinData->get('txid'))
            ->vouts
            ->get($vinData->get('vout'));
    }
}
