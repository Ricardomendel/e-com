<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Test routes for debugging (remove in production)
Route::get('/test-staff-login', function () {
    Auth::loginUsingId(2); // Login as staff user (ID 2)
    return redirect('/admin/login'); // Try to access admin - should redirect to staff dashboard
});

Route::get('/test-merchant-login', function () {
    Auth::loginUsingId(4); // Login as merchant user (ID 4)
    return redirect('/admin/login'); // Try to access admin - should redirect to merchant dashboard
});

Route::get('/test-customer-login', function () {
    // Create a customer user for testing
    $customer = \App\Models\User::firstOrCreate(
        ['email' => 'customer@test.com'],
        [
            'name' => 'Test Customer',
            'username' => 'testcustomer',
            'password' => bcrypt('password'),
            'role' => 'CUSTOMER',
            'status' => 'ACTIVE',
            'phone' => '1234567890',
            'email_verified_at' => now(),
        ]
    );
    Auth::loginUsingId($customer->id);
    return redirect()->route('customer.dashboard');
});


// Direct dashboard access for testing
Route::get('/staff-test', function () {
    Auth::loginUsingId(2); // Login as staff
    return redirect()->route('staff.dashboard');
});

Route::get('/merchant-test', function () {
    Auth::loginUsingId(4); // Login as merchant  
    return redirect()->route('merchant.dashboard');
});

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
    Route::get('/register/customer', [RegistrationController::class, 'showCustomer']);
    Route::post('/register/customer', [RegistrationController::class, 'storeCustomer']);
});
// Auth::routes();

// Pending approval page
Route::get('/pending', function (Request $request) {
    $message = $request->session()->get('pending_message', 'Your registration has been submitted successfully.');
    return view('auth.pending', compact('message'));
})->name('auth.pending');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Staff Dashboard Routes
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/merchants', [App\Http\Controllers\Staff\DashboardController::class, 'merchants'])->name('merchants');
    Route::get('/orders', [App\Http\Controllers\Staff\DashboardController::class, 'orders'])->name('orders');
    Route::post('/merchants/{user}/approve', [App\Http\Controllers\Staff\DashboardController::class, 'approveMerchant'])->name('approve-merchant');
});

// Merchant Dashboard Routes
Route::middleware(['auth'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Merchant\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [App\Http\Controllers\Merchant\DashboardController::class, 'products'])->name('products');
    Route::get('/orders', [App\Http\Controllers\Merchant\DashboardController::class, 'orders'])->name('orders');
    Route::get('/finances', [App\Http\Controllers\Merchant\DashboardController::class, 'finances'])->name('finances');
});

// Customer Dashboard Routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/shop', [App\Http\Controllers\Customer\DashboardController::class, 'shop'])->name('shop');
    Route::get('/cart', [App\Http\Controllers\Customer\DashboardController::class, 'cart'])->name('cart');
    Route::get('/orders', [App\Http\Controllers\Customer\DashboardController::class, 'orders'])->name('orders');
    Route::get('/profile', [App\Http\Controllers\Customer\DashboardController::class, 'profile'])->name('profile');
    
    // Cart management routes
    Route::post('/cart/add/{productId}', [App\Http\Controllers\Customer\DashboardController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{productId}', [App\Http\Controllers\Customer\DashboardController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [App\Http\Controllers\Customer\DashboardController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart/clear', [App\Http\Controllers\Customer\DashboardController::class, 'clearCart'])->name('cart.clear');
});

// Product Management Routes (for merchants)
Route::middleware(['auth'])->prefix('products')->name('products.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\ProductController::class, 'store'])->name('store');
    Route::put('/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
});

