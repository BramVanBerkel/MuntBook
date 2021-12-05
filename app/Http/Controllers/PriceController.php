<?php

namespace App\Http\Controllers;

use App\Repositories\PriceRepository;

/**
 * Class PriceController
 * @package App\Http\Controllers
 */
class PriceController extends Controller
{
    public function __construct(
        private PriceRepository $priceRepository
    ) { }

    public function index()
    {
        return view('pages.price', [
            'currentPrice' => $this->priceRepository->getCurrentPrice(),
        ]);
    }
}
