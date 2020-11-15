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
            return redirect()->route('home');
        }
        return view('layouts.pages.address', [
            'address' => $address
        ]);
    }
}
