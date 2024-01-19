<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\API\PostCodeController;

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

Route::group( ['middleware' => 'auth:api'], function() {

});
Route::post('/checkout',[\App\Http\Controllers\API\OrderController::class,'checkout'])->name('checkout');
Route::post('/verify-voucher',[\App\Http\Controllers\API\OrderController::class,'verifyVoucher'])->name('verify-voucher');

Route::post('/login',[\App\Http\Controllers\API\LoginController::class,'login'])->name('app.login');
Route::get('/get-temp-data/{token}',[\App\Http\Controllers\API\LoginController::class,'getTempData']);
Route::get('/login/oauth/{provider}', [\App\Http\Controllers\API\LoginController::class,'redirectToProvider']);
Route::get('/check-email', [\App\Http\Controllers\API\LoginController::class,'checkEmail']);
Route::post('/google-login', [\App\Http\Controllers\API\LoginController::class,'handleProviderCallback']);
Route::get('/checkService/{postcode}',[PostCodeController::class,'checkService']);
Route::get('/timeslots/{postcode}',[PostCodeController::class,'zipTimeSlots']);
Route::get('/updatedtimeslots/{postcode}',[PostCodeController::class,'getUpdatedTimeSlotsAndDeliveryDates']);
Route::get('/services/',[\App\Http\Controllers\API\PriceServiceController::class,'index']);
Route::get('/services/{id}',[\App\Http\Controllers\API\PriceServiceController::class,'show']);


