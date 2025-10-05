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
    return view('home', [
        "DEBUG" => config("app.debug"),
        "boxedCategories" => config("products.boxed_categories")
    ]);
})->name('home');


Route::get('/checkout', function () {
    return view('checkout', [ "DEBUG" => config("app.debug") ]);
})->name('checkout');



Route::post('order/create', [OrderController::class, 'create'])->name('order-create');



Route::get('/admin/excel-conversion', [ExcelConversionController::class, 'show']);
Route::post('/admin/excel-conversion', [ExcelConversionController::class, 'process'])->name('excel.convert');



Route::post('/deploy', function (\Illuminate\Http\Request $request) {
    if (!$request->has('token') || $request->input('token') !== config('app.command_token')) {
        abort(403, 'forbidden');
    }

    Artisan::call('app:deploy');

    return response()->json(['status' => 'Deployment executed']);
});

Route::post('/maintenance', function (\Illuminate\Http\Request $request) {
    if (!$request->has('token') || $request->input('token') !== config('app.command_token')) {
        abort(403, 'forbidden');
    }

    if(!$request->has('action')){
        abort(400, 'Bad Request: action parameter is required');
    }

    $maintenanceSecret = config('app.maintenance_secret');
    if (empty($maintenanceSecret)) {
        abort(500, 'Server Misconfiguration: maintenance secret is not set');
    }

    $commands = [
        'down' => 'down --render="errors.503" --secret="' . $maintenanceSecret . '"',
        'up' => 'up',
    ];

    $action = $request->input('action');
    if(!isset($commands[$action])) abort(400, 'Bad Request: invalid action');
    $exitCode = Artisan::call($commands[$action]);
    \Illuminate\Support\Facades\Log::info("Maintenance mode: $action by IP ".$request->ip());

    return response()->json([
        'status' => 'ok',
        'action' => $action,
        'exitCode' => $exitCode
    ]);
});

Route::get('{productName}/{productId}', [ProductController::class, 'show']);
