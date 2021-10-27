<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use App\Services\GuldenService;
use App\Transformers\TransactionOutputTransformer;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(
        private GuldenService $guldenService,
        private TransactionOutputTransformer $transactionOutputTransformer
    )
    {}

    public function index(string $txid)
    {
        $transaction = Transaction::where('txid', '=', $txid)->firstOrFail();

        $vins = Vin::select(['addresses.address', DB::raw('sum(vouts.value) as value')])
            ->leftJoin('vouts', 'vins.vout_id', '=', 'vouts.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'vouts.address_id')
            ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
            ->where('vins.transaction_id', '=', $transaction->id)
            ->whereNotNull('vins.vout_id')
            ->groupBy('addresses.address')
            ->get();

        $vouts = $transaction->vouts()
            ->whereHas('address')
            ->where('type', '<>', Vout::TYPE_WITNESS)
            ->get();

        if($transaction->type === Transaction::TYPE_WITNESS) {
            $fee = $vouts->sum('value') - $this->guldenService->getWitnessReward($transaction->block_height);
        } else {
            $input_total = $vins->sum('value');
            $output_total = $vouts->sum('value');
            $fee = $input_total !== 0 ? $input_total - $output_total : 0;
        }

        return view('pages.transaction')->with([
            'transaction' => $transaction,
            'outputs' => $this->transactionOutputTransformer->transform($vins->concat($vouts)),
            'fee' => $fee,
            'rewarded_witness_address' => $transaction->rewarded_witness_address,
        ]);
    }
}
