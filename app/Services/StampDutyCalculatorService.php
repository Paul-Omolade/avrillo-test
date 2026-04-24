<?php

namespace App\Services;

class StampDutyCalculatorService
{
    public function calculate(array $input): array
    {
        $price = (int) $input['price'];
        $isFirstTimeBuyer = (bool) ($input['is_first_time_buyer'] ?? false);
        $isAdditionalProperty = (bool) ($input['is_additional_property'] ?? false);

        $notes = [];
        $scenario = 'standard';

        $standardBands = config('sdlt.standard');
        $bands = $standardBands;

        if ($isAdditionalProperty) {
            $scenario = 'additional_property';
            $bands = $this->applySurcharge($standardBands, (float) config('sdlt.additional_property.surcharge'));
        } elseif ($isFirstTimeBuyer && $price <= (int) config('sdlt.first_time_buyer.max_price')) {
            $scenario = 'first_time_buyer';
            $bands = config('sdlt.first_time_buyer.bands');
        } elseif ($isFirstTimeBuyer && $price > (int) config('sdlt.first_time_buyer.max_price')) {
            $notes[] = 'First-time buyer relief does not apply to purchases over £500,000, so standard rates were used.';
        }

        if ($isFirstTimeBuyer && $isAdditionalProperty) {
            $notes[] = 'First-time buyer relief was not applied because the purchase was treated as an additional property.';
        }

        $breakdown = $this->calculateBands($price, $bands);
        $total = array_sum(array_column($breakdown, 'tax'));
        $effectiveRate = $price > 0 ? round(($total / $price) * 100, 2) : 0.0;

        return [
            'total' => $total,
            'effective_rate' => $effectiveRate,
            'applied_scenario' => $scenario,
            'breakdown' => $breakdown,
            'notes' => $notes,
        ];
    }

    protected function applySurcharge(array $bands, float $surcharge): array
    {
        return array_map(function (array $band) use ($surcharge) {
            $band['rate'] = $band['rate'] + $surcharge;
            return $band;
        }, $bands);
    }

    protected function calculateBands(int $price, array $bands): array
    {
        $breakdown = [];
        $lower = 0;

        foreach ($bands as $band) {
            $upper = $band['up_to'];
            $rate = (float) $band['rate'];

            if ($price <= $lower) {
                break;
            }

            $taxableAmount = $upper === null
                ? $price - $lower
                : max(min($price, $upper) - $lower, 0);

            if ($taxableAmount <= 0) {
                $lower = $upper ?? $lower;
                continue;
            }

            $tax = (int) round($taxableAmount * ($rate / 100));

            $breakdown[] = [
                'label' => $this->makeBandLabel($lower, $upper),
                'taxable_amount' => $taxableAmount,
                'rate' => $rate,
                'tax' => $tax,
            ];

            $lower = $upper ?? $price;
        }

        return $breakdown;
    }

    protected function makeBandLabel(int $lowerExclusive, ?int $upperInclusive): string
    {
        if ($lowerExclusive === 0 && $upperInclusive !== null) {
            return 'Up to £' . number_format($upperInclusive);
        }

        if ($upperInclusive === null) {
            return 'Above £' . number_format($lowerExclusive);
        }

        return '£' . number_format($lowerExclusive + 1) . ' to £' . number_format($upperInclusive);
    }
}
