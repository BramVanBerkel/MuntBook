<?php

namespace App\Http\Controllers;

use App\Enums\AddressTypeEnum;
use App\Repositories\AddressRepository;

class AddressController extends Controller
{
    public function __construct(
        private AddressRepository $addressRepository
    ) {}

    public function index(string $address)
    {
        $address = $this->addressRepository->findAddress($address);

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
