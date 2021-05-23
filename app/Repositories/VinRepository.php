<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;

class VinRepository
{
    public function syncVins(Collection $vins, Transaction $transaction): void
    {
        foreach ($vins as $vinData) {
            //convert empty strings to null, to prevent inserting empty values in the DB
            $vinData = $vinData->map(fn($item) => $item === "" ? null : $item);

            $referencingVout = $this->getReferencingVout($vinData);

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

            if ($referencingVout instanceof Vout) {
                $vin->vout()->associate($referencingVout);
                $vin->save();
            }
        }
    }

    private function getReferencingVout(Collection $vinData): ?Vout
    {
        if ($vinData->get('prevout_type') === Vin::PREVOUT_TYPE_INDEX) {
            return $this->getIndexVout($vinData);
        } elseif ($vinData->get('prevout_type') === Vin::PREVOUT_TYPE_HASH) {
            return $this->getHashVout($vinData);
        }
        return null;
    }

    private function getIndexVout(Collection $vinData): ?Vout
    {
        return Vout::where('transaction_id', function ($query) use ($vinData) {
            return $query->select('id')
                ->from((new Transaction)->getTable())
                ->where('block_height', '=', $vinData->get('tx_height'))
                ->skip($vinData->get('tx_index'))
                ->take(1);
        })->firstWhere('n', '=', $vinData->get('vout'));
    }

    private function getHashVout(Collection $vinData): ?Vout
    {
        if ($vinData->get('txid') === Transaction::EMPTY_TXID) {
            return null;
        }

        return Transaction::firstWhere('txid', '=', $vinData->get('txid'))
            ?->vouts
            ->get($vinData->get('vout'));
    }
}
