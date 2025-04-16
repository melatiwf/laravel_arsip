<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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

Route::post('login',[LoginController::class,'login']);
Route::post('register',[LoginController::class,'register']);
Route::post('logout',[LoginController::class,'logout']);

Route::apiResource('/pengunjungs', App\Http\Controllers\Api\PengunjungController::class);
Route::apiResource('/dokumentasis', App\Http\Controllers\Api\DokumentasiController::class);
Route::apiResource('/pengumumans', App\Http\Controllers\Api\PengumumanController::class);
Route::apiResource('/kunjungans', App\Http\Controllers\Api\KunjunganController::class);





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
