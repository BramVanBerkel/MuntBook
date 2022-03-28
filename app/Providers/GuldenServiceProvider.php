<?php

namespace App\Providers;

use App\Services\GuldenService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class GuldenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GuldenService::class, function () {
            $rpcUser = config('gulden.rpc_user');
            $rpcPassword = config('gulden.rpc_password');
            $rpcHost = config('gulden.rpc_host').':'.config('gulden.rpc_port');

            $client = new Client([
                'base_uri' => $rpcHost,
                'auth' => [$rpcUser, $rpcPassword],
            ]);

            return new GuldenService($client);
        });
    }
}
