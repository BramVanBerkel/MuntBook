<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Transaction $transaction)
    {
        return view('layouts.pages.transaction')->with([
            'transaction' => $transaction
        ]);
    }
}
