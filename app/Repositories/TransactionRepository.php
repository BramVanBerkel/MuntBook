<?php


namespace App\Repositories;


use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransactionRepository
{
    public static function create(Collection $transaction, int $height): Transaction
    {
        return Transaction::create([
            'id' => $transaction->get('txid'),
            'size' => $transaction->get('size'),
            'vsize' => $transaction->get('vsize'),
            'version' => $transaction->get('version'),
            'locktime' => $transaction->get('locktime'),
            'blockhash' => $transaction->get('blockhash'),
            'confirmations' => $transaction->get('confirmations'),
            'blocktime' => new Carbon($transaction->get('blocktime')),
            'created_at' => new Carbon($transaction->get('time')),
            'block_height' => $height,
        ]);
    }
}
