<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\TransactionController;
use App\Http\Controllers\API\V1\DashboardSummaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::group(['middleware' => ['auth:api']], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user-profile', [AuthController::class, 'profile']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('currency-list', [TransactionController::class, 'currency_list']);
        Route::post('transfer-balance', [TransactionController::class, 'store']);

        Route::get('most-conversion-user', [DashboardSummaryController::class, 'most_conversion_user']);
        Route::get('user-total-converted-amount/{user_id}', [DashboardSummaryController::class, 'user_total_converted_amount']);
        Route::get('user-third-highest-transaction-amount/{user_id}', [DashboardSummaryController::class, 'user_third_highest_transaction_amount']);
    });
});
