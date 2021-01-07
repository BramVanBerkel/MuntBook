<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(string $address)
    {
        $address = Address::firstWhere('address', $address);

        if ($address === null) {
            abort(404);
        }

        $vouts = DB::table('vouts')->select(['transactions.id', 'transactions.txid', 'vouts.value', 'blocks.created_at', DB::raw('\'vout\' as type')])
            ->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('vouts.address_id', '=', $address->id);

        $vins = DB::table('vins')->select(['transactions.id', 'transactions.txid', 'vouts.value', 'blocks.created_at', DB::raw('\'vin\' as type')])
            ->join('vouts', 'vins.vout_id', '=', 'vouts.id')
            ->join('transactions', 'vins.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('vouts.address_id', '=', $address->id);

        $transactions = $vouts->union($vins)->paginate();

        return view('layouts.pages.address', [
            'address' => $address,
            'transactions' => $transactions,
        ]);
    }
}
