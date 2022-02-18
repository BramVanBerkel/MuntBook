<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use App\Repositories\TransactionRepository;
use App\Services\GuldenService;
use App\Transformers\TransactionOutputTransformer;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(
        private GuldenService $guldenService,
        private TransactionRepository $transactionRepository,
    )
    {}

    public function index(string $txid)
    {
        $transaction = $this->transactionRepository->getTransaction($txid);

        if($transaction === null) {
            abort(404);
        }

        $outputs = $this->transactionRepository->getOutputs($txid);

        if($transaction->type === Transaction::TYPE_WITNESS) { //todo: refactor the fee to the repo
            $fee = $outputs->sum('amount') -
                $this->guldenService->getWitnessReward($transaction->height);
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
