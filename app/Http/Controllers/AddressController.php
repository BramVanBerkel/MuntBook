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
        $type = $this->addressRepository->getType($address);

        if($type === null) {
            abort(404);
        }

        $transactions = match ($type) {
            AddressTypeEnum::ADDRESS => $this->addressRepository->getAddressTransactions($address),
            AddressTypeEnum::MINING => $this->addressRepository->getMiningAddressTransactions($address),
            AddressTypeEnum::WITNESS => $this->addressRepository->getWitnessAddressTransactions($address),
        };

        $address = match ($type) {
            AddressTypeEnum::ADDRESS => $this->addressRepository->getAddress($address),
            AddressTypeEnum::MINING => $this->addressRepository->getMiningAddress($address),
            AddressTypeEnum::WITNESS => $this->addressRepository->getWitnessAddress($address),
        };

        return match ($type) {
            AddressTypeEnum::ADDRESS => view('pages.address', ['address' => $address, 'transactions' => $transactions]),
            AddressTypeEnum::MINING => view('pages.mining-address', ['address' => $address, 'transactions' => $transactions]),
            AddressTypeEnum::WITNESS => view('pages.witness-address', ['address' => $address, 'transactions' => $transactions]),
        };
    }
}
