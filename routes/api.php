<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotInvoiceController;
use App\Http\Controllers\BotSPKController;
use App\Http\Controllers\BotIncomeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bot/invoice/{token}/{filter?}', [BotInvoiceController::class, 'getInvoices']);

Route::get('/bot/spk/{token}/{filter?}', [BotSPKController::class, 'getSPK']);
Route::get('/bot/income/{token}', [BotIncomeController::class, 'getIncome']); //api income yearly
// routes/api.php
Route::get('bot/income/monthly/{token}', [BotIncomeController::class, 'getPerHari']); //api income monthly


Route::get('bot/income/daily-hourly/{token}', [BotIncomeController::class, 'getPerJamHarian']);


