<?php

namespace App\Http\Controllers;

use App\Models\Address\Address;
use App\Repositories\AddressRepository;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function __construct(
        private AddressRepository $addressRepository
    ) {}

    public function index(string $address)
    {
        $address = $this->addressRepository->findAddress($address);

        return view('layouts.pages.address.address', [
            'address' => $address,
        ]);
    }
}
