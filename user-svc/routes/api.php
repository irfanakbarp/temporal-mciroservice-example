<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1/user-svc')->group(function () {
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'create']);
});
