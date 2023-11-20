<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth:api')->group(function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);

    Route::prefix('transactions')->middleware('admin')->group(function () {
        Route::get('/', [TransactionsController::class, 'index']);
        Route::get('/{transaction_id}', [TransactionsController::class, 'show']);
        Route::post('create-transaction', [TransactionController::class, 'store']);
        Route::post('/{transaction_id}/update', [TransactionsController::class, 'update']);
        Route::get('/{transaction_id}/delete', [TransactionsController::class, 'destroy']);

        Route::get('all', [TransactionController::class, 'getReport']);
    });

    Route::get('/customer', [TransactionController::class, 'getUserTransaction']);

    Route::prefix('payments')->middleware('admin')->group(function () {
        Route::get('/transaction/{transaction_id}', [PaymentsController::class, 'index']);
        Route::get('/{payment_id}', [PaymentsController::class, 'show']);
        Route::post('/', [PaymentsController::class, 'store']);
        Route::post('/{payment_id}/update', [PaymentsController::class, 'update']);
        Route::get('/{payment_id}/delete', [PaymentsController::class, 'destroy']);
    });
});