<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        if($query === null) {
            return redirect()->route('home');
        }

        //query is a transaction
        // todo: transactions and block hashes have the same length, first check if a block with the given hash exists
        //  in the db and if true, redirect to that block, if false check for a transaction
        if(strlen($query) === config('gulden.transaction_length')) {
            return redirect()->route('transaction', ['transaction' => $query]);
        }

        //query is an address
        if(strlen($query) === config('gulden.address_length')) {
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
