<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(string $txid)
    {
        $transaction = Transaction::firstWhere('txid', '=', $txid);

        if ($transaction === null) {
            abort(404);
        }

        $vouts = $transaction->vouts()->where('type', '<>', Vout::TYPE_WITNESS)->get();

        $vins = Vin::select(['addresses.address', DB::raw('sum(vouts.value) as value')])
            ->leftJoin('vouts', 'vins.vout_id', '=', 'vouts.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'vouts.address_id')
            ->where('vins.transaction_id', '=', $transaction->id)
            ->where('vins.vout_id', '<>', null)
            ->groupBy('addresses.address')
            ->get();

        return view('layouts.pages.transaction')->with([
            'transaction' => $transaction,
            'vouts' => $vouts,
            'vins' => $vins,
        ]);
    }
}
