<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('query');

        if($query === null) {
            return redirect()->route('home');
        }

        //query is either a transaction or a block hash
        if(strlen($query) === config('gulden.transaction_or_block_hash_length')) {
            if($block = Block::select('height')->firstWhere('hash', '=', $query)) {
                return redirect()->route('block', ['block' => $block->height]);
            }

            return redirect()->route('transaction', ['txid' => $query]);
        }

        //query is a (witness)address
        if(strlen($query) === config('gulden.address_length') || strlen($query) === config('gulden.witness_address_length')) {
            dd('address');
            return redirect()->route('address', ['address' => $query]);
        }

        //query is a block number
        if(is_numeric($query)) {
            return redirect()->route('block', ['block' => $query]);
        }

        dd('not found');
        //query was not found
        return redirect()->route('home');
    }
}
