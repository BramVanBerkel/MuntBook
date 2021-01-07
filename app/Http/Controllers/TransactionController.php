<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(string $txid)
    {
        $transaction = Transaction::firstWhere('txid', '=', $txid);

        if ($transaction === null) {
            abort(404);
        }

        $vins = Vin::select(['addresses.address', DB::raw('sum(vouts.value) as value')])
            ->leftJoin('vouts', 'vins.vout_id', '=', 'vouts.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'vouts.address_id')
            ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
            ->where('vins.transaction_id', '=', $transaction->id)
            ->where('vins.vout_id', '<>', null)
            ->groupBy('addresses.address')
            ->get();

        $vouts = $transaction->vouts()->where('type', '<>', Vout::TYPE_WITNESS)->get();

        if($transaction->type === Transaction::TYPE_WITNESS) {
            $fee = $vouts->sum('value') - Transaction::WITNESS_REWARD;
            $witness_address = $transaction->vouts()->first()->address;
        } else {
            $input_total = $vins->sum('value');
            $output_total = $vouts->sum('value');
            $fee = $input_total !== 0 ? $input_total - $output_total : 0;
        }

        return view('layouts.pages.transaction')->with([
            'transaction' => $transaction,
            'vouts' => $vouts,
            'vins' => $vins,
            'fee' => $fee,
            'witness_address' => $witness_address ?? null,
        ]);
    }
}
