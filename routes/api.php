<?php

use App\Http\Controllers\Api\CalendarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/calendars/month', [CalendarController::class, 'month']);
Route::get('/calendars/convert', [CalendarController::class, 'convert']);
