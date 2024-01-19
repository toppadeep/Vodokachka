<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PumpMeterRecordsController;
use App\Http\Controllers\RateController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('resident', ResidentController::class);
Route::apiResource('period', PeriodController::class);
Route::apiResource('bill', BillController::class);
Route::apiResource('pump', PumpMeterRecordsController::class);
Route::apiResource('rate', RateController::class);

Route::post('resident/update/{id}', [ResidentController::class, 'update']);
Route::post('period/update/{id}', [PeriodController::class, 'update']);
Route::post('rate/update/{id}', [RateController::class, 'update']);
Route::post('bill/update/{id}', [BillController::class, 'update']);
Route::post('pump/update/{id}', [PumpMeterRecordsController::class, 'update']);
