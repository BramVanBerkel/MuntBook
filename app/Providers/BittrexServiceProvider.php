<?php

namespace App\Providers;

use App\Services\BittrexService;
use Illuminate\Support\ServiceProvider;

class BittrexServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BittrexService::class, fn () => new BittrexService());
    }
}
