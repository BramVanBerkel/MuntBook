<?php

namespace App\Http\Controllers;

use App\Repositories\Address\AddressRepository;

class RichListController extends Controller
{
    public function __construct(
        private readonly AddressRepository $addressRepository,
    ) {
    }

    public function __invoke()
    {
        return view('pages.richlist', [
            'richList' => $this->addressRepository->getRichList(),
        ]);
    }
}
