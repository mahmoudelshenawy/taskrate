<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, "index"]);
Route::get('/currencies', [DashboardController::class, "currenciesPage"])->name("currenciesPage");
Route::post('/add-new-currency', [DashboardController::class, "addNewCurrency"])->name("addNewCurrency");
Route::put('/edit-currency', [DashboardController::class, "editCurrency"])->name("editCurrency");
Route::post('/delete-currency', [DashboardController::class, "deleteCurrency"])->name("deleteCurrency");
Route::get('/exchange_rates', [DashboardController::class, "exchangeRatesPage"])->name("exchangeRatesPage");
Route::post('/update-exchange_rates', [DashboardController::class, "updateExchangeRate"])->name("updateExchangeRate");
Route::get('/amounts', [DashboardController::class, "amountsPage"])->name("amountsPage");
Route::post('/add-new-amount', [DashboardController::class, "addNewAmount"])->name("addNewAmount");
Route::put('/edit-amount', [DashboardController::class, "editAmount"])->name("editAmount");
Route::post('/delete-amount', [DashboardController::class, "deleteAmount"])->name("deleteAmount");
// Route::get('/amounts', [DashboardController::class, "index"]);
