<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkShortnerController;

Route::get('/', function () {
    return response()->json(["app" => "link-shortener"]);
});

Route::controller(LinkShortnerController::class)->group(function () {
    Route::get('/decode', 'show')->name('decode');
    Route::match(['get', 'post'], '/encode', 'store')->name('encode');
    Route::get('/decode/{url}', 'show')->name('decode');
    Route::match(['get', 'post'], '/encode/{url}', 'store')->name('encode');
});
