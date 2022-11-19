<?php

namespace App\Providers;

use App\Services\MuntService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class MuntServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MuntService::class, function () {
            $rpcUser = config('munt.rpc_user');
            $rpcPassword = config('munt.rpc_password');
            $rpcHost = config('munt.rpc_host').':'.config('munt.rpc_port');

            $client = new Client([
                'base_uri' => $rpcHost,
                'auth' => [$rpcUser, $rpcPassword],
            ]);

            return new MuntService($client);
        });
    }
}
