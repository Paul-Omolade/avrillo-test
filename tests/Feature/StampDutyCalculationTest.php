<?php

namespace Tests\Feature;

use Tests\TestCase;

class StampDutyCalculationTest extends TestCase
{
    public function test_returns_validation_errors_for_invalid_input(): void
    {
        $response = $this->postJson('/api/sdlt/calculate', [
            'price' => 0,
            'purchase_date' => '2026-04-24',
            'is_first_time_buyer' => false,
            'is_additional_property' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }

    public function test_returns_validation_errors_when_purchase_date_is_missing(): void
    {
        $response = $this->postJson('/api/sdlt/calculate', [
            'price' => 295000,
            'is_first_time_buyer' => false,
            'is_additional_property' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['purchase_date']);
    }

    public function test_returns_a_structured_calculation_response(): void
    {
        $response = $this->postJson('/api/sdlt/calculate', [
            'price' => 295000,
            'purchase_date' => '2026-04-24',
            'is_first_time_buyer' => false,
            'is_additional_property' => false,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'total',
                'effective_rate',
                'applied_scenario',
                'breakdown',
                'notes',
            ]);
    }
}
