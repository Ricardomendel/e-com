<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegistrationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PortalController;

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
    return view('portal');
});

// Simple public storefront by merchant username
Route::get('/store/{username}', [\App\Http\Controllers\StorefrontController::class, 'home']);
Route::get('/store/{username}/products', [\App\Http\Controllers\StorefrontController::class, 'products']);
Route::get('/store/{username}/p/{slug}', [\App\Http\Controllers\StorefrontController::class, 'product']);
Route::get('/store/{username}/cart', [\App\Http\Controllers\StoreCartController::class, 'view']);
Route::post('/store/{username}/cart/add', [\App\Http\Controllers\StoreCartController::class, 'add']);
Route::post('/store/{username}/cart/update', [\App\Http\Controllers\StoreCartController::class, 'update']);
Route::post('/store/{username}/cart/remove', [\App\Http\Controllers\StoreCartController::class, 'remove']);
Route::get('/store/{username}/checkout', [\App\Http\Controllers\StoreCheckoutController::class, 'show']);
Route::post('/store/{username}/checkout', [\App\Http\Controllers\StoreCheckoutController::class, 'place']);
Route::get('/store/{username}/thank-you', [\App\Http\Controllers\StoreCheckoutController::class, 'thankYou']);

Route::get('login', function () {
    return to_route('filament.auth.login');
})->name('login');

// Admin self-registration, only if no admin/staff exists yet
Route::middleware('web')->group(function () {
    Route::post('/portal/login', [PortalController::class, 'login']);
    Route::get('/admin/register', [AdminRegistrationController::class, 'show']);
    Route::post('/admin/register', [AdminRegistrationController::class, 'store']);
    // Public registrations
    Route::get('/register/merchant', [RegistrationController::class, 'showMerchant']);
    Route::post('/register/merchant', [RegistrationController::class, 'storeMerchant']);
    Route::get('/register/staff', [RegistrationController::class, 'showStaff']);
    Route::post('/register/staff', [RegistrationController::class, 'storeStaff']);
});
// Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
