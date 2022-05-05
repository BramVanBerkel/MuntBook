<?php

namespace App\Http\Controllers;

use App\Repositories\Address\AddressRepository;

class AddressController extends Controller
{
    public function __construct(
        private readonly AddressRepository $addressRepository
    ) {
    }

    public function __invoke(string $address)
    {
        $type = $this->addressRepository->getType($address);

        if ($type === null) {
            abort(404);
        }

        $repository = $type->getRepository();

        $transactions = $repository->getTransactions($address);

        $address = $repository->getAddress($address);

        return $type->getView()->with([
            'address' => $address,
            'transactions' => $transactions,
        ]);
    }
}
