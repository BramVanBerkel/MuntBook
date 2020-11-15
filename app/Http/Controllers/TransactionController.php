<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(string $transaction)
    {
        $transaction = Transaction::firstWhere('txid', $transaction);

        if($transaction === null) {
            return redirect()->route('home');
        }

        return view('layouts.pages.transaction')->with([
            'transaction' => $transaction
        ]);
    }
}
