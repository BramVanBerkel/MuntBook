<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('query');

        if ($query === null) {
            return redirect()->route('home');
        }

        //query is either a transaction or a block hash
        if (strlen((string) $query) === config('munt.transaction_or_block_hash_length')) {
            if ($block = Block::select('height')->firstWhere('hash', '=', $query)) {
                return redirect()->route('block', ['block' => $block->height]);
            }

            return redirect()->route('transaction', ['txid' => $query]);
        }

        //query is a (witness)address
        if (strlen((string) $query) === config('munt.address_length') || strlen((string) $query) === config('munt.witness_address_length')) {
            return redirect()->route('address', ['address' => $query]);
        }

        //query is a block number
        if (is_numeric($query)) {
            return redirect()->route('block', ['block' => $query]);
        }

        //query was not found
        return redirect()->route('home');
    }
}
