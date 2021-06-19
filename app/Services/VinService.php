<?php


namespace App\Services;


use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;

class VinService
{
    public function saveVins(Collection $vins, Transaction $transaction): void
    {
        $vins->each(function(Collection $vin) use($transaction) {
            //convert empty strings to null, to prevent inserting empty values in the DB
            $vin = $vin->map(fn($item) => $item === "" ? null : $item);

            $referencingVout = $this->getReferencingVout($vin);

            $vin = Vin::updateOrCreate([
                'transaction_id' => $transaction->id,
                'tx_height' => $vin->get('tx_height'),
                'tx_index' => $vin->get('tx_index'),
                'vout_id' => $referencingVout?->id,
            ], [
                'transaction_id' => $transaction->id,
                'prevout_type' => $vin->get('prevout_type'),
                'coinbase' => $vin->get('coinbase'),
                'tx_height' => $vin->get('tx_height'),
                'tx_index' => $vin->get('tx_index'),
                'scriptSig_asm' => $vin->get('scriptSig_asm'),
                'scriptSig_hex' => $vin->get('scriptSig_hex'),
                'rbf' => $vin->get('rbf'),
            ]);

            if ($referencingVout instanceof Vout) {
                $vin->vout()->associate($referencingVout);
                $vin->save();
            }
        });
    }

    private function getReferencingVout(Collection $vin): ?Vout
    {
        if ($vin->get('prevout_type') === Vin::PREVOUT_TYPE_INDEX) {
            return $this->getIndexVout($vin);
        } elseif ($vin->get('prevout_type') === Vin::PREVOUT_TYPE_HASH) {
            return $this->getHashVout($vin);
        }
        return null;
    }

    private function getIndexVout(Collection $vin): ?Vout
    {
        return Vout::where('transaction_id', function ($query) use ($vin) {
            return $query->select('id')
                ->from((new Transaction)->getTable())
                ->where('block_height', '=', $vin->get('tx_height'))
                ->skip($vin->get('tx_index'))
                ->take(1);
        })->firstWhere('n', '=', $vin->get('vout'));
    }

    private function getHashVout(Collection $vin): ?Vout
    {
        if ($vin->get('txid') === Transaction::EMPTY_TXID) {
            return null;
        }

        return Transaction::firstWhere('txid', '=', $vin->get('txid'))
            ?->vouts
            ->get($vin->get('vout'));
    }
}
