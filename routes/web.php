<?php

use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

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
});


// api routes
Route::post('/UserRegistration',[\App\Http\Controllers\UserController::class,'UserRegistration']);
Route::post('/UserLogin',[\App\Http\Controllers\UserController::class,'UserLogin']);
Route::post('/SendOtpCode',[\App\Http\Controllers\UserController::class,'SendOtpCode']);
Route::post('/VerifyOtp',[\App\Http\Controllers\UserController::class,'VerifyOtp']);
Route::post('/ResetPassword',[\App\Http\Controllers\UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);




// page route
Route::get('/userLogin',[\App\Http\Controllers\UserController::class,'LoginPage']);
Route::get('/userRegistration',[\App\Http\Controllers\UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[\App\Http\Controllers\UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[\App\Http\Controllers\UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[\App\Http\Controllers\UserController::class,'ResetPasswordPage']);

Route::get('/Dashboard',[\App\Http\Controllers\UserController::class,'DashboardPage']);
