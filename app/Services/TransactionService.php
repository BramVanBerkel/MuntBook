<?php


namespace App\Services;


use App\Models\Block;
use App\Models\Transaction;
use Carbon\Traits\Creator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TransactionService
{
    public function saveTransaction(Collection $transaction, Block $block): Transaction
    {
        return Transaction::updateOrCreate([
            'txid' => $transaction->get('txid'),
        ], [
            'block_height' => $block->height,
            'size' => $transaction->get('size'),
            'vsize' => $transaction->get('vsize'),
            'version' => $transaction->get('version'),
            'locktime' => $transaction->get('locktime'),
            'blockhash' => $transaction->get('blockhash'),
            'confirmations' => $transaction->get('confirmations'),
            'blocktime' => new Carbon($transaction->get('blocktime')),
            'type' => $this->getType($transaction),
            'created_at' => new Carbon($transaction->get('time')),
        ]);
    }

    private function getType(Collection $transaction): ?string
    {
        //transactions with empty inputs generate new coins
        if($transaction->get('vin')->first()->get('coinbase') === "") {
            return Transaction::TYPE_MINING;
        }

        $witnessVout = $transaction->get('vout')->filter(function ($vout) {
            return $vout->has('PoW²-witness');
        })->first();

        if ($witnessVout !== null) {
            return Transaction::TYPE_WITNESS;
        }

        return Transaction::TYPE_TRANSACTION;
    }
}
