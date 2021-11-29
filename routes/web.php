<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceController;
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

Route::post('/search', SearchController::class)->name('search');

Route::get('/block/{block}', [BlockController::class, 'index'])->name('block')->where('block', '[0-9]{1,10}+');

Route::get('/transaction/{txid}', [TransactionController::class, 'index'])->name('transaction');

Route::get('/address/{address}', [AddressController::class, 'index'])->name('address');

Route::get('/price', [PriceController::class, 'index'])->name('price');

require __DIR__.'/auth.php';
