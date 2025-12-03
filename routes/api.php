<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;


Route::post('/auth/connexion', [AuthenticationController::class,'login']);

Route::get('/test', [ApiController::class, 'get'])->middleware('auth:sanctum');