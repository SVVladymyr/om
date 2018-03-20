<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFilteringRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'statuses' => 'array',
            'clients' => 'array',
            'created_from' => 'date|nullable',
            'created_to' => 'date|after_or_equal:created_from|nullable',
            'expected_delivery_from' => 'date|nullable',
            'expected_delivery_to' => 'date|after_or_equal:expected_delivery_from|nullable',
            'delivery_from' => 'date|nullable',
            'delivery_to' => 'date|after_or_equal:delivery_from|nullable',
        ];
    }
}
