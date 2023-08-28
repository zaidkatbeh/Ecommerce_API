<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user as userController;
use App\Http\Resources\user as userResource;
use App\Http\Controllers\addressesController;
use App\Http\Controllers\countriesAndRegionsController;
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
Route::get('userNotFound',\App\Http\Controllers\userNotFound::class)->name('userNotFound');
Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return new userResource($request->user());
});

Route::get('createCategories',[\App\Http\Controllers\products::class,'index']);
Route::get('getCategories',\App\Http\Controllers\categories::class);
Route::apiResource('products',\App\Http\Controllers\products::class);
Route::apiResource('cart',\App\Http\Controllers\cart::class)->middleware('auth:sanctum');
Route::post('checkEmailUsage',\App\Http\Controllers\checkEmailIsntUsed::class);
Route::post('checkUserNameUsage',\App\Http\Controllers\checkUserNameIsntUser::class);
Route::controller(userController::class)->group(function (){
    Route::post('register','register');
    Route::post('login','login');
    Route::post('logout','logout')->middleware('auth:sanctum');
});
Route::post('addresses/{id}',[addressesController::class,'update'])->middleware('auth:sanctum');
Route::apiResource('addresses',addressesController::class)->middleware('auth:sanctum');
Route::prefix('countries')->controller(countriesAndRegionsController::class)->group(function (){
    Route::get('/','countries');
    Route::get('{id}/regions','regions');
});
Route::post('validatePhoneNumber',\App\Http\Controllers\ValidatePhoneNumber::class);
