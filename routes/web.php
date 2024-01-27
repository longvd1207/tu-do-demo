<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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

Route::get('/test', function (){
   dd(1);
})->name('test');
// Route::get('test', [\App\Http\Controllers\Auth\RoleController::class, 'index']);


// ======================================= Auth =========================================================

//trang chủ của admin desktop
//Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/login', [\App\Http\Controllers\Admin\UserController::class, 'check_login'])->name('check_login');
Route::get('/login', [\App\Http\Controllers\Admin\UserController::class, 'login'])->name('login');

Route::get('/active', [\App\Http\Controllers\Admin\UserController::class, 'login'])->name('active');
Route::post('/active', [\App\Http\Controllers\Admin\UserController::class, 'check_active'])->name('check_active_post');

//Route::prefix('register_online')->group(function () {
    Route::get('/', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'ladipage'])->name('register_online.ladipage');
    Route::get('/getTicketTypeDetail', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'getTicketTypeDetail'])->name('register_online.getTicketTypeDetail');
    Route::get('/payment', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'payment'])->name('register_online.payment');
    Route::get('/getTicket/{key}', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'getTicket'])->name('register_online.getTicket');
    Route::get('/errorPayment/{key}', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'errorPayment'])->name('register_online.errorPayment');
    Route::get('/sendEmail/{order_id}', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'sendEmail'])->name('register_online.sendEmail');


    //hiển thị kết quả đơn hàng sau khi KH mua hàng xong
   // Route::get('/payment_result', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'payment_result'])->name('register_online.payment_result');

    //Kh tìm kiếm đơn hàng
   Route::post('/payment_search', [\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'payment_search'])->name('register_online.payment_search');

   Route::match(['get', 'post'],'/payment_result',[\App\Http\Controllers\RegisterOnline\RegisterOnlineController::class, 'payment_result'])->name('register_online.payment_result');
//});
