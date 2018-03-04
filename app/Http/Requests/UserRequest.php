<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::user()->isCompanyAdmin() || Auth::user()->isClientAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:128',
            'last_name' => 'required|string|max:128',
            'email' => 'required|email|string|max:128',
            'password' => 'sometimes|required|alpha_dash|different:email|min:8|confirmed',
            'phone_number' => 'required|regex:/^\+38\([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
            'show_price_status' => 'boolean',
            'role_id' => 'required|integer',
            'employer_id' => 'sometimes|nullable|integer',
        ];
    }
}
