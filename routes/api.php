<?php

use App\Http\Controllers\Api\AverageBlocktimeController;
use App\Http\Controllers\Api\NonceDistributionController;
use App\Http\Controllers\Api\PriceController;
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

Route::get('/prices/{timeframe}', [PriceController::class, 'index']);
Route::get('/nonce-distribution', NonceDistributionController::class);
Route::get('/average-blocktime', AverageBlocktimeController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
