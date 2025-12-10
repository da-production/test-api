<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;


Route::post('/auth/connexion', [AuthenticationController::class,'login']);

Route::get('/doleance', [ApiController::class, 'get'])->middleware('auth:sanctum');
Route::get('/doleances', [ApiController::class, 'paginate'])->middleware('auth:sanctum');