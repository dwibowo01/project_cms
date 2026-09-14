<?php

declare(strict_types=1);

use App\Http\Controllers\ClientContactController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MasterItemCategoryController;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\InitializeTenancyBySubDomainWithCookieFallback;
use App\Http\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    InitializeTenancyBySubDomainWithCookieFallback::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/tenant', function () {
        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
    });
    Route::resource('users', UserController::class)->middleware(['auth', 'verified']);
    Route::resource('clients', ClientController::class)->middleware(['auth', 'verified']);
    Route::resource('clients.contacts', ClientContactController::class)
        ->except('show')
        ->middleware(['auth', 'verified']);
    Route::resource('ships', ShipController::class)->middleware(['auth', 'verified']);
    Route::resource('master-items', MasterItemController::class)
        ->except('show')
        ->middleware(['auth', 'verified']);
    Route::resource('master-item-categories', MasterItemCategoryController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware(['auth', 'verified']);
});
