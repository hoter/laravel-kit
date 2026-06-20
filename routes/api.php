<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:100')->group(function () {
    Route::get('/health', fn () => response()->json(['status' => 'ok']));
});
