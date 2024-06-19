<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import', [PriceController::class, 'index']);
Route::post('/upload-excel', [PriceController::class, 'start']);

Route::get('products', [ProductController::class, 'index']);
Route::get('getProducts', [ProductController::class, 'getProducts'])->name('products.getProducts');
