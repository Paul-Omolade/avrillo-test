<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateStampDutyRequest;
use App\Services\StampDutyCalculatorService;
use Illuminate\Http\JsonResponse;

class StampDutyController extends Controller
{
    public function __construct(
        protected StampDutyCalculatorService $calculator
    ) {}

    public function calculate(CalculateStampDutyRequest $request): JsonResponse
    {
        $result = $this->calculator->calculate($request->validated());

        return response()->json($result);
    }
}
