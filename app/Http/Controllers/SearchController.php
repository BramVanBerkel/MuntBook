<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        if($query === null) {
            return redirect()->route('home');
        }

        //query is a transaction
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
