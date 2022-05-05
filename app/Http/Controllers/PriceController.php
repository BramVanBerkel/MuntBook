<?php

namespace App\Http\Controllers;

use App\Repositories\PriceRepository;

/**
 * Class PriceController.
 */
class PriceController extends Controller
{
    public function __construct(
        private readonly PriceRepository $priceRepository
    ) {
    }

    public function __invoke()
    {
        return view('pages.price', [
            'currentPrice' => $this->priceRepository->getCurrentPrice(),
        ]);
    }
}
