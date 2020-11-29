<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(string $transaction)
    {
        $transaction = Transaction::with(['vouts' => function($query) {
            return $query->where('type', '<>', 'witness');
        }])->firstWhere('txid', $transaction);

        if($transaction === null) {
            abort(404);
        }

        return view('layouts.pages.transaction')->with([
            'transaction' => $transaction
        ]);
    }
}
