<?php

namespace App\Http\Controllers;

use App\Enums\PriceTimeframeEnum;
use App\Repositories\PriceRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Enum\Laravel\Rules\EnumRule;

class PriceController extends Controller
{
    public function __construct(private PriceRepository $priceRepository){}

    public function index()
    {
        return view('layouts.pages.prices');
    }

    public function data(Request $request)
    {
        $request->validate([
            'timeframe' => [
                Rule::in(PriceTimeframeEnum::toValues())
            ],
        ]);

        return $this->priceRepository->getPrices($request->get('timeframe'), 'BITTREX');
    }
}
