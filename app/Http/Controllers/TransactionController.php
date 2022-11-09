<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\BlockService;

class TransactionController extends Controller
{
    public function __construct(
        private readonly BlockService $blockService,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    public function __invoke(string $txid)
    {
        $transaction = $this->transactionRepository->getTransaction($txid);

        $outputs = $this->transactionRepository->getOutputs($txid);

        if ($transaction->type === Transaction::TYPE_WITNESS) {
            $fee = $outputs->sum('amount') -
                $this->blockService->getBlockSubsidy($transaction->height)->witness;
        } else {
            $fee = abs($outputs->where('type', '=', 'input')->sum('value')) -
                $outputs->where('type', '=', 'output')->sum('value');
        }

        return view('pages.transaction')->with([
            'transaction' => $transaction,
            'outputs' => $outputs,
            'fee' => $fee,
        ]);
    }
}
