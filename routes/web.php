<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use  App\Http\Controllers\PermissionController;




// In routes/web.php
Route::middleware(['auth', 'trans'])->group(function () {
   


    // Home route
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
// Route::get('mycode/{code}', function ($code) {
//     return "Code accepted: $code";
// })->middleware('mycode');

    // Resource routes
    Route::resource('role', RoleController::class);
    Route::get('role/delete/{id}', [RoleController::class, 'destroy'])->name('role.delete');
    Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');

    // Unit
    Route::get('unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::post('unit/update', [UnitController::class, 'update'])->name('unit.update');

    // Product
    Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/update', [ProductController::class, 'update'])->name('product.update');


   


//user
Route::resource('user', UserController::class)->except(['show', 'destroy']);
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::post('user/{id}', [UserController::class, 'update'])->name('user.update');



Route::post('user/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
Route::get('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');

//copany
// routes/web.php
Route::get('company', [CompanyController::class, 'index'])->name('company.index');
Route::get('company/edit', [CompanyController::class, 'edit'])->name('company.edit');
Route::post('company/update', [CompanyController::class, 'update'])->name('company.update');

Route::get('permission/{id}', [PermissionController::class, 'index'])->name('permission.index');
Route::post('permission/save', [PermissionController::class, 'save'])->name('permission.save');


//exchange

Route::get('exchange',[ExchangeController::class,'index'])->name('exchange.index');
//unit
Route::resource('unit', UnitController::class)->except(['show', 'destroy']);
Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
Route::post('unit/update', [UnitController::class, 'update'])->name('unit.update');
Route::get('unit/delete/{id}', [UnitController::class, 'delete'])->name('unit.delete');

//inventory/product
Route::resource('product', ProductController::class)->except(['show', 'destroy']);
Route::get('product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
Route::post('unit/update', [UnitController::class, 'update'])->name('product.update');
Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
Route::get('product/detail/{id}', [ProductController::class, 'detail'])->name('product.detail');
Route::get('product',[ProductController::class,'index'])->name('product.index');
Route::get('product/search',[ProductController::class,'search'])->name('product.search');
Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
Route::get('product/export', [ProductController::class, 'export'])->name('product.export');
Route::post('/product/import', [ProductController::class, 'import'])->name('product.import');
Route::get('product/low', [ProductController::class, 'low'])->name('product.low');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('user/logout', [LoginController::class, 'logout'])->name('user.logout');
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
//language



Route::get('lang/{lang}', [UserController::class,'change_lang'])->name('user.lang');
});

// Registration routes (outside auth middleware to allow guests)
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password reset routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/verify-code', [ResetPasswordController::class, 'verifyCode'])->name('password.verify-code');
Route::get('/password/reset-form', [ResetPasswordController::class, 'showResetForm'])->name('password.reset-form');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Auth::routes();

