<?php

namespace App\Repositories;

use App\Models\Price;

class PriceRepository
{
    public function getCurrentPrice(): ?Price
    {
        return Price::query()
            ->orderByDesc('timestamp')
            ->first();
    }
}
