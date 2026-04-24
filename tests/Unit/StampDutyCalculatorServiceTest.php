<?php

namespace Tests\Unit;

use App\Services\StampDutyCalculatorService;
use Tests\TestCase;

class StampDutyCalculatorServiceTest extends TestCase
{
    public function test_calculates_standard_residential_sdlt_correctly(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 295000,
            'is_first_time_buyer' => false,
            'is_additional_property' => false,
        ]);

        $this->assertSame('standard', $result['applied_scenario']);
        $this->assertSame(4750, $result['total']);
    }

    public function test_calculates_first_time_buyer_relief_correctly_at_500000(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 500000,
            'is_first_time_buyer' => true,
            'is_additional_property' => false,
        ]);

        $this->assertSame('first_time_buyer', $result['applied_scenario']);
        $this->assertSame(10000, $result['total']);
    }

    public function test_calculates_zero_sdlt_for_a_first_time_buyer_at_300000(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 300000,
            'is_first_time_buyer' => true,
            'is_additional_property' => false,
        ]);

        $this->assertSame('first_time_buyer', $result['applied_scenario']);
        $this->assertSame(0, $result['total']);
    }

    public function test_falls_back_to_standard_rates_when_first_time_buyer_price_exceeds_cap(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 501000,
            'is_first_time_buyer' => true,
            'is_additional_property' => false,
        ]);

        $this->assertSame('standard', $result['applied_scenario']);
        $this->assertSame(15050, $result['total']);
    }

    public function test_calculates_additional_property_surcharge_correctly(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 300000,
            'is_first_time_buyer' => false,
            'is_additional_property' => true,
        ]);

        $this->assertSame('additional_property', $result['applied_scenario']);
        $this->assertSame(20000, $result['total']);
    }

    public function test_calculates_additional_property_tax_correctly_at_125000(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 125000,
            'is_first_time_buyer' => false,
            'is_additional_property' => true,
        ]);

        $this->assertSame('additional_property', $result['applied_scenario']);
        $this->assertSame(6250, $result['total']);
    }

    public function test_handles_exact_standard_threshold_boundary(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 250000,
            'is_first_time_buyer' => false,
            'is_additional_property' => false,
        ]);

        $this->assertSame('standard', $result['applied_scenario']);
        $this->assertSame(2500, $result['total']);
    }

    public function test_handles_very_low_value_purchase(): void
    {
        $service = app(StampDutyCalculatorService::class);

        $result = $service->calculate([
            'price' => 1,
            'is_first_time_buyer' => false,
            'is_additional_property' => false,
        ]);

        $this->assertSame('standard', $result['applied_scenario']);
        $this->assertSame(0, $result['total']);
    }
}
