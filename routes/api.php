<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\FileController;
use App\Http\Controllers\Api\v1\CompanyController;
use App\Http\Controllers\Api\v1\DeviceController;
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


//quẹt thẻ
//Route::post('customer_swipe_card', [\App\Http\Controllers\Api\v1\SwapCardController::class, 'swipe_card'])->name("customer_swipe_card.swipe_card");





Route::namespace('api/v1')->prefix('v1')->group(function () {

    Route::get('test', function () {
        return "test Ok";
    });

    //để tạm
  //  Route::post('customer_swipe_card', [\App\Http\Controllers\Api\v1\SwapCardController::class, 'swipe_card'])->name("customer_swipe_card.swipe_card");

    // ======================== PUBLIC ROUTE ==============================
    // Cường: Authentication
    Route::group(['prefix' => 'auth'], function () {

        Route::post('/login', [LoginController::class, 'login']);
    });

    // Download File
    Route::get('/static/{path}', [FileController::class, 'getFile'])
        ->where('path', '.*');


    // ======================== END PUBLIC ROUTE ==============================


    Route::post('customer_swipe_card', [\App\Http\Controllers\Api\v1\SwapCardController::class, 'swipe_card'])->name("customer_swipe_card.swipe_card");

    Route::post('customer_swipe_card_test', [\App\Http\Controllers\Api\v1\SwapCardController::class, 'swipe_card_test'])->name("customer_swipe_card.swipe_card_test");


    // ============================== PROTECTED ROUTES =========================
    Route::group(['middleware' => ['auth:api']], function () {
        // Auth
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/logout', [LoginController::class, 'logout']);
        });



//        //quẹt thẻ
//        Route::post('customer_swipe_card', [\App\Http\Controllers\Api\v1\SwapCardController::class, 'swipe_card'])->name("customer_swipe_card.swipe_card");
    });
    // ============================== END PROTECTED ROUTES =========================


});
