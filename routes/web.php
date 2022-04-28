<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AverageBlocktimeController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\CalulatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MissingBlockController;
use App\Http\Controllers\NodeInformationController;
use App\Http\Controllers\NonceDistributionController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', HomeController::class)->name('home');

Route::post('/search', SearchController::class)->name('search');

Route::get('/block/{block}', BlockController::class)
    ->where('block', '[0-9]{1,10}+')
    ->name('block')
    ->missing(function (Request $request) {
        return Redirect::route('missing-block', [
            'block' => $request->route('block'),
        ]);
    });

Route::get('missing-block/{block}', MissingBlockController::class)->name('missing-block');

Route::get('/transaction/{txid}', TransactionController::class)->name('transaction');

Route::get('/address/{address}', AddressController::class)->name('address');

Route::get('/price', PriceController::class)->name('price');

Route::get('/node-information', NodeInformationController::class)->name('node-information');
Route::get('/nonce-distribution', NonceDistributionController::class)->name('nonce-distribution');
Route::get('/average-blocktime', AverageBlocktimeController::class)->name('average-blocktime');

Route::prefix('/calculator')->group(function () {
    Route::get('/witness', [CalulatorController::class, 'witnessYieldCalculator']);
    Route::get('/mining', [CalulatorController::class, 'miningYieldCalculator']);
});

require __DIR__.'/auth.php';
