<?php

namespace App\Http\Controllers;

use App\Enums\AddressTypeEnum;
use App\Models\Address\Address;

class AddressController extends Controller
{
    public function index(string $address)
    {
        $address = Address::firstWhere('address', '=', $address);

        $view = match($address->type) {
            AddressTypeEnum::ADDRESS() => view('pages.address'),
            AddressTypeEnum::MINING() => view('pages.mining-address'),
            AddressTypeEnum::WITNESS() => view('pages.witness-address'),
        };

        return $view->with([
            'address' => $address,
            'transactions' => $address->transactions->paginate(),
        ]);
    }
}
