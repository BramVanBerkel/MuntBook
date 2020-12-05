<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NonceDistributionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransactionController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/', [SearchController::class, 'search'])->name('search');

Route::get('/transaction/{transaction}', [TransactionController::class, 'index'])->name('transaction');

Route::get('/address/{address}', [AddressController::class, 'index'])->name('address');

Route::get('/block/{block}', [BlockController::class, 'index'])->name('block')->where('block', '[0-9]+');

Route::get('/nonce-distribution', [NonceDistributionController::class, 'index'])->name('nonce-distribution');
Route::get('/nonce-distribution/data', [NonceDistributionController::class, 'data'])->name('nonce-distribution.data');
