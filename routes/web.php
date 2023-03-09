<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelConversionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/checkout', function () {
    return "This is the Checkout page.";
})->name('checkout');


Route::get('/admin/excel-conversion', [ExcelConversionController::class, 'show']);
Route::post('/admin/excel-conversion', [ExcelConversionController::class, 'process'])->name('excel.convert');
