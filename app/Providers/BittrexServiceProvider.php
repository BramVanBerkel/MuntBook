<?php

namespace App\Providers;

use App\Services\BittrexService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class BittrexServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BittrexService::class, function () {
            return new BittrexService();
        });
    }
}
