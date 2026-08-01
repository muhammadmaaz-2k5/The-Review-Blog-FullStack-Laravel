<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DramaController;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\Api\ClipController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


