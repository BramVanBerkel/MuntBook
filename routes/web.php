<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\DifficultyHashrateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MissingBlockController;
use App\Http\Controllers\NodeInformationController;
use App\Http\Controllers\NonceDistributionController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** Home */
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/** Search */
Route::post('/', [SearchController::class, 'search'])
    ->name('search');

/** Transaction */
Route::get('/transaction/{txid}', [TransactionController::class, 'index'])
    ->name('transaction');

/** Address */
Route::get('/address/{address}', [AddressController::class, 'index'])
    ->name('address');

/** Block */
Route::get('/block/{block}', [BlockController::class, 'index'])
    ->name('block')
    ->where('block', '[0-9]+')
    ->missing(function(Request $request) {
        return Redirect::route('missing-block', ['block' => $request->route('block')]);
    });

Route::get('/missing-block/{block}', [MissingBlockController::class, 'index'])
    ->name('missing-block')
    ->where('block', '[0-9]+');

/** Nonce distribution */
Route::get('/nonce-distribution', [NonceDistributionController::class, 'index'])
    ->name('nonce-distribution');

Route::get('/nonce-distribution/data', [NonceDistributionController::class, 'data'])
    ->name('nonce-distribution.data');

/** Difficulty & Hashrate */
Route::get('/difficulty-hashrate', [DifficultyHashrateController::class, 'index'])
    ->name('difficulty-hashrate');
Route::get('/difficulty-hashrate/data', [DifficultyHashrateController::class, 'data'])
    ->name('difficulty-hashrate.data');

/** Prices */
Route::get('/prices', [PriceController::class, 'index'])
    ->name('prices');
Route::get('/prices/data', [PriceController::class, 'data'])
    ->name('prices.data');

/** Node Information */
Route::get('/node-information', [NodeInformationController::class, 'index'])
    ->name('node-information');
