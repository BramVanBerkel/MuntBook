<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AverageBlocktimeController;
use App\Http\Controllers\Api\NonceDistributionController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\SupplyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.')
    ->group(function () {
        Route::get('/prices/{timeframe}', [PriceController::class, 'index'])
            ->name('prices');

        Route::get('/nonce-distribution', NonceDistributionController::class)
            ->name('nonce-distribution');

        Route::get('/average-blocktime', AverageBlocktimeController::class)
            ->name('average-blocktime');

        Route::get('/total-supply', [SupplyController::class, 'totalSupply'])
            ->name('total-supply');

        Route::get('/circulating-supply', [SupplyController::class, 'circulatingSupply'])
            ->name('circulating-supply');

        Route::get('/addresses/{address}/mining-address-calendar', [AddressController::class, 'miningAddressCalendar']);
        Route::get('/addresses/{address}/witness-address-calendar', [AddressController::class, 'witnessAddressCalendar']);
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
