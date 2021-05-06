<?php

namespace App\Http\Controllers;

use App\Enums\PriceTimeframeEnum;
use App\Repositories\PriceRepository;
use Illuminate\Http\Request;
use Spatie\Enum\Enum;
use Spatie\Enum\Laravel\Rules\EnumRule;

/**
 * Class PriceController
 * @package App\Http\Controllers
 */
class PriceController extends Controller
{

    public function __construct(private PriceRepository $priceRepository)
    {
        //
    }

    public function index()
    {
        return view('layouts.pages.prices');
    }

    public function data(Request $request)
    {
        $request->validate([
            'timeframe' => [
                'required',
                'string',
                new EnumRule(PriceTimeframeEnum::class),
            ],
        ]);

        $request->transformEnums([
            'timeframe' => PriceTimeframeEnum::class,
        ]);

        return $this->priceRepository->getPrices($request->get('timeframe'), 'BITTREX');
    }
}
