<?php

namespace App\Http\Controllers;

use App\Enums\AddressTypeEnum;
use App\Models\Address\Address;

class AddressController extends Controller
{
    public function index(string $address)
    {
        $address = Address::where('address', '=', $address)->firstOrFail();

        $view = match ($address->type) {
            AddressTypeEnum::ADDRESS() => view('pages.address'),
            AddressTypeEnum::MINING() => view('pages.mining-address'),
            AddressTypeEnum::WITNESS() => view('pages.witness-address'),
        };

        $transactions = (!$address->isDevelopmentAddress) ? $address->transactions->paginate() : $address->transactions->simplePaginate();

        return $view->with([
            'address' => $address,
            'transactions' => $transactions,
        ]);
    }
}
