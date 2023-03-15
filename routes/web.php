<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelConversionController;
use Illuminate\Support\Facades\Artisan;

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
    return view('home', ["DEBUG"=>env("APP_DEBUG")]);
})->name('home');
Route::get('/checkout', function () {
    return "This is the Checkout page.";
})->name('checkout');


Route::get('/admin/excel-conversion', [ExcelConversionController::class, 'show']);
Route::post('/admin/excel-conversion', [ExcelConversionController::class, 'process'])->name('excel.convert');

Route::get('/site-set/{action}', function (string $action) {
    if($action == "down"){
        $exitCode = Artisan::call('down --render="errors.503" --secret="40012jasdjj-246b-jiasdm120-afa1-dd72a4c43515"');
    }else{
        $exitCode = Artisan::call('up');
    }

    return $exitCode;
});
