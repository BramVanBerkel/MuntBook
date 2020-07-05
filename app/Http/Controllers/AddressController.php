<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(string $address)
    {
        dd("address {$address}");
    }
}
