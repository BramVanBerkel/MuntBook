<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    const TRANSACTION_LENGTH = 64;

    const ADDRESS_LENGTH = 34;

    public function search(Request $request)
    {
        $query = $request->get('query');

        if($query === null) {
            return redirect()->route('home');
        }

        //query is a transaction
        if(strlen($query) === self::TRANSACTION_LENGTH) {
            return redirect()->route('transaction', ['transaction' => $query]);
        }

        //query is an address
        if(strlen($query) === self::ADDRESS_LENGTH) {
            return redirect()->route('address', ['address' => $query]);
        }

        //query is a block number
        if(is_numeric($query)) {
            return redirect()->route('block', ['block' => $query]);
        }

        //query was not found
        return redirect()->route('home');
    }
}
