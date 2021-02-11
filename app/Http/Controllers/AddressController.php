<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(string $address)
    {
        $address = Address::firstWhere('address', '=', $address);

        if ($address === null) {
            abort(404);
        }

        $vinsQuery = DB::table('vins')->select([
            'transactions.txid', 'blocks.created_at', DB::raw('sum(vouts.value) as value'), DB::raw("'vin' as type")
        ])->join('vouts', 'vins.vout_id', '=', 'vouts.id')
            ->join('transactions', 'vins.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('vouts.address_id', '=', $address->id)
            ->groupBy(['vins.transaction_id', 'transactions.txid', 'blocks.created_at']);

        $voutsQuery = DB::table('vouts')->select([
            'transactions.txid', 'blocks.created_at', DB::raw('sum(vouts.value) as value'), DB::raw("'vout' as type")
        ])->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('vouts.address_id', '=', $address->id)
            ->groupBy('vouts.transaction_id', 'transactions.txid', 'blocks.created_at');

        $totalValueIn = (float)DB::query()->fromSub($vinsQuery, 'vins')->sum('value');
        $totalValueOut = (float)DB::query()->fromSub($voutsQuery, 'vouts')->sum('value');

        $totalValue = $totalValueIn - $totalValueOut;

        $query = ($address->address !== Address::DEVELOPMENT_ADDRESS) ?
            $voutsQuery->union($vinsQuery) :
            $vinsQuery;

        $transactions = $query->orderByDesc('created_at')->paginate();

        return view('layouts.pages.address', [
            'address' => $address,
            'transactions' => $transactions,
            'totalValueIn' => $totalValueIn,
            'totalValueOut'  => $totalValueOut,
            'totalValue' => $totalValue
        ]);
    }
}
