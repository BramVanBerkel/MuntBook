<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        if($query === null) {
            return redirect()->route('home');
        }

        //query is either a transaction or a block hash
        if(strlen($query) === config('gulden.transaction_or_block_hash_length')) {
            if($block = Block::select('height')->firstWhere('hash', '=', $query)) {
                return redirect()->route('block', ['height' => $block->height]);
            }

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
