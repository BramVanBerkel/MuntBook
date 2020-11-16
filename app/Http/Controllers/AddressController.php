<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(string $address)
    {
        $address = Address::firstWhere('address', $address);

        if($address === null) {
            abort(404);
        }
        return view('layouts.pages.address', [
            'address' => $address
        ]);
    }
}
