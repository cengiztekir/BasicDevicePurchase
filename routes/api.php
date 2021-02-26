<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\IosMockApi;
use App\Http\Controllers\AndroidMockApi;

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

Route::post('ApiRegister', [LoginController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('IosMockApi', [IosMockApi::class, 'CheckPurchase']);
Route::post('AndroidMockApi', [AndroidMockApi::class, 'CheckPurchase']);

Route::middleware('auth:api')->group( function () {
    Route::resource('purchase', PurchaseController::class)->only([
        'index','store', 'show'
    ]);
    Route::resource('register', DeviceController::class)->only([
        'index','store', 'show'
    ]);
});
