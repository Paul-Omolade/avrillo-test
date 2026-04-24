<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateStampDutyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'price' => ['required', 'integer', 'min:1'],
            'purchase_date' => ['required', 'date'],
            'is_first_time_buyer' => ['required', 'boolean'],
            'is_additional_property' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'Enter the property price.',
            'price.integer' => 'The property price must be a whole number in pounds.',
            'price.min' => 'The property price must be greater than £0.',
            'purchase_date.required' => 'Select the purchase date.',
            'purchase_date.date' => 'Enter a valid purchase date.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
