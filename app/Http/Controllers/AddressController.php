<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(string $address)
    {
        $address = Address::firstWhere('address', $address);

        if ($address === null) {
            abort(404);
        }

        $vouts = $address->vouts;
        $vins = $address->vins;

        $transactions = $vouts->merge($vins)->map(function ($transaction) {
            if ($transaction instanceof Vin) {
                return collect([
                    'timestamp' => $transaction->created_at,
                    'value' => -$transaction->vout->value,
                ]);
            }

            return collect([
                'timestamp' => $transaction->created_at,
                'value' => $transaction->value,
            ]);
        })->sortByDesc('timestamp');

        return view('layouts.pages.address', [
            'address' => $address,
            'transactions' => $transactions,
        ]);
    }
}
