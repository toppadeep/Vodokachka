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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('resident', ResidentController::class);
Route::apiResource('period', PeriodController::class);
Route::apiResource('bill', BillController::class);
Route::apiResource('pump', PumpMeterRecordsController::class);
Route::apiResource('rate', RateController::class);

//ROUTES LIST

//GET|HEAD        / .................................................................. 
//POST            _ignition/execute-solution ignition.executeSolution › Spatie\Larave…
//GET|HEAD        _ignition/health-check ignition.healthCheck › Spatie\LaravelIgnitio…
//POST            _ignition/update-config ignition.updateConfig › Spatie\LaravelIgnit…
//GET|HEAD        api/bill ......................... bill.index › BillController@index
//POST            api/bill ......................... bill.store › BillController@store
//GET|HEAD        api/bill/{bill} .................... bill.show › BillController@show
//PUT|PATCH       api/bill/{bill} ................ bill.update › BillController@update
//DELETE          api/bill/{bill} .............. bill.destroy › BillController@destroy
//GET|HEAD        api/period ................... period.index › PeriodController@index
//POST            api/period ................... period.store › PeriodController@store
//GET|HEAD        api/period/{period} ............ period.show › PeriodController@show
//PUT|PATCH       api/period/{period} ........ period.update › PeriodController@update
//DELETE          api/period/{period} ...... period.destroy › PeriodController@destroy  
//GET|HEAD        api/pump ............. pump.index › PumpMeterRecordsController@index  
//POST            api/pump ............. pump.store › PumpMeterRecordsController@store  
//GET|HEAD        api/pump/{pump} ........ pump.show › PumpMeterRecordsController@show  
//PUT|PATCH       api/pump/{pump} .... pump.update › PumpMeterRecordsController@update  
//DELETE          api/pump/{pump} .. pump.destroy › PumpMeterRecordsController@destroy  
//GET|HEAD        api/rate ......................... rate.index › RateController@index  
//POST            api/rate ......................... rate.store › RateController@store  
//GET|HEAD        api/rate/{rate} .................... rate.show › RateController@show  
//PUT|PATCH       api/rate/{rate} ................ rate.update › RateController@update  
//DELETE          api/rate/{rate} .............. rate.destroy › RateController@destroy  
//GET|HEAD        api/resident ............. resident.index › ResidentController@index  
//POST            api/resident ............. resident.store › ResidentController@store  
//GET|HEAD        api/resident/{resident} .... resident.show › ResidentController@show  
//PUT|PATCH       api/resident/{resident} resident.update › ResidentController@update   
//DELETE          api/resident/{resident} resident.destroy › ResidentController@destr…  
//GET|HEAD        api/user ...........................................................  
//GET|HEAD        sanctum/csrf-cookie sanctum.csrf-cookie › Laravel\Sanctum › CsrfCoo… 