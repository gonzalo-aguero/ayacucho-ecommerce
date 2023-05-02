<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelConversionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
    return view('home', ["DEBUG" => config("app.debug")]);
})->name('home');


Route::get('/checkout', function () {
    return view('checkout', [ "DEBUG" => config("app.debug") ]);
})->name('checkout');



Route::post('order/create', [OrderController::class, 'create'])->name('order-create');



Route::get('/admin/excel-conversion', [ExcelConversionController::class, 'show']);
Route::post('/admin/excel-conversion', [ExcelConversionController::class, 'process'])->name('excel.convert');



Route::get('/site/{token}', function (string $token) {
    if(config('app.command_token') != null && config('app.command_token') == $token){
        $viewExitCode = Artisan::call('view:cache');
        $routeExitCode = Artisan::call('route:cache');

    }else{
        abort(404);
    }
    $exitCode = $viewExitCode. "<br>" . $routeExitCode;

    return $exitCode;
 });

Route::get('/site/{token}/{action}', function (string $token, string $action) {
    if(config('app.command_token') != null && config('app.command_token') == $token){
        if($action == "down"){
            $exitCode = Artisan::call('down --render="errors.503" --secret="40012jasdjj-246b-jiasdm120-afa1-dd72a4c43515"');
        }else if($action == "up"){
            $exitCode = Artisan::call('up');
        }else if($action == "migrate"){
            $exitCode = Artisan::call('migrate --seed');
        }else if($action == "symblinks"){
            $exitCode = Artisan::call('storage:link');
        }
    }else{
        abort(404);
    }

    return $exitCode;
});

Route::get('{productName}/{productId}', [ProductController::class, 'show']);
