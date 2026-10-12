<?php

declare(strict_types=1);

use App\Http\Controllers\ClientContactController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConfirmOrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MasterItemCategoryController;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\ProjectBudgetController;
use App\Http\Controllers\ProjectBudgetItemController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTeamController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationItemController;
use App\Http\Controllers\RepairListController;
use App\Http\Controllers\RepairListItemController;
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
    Route::get('quotations/{quotation}/export', [QuotationController::class, 'export'])
        ->name('quotations.export')
        ->middleware(['auth', 'verified']);
    Route::post('quotations/{quotation}/revise', [QuotationController::class, 'revise'])
        ->name('quotations.revise')
        ->middleware(['auth', 'verified']);
    Route::resource('quotations', QuotationController::class)->middleware(['auth', 'verified']);
    Route::resource('quotations.items', QuotationItemController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware(['auth', 'verified']);
    Route::get('confirm-orders/{confirm_order}/export', [ConfirmOrderController::class, 'export'])
        ->name('confirm-orders.export')
        ->middleware(['auth', 'verified']);
    Route::resource('confirm-orders', ConfirmOrderController::class)->middleware(['auth', 'verified']);
    Route::resource('projects', ProjectController::class)->middleware(['auth', 'verified']);
    Route::get('project-teams', [ProjectTeamController::class, 'index'])
        ->name('project-teams.index')
        ->middleware(['auth', 'verified']);
    Route::get('project-teams/{project}/edit', [ProjectTeamController::class, 'edit'])
        ->name('project-teams.edit')
        ->middleware(['auth', 'verified']);
    Route::put('project-teams/{project}', [ProjectTeamController::class, 'update'])
        ->name('project-teams.update')
        ->middleware(['auth', 'verified']);

    Route::get('repair-lists', [RepairListController::class, 'index'])
        ->name('repair-lists.index')
        ->middleware(['auth', 'verified']);
    Route::get('repair-lists/{project}', [RepairListController::class, 'show'])
        ->name('repair-lists.show')
        ->middleware(['auth', 'verified']);
    Route::post('repair-lists/{project}/items', [RepairListItemController::class, 'store'])
        ->name('repair-lists.items.store')
        ->middleware(['auth', 'verified']);
    Route::put('repair-lists/{project}/items/{item}', [RepairListItemController::class, 'update'])
        ->name('repair-lists.items.update')
        ->middleware(['auth', 'verified']);
    Route::delete('repair-lists/{project}/items/{item}', [RepairListItemController::class, 'destroy'])
        ->name('repair-lists.items.destroy')
        ->middleware(['auth', 'verified']);

    Route::get('project-budgets', [ProjectBudgetController::class, 'index'])
        ->name('project-budgets.index')
        ->middleware(['auth', 'verified']);
    Route::get('project-budgets/{project}', [ProjectBudgetController::class, 'show'])
        ->name('project-budgets.show')
        ->middleware(['auth', 'verified']);
    Route::post('project-budgets/{project}/items', [ProjectBudgetItemController::class, 'store'])
        ->name('project-budgets.items.store')
        ->middleware(['auth', 'verified']);
    Route::put('project-budgets/{project}/items/{item}', [ProjectBudgetItemController::class, 'update'])
        ->name('project-budgets.items.update')
        ->middleware(['auth', 'verified']);
    Route::delete('project-budgets/{project}/items/{item}', [ProjectBudgetItemController::class, 'destroy'])
        ->name('project-budgets.items.destroy')
        ->middleware(['auth', 'verified']);

    Route::resource('invoices', InvoiceController::class)->middleware(['auth', 'verified']);
});
