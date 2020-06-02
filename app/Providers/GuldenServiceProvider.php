<?php

namespace App\Providers;

use App\Services\Gulden;
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
        $this->app->singleton(Gulden::class, function() {
            $rpcUser = config("gulden.rpc_user");
            $rpcPassword = config("gulden.rpc_password");
            $rpcHost = config('gulden.rpc_host') . ":" . config('gulden.rpc_port');
            return new Gulden($rpcUser, $rpcPassword, $rpcHost);
        });
    }
}
