<?php

namespace App\Http\Controllers;

use App\Enums\PriceTimeframeEnum;
use App\Repositories\PriceRepository;
use Illuminate\Http\Request;
use Spatie\Enum\Laravel\Rules\EnumRule;

/**
 * Class PriceController
 * @package App\Http\Controllers
 */
class PriceController extends Controller
{
    public function index()
    {
        return view('pages.price');
    }
}
