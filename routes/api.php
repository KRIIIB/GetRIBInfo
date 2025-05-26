<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\RibController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('operations', [OperationController::class, 'index']);

Route::get('operations/rib/{rib_id}', [OperationController::class, 'getByRib']);

Route::post('operation', [OperationController::class, 'store']);

Route::put('operation', [OperationController::class, 'store']);

Route::delete('operation/{id}', [OperationController::class, 'destroy']);

Route::get('ribs', [RibController::class, 'index']);