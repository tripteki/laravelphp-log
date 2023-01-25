<?php

use App\Http\Controllers\Log\LogController;
use Illuminate\Support\Facades\Route;

Route::prefix(config("adminer.route.user"))->middleware(config("adminer.middleware.user"))->group(function () {

    /**
     * Logs.
     */
    Route::get("logs", [ LogController::class, "index", ]);
    Route::get("logs/{log}", [ LogController::class, "show", ]);
    Route::put("logs/{context}", [ LogController::class, "update", ]);
});
