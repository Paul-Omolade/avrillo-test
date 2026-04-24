<?php

use App\Http\Controllers\Api\StampDutyController;

Route::post('/sdlt/calculate', [StampDutyController::class, 'calculate']);
