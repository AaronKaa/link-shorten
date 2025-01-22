<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkShortnerController;

Route::get('/', function () {
    return response()->json(["app" => "link-shortener"]);
});

Route::controller(LinkShortnerController::class)->group(function () {
    Route::get('/decode', 'show')->name('decode');
    Route::get('/encode', 'store')->name('encode');
});
