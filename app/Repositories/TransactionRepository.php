<?php


namespace App\Repositories;


use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransactionRepository
{
    public static function create(Collection $transaction, int $height): Transaction
    {
        return Transaction::updateOrCreate([
            'txid' => $transaction->get('txid'),
        ], [
            'size' => $transaction->get('size'),
            'vsize' => $transaction->get('vsize'),
            'version' => $transaction->get('version'),
            'locktime' => $transaction->get('locktime'),
            'blockhash' => $transaction->get('blockhash'),
            'confirmations' => $transaction->get('confirmations'),
            'blocktime' => new Carbon($transaction->get('blocktime')),
            'type' => self::getType($transaction),
            'created_at' => new Carbon($transaction->get('time')),
            'block_height' => $height,
        ]);
    }

    private static function getType(Collection $transaction): ?string
    {
        //todo: mining
        $witnessVout = $transaction->get('vout')->filter(function ($vout) {
            return $vout->has('PoW²-witness');
        })->first();

        if ($witnessVout !== null) {
            if ($witnessVout->get('PoW²-witness')->get('lock_from_block') === 0) {
                return Transaction::TYPE_WITNESS_FUNDING;
            }

            return Transaction::TYPE_WITNESS;
        }

        return null;
    }
}
