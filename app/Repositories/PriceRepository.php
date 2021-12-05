<?php


namespace App\Repositories;


use App\Enums\PriceTimeframeEnum;
use App\Models\Price;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PriceRepository
{
    public function getCurrentPrice(): Price
    {
        return Price::query()
            ->orderByDesc('timestamp')
            ->first();
    }
}
