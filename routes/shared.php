<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SwitchTenantController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantUserController;
use App\Http\Middleware\InitializeTenancyBySubDomain;
use Illuminate\Support\Facades\Route;

Route::middleware([
    InitializeTenancyBySubDomain::class,
])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');

    Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/tenant/switch/{tenant}', SwitchTenantController::class)->name('tenant.switch')->middleware('verified');
        Route::resource('roles', RoleController::class)->middleware('verified');
        Route::resource('tenants', TenantController::class)->middleware('verified');
        Route::resource('tenants.users', TenantUserController::class)->middleware('verified');
    });
});
