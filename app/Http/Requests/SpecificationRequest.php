<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;

class SpecificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isClientAdmin() || Auth::user()->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*return [
            'name' => 'sometimes|required|string|max:128',
            'order_begin' => 'sometimes|integer|min:1|max:28',
            'order_end' => 'sometimes|integer|min:1|max:28',
        ];*/
        
        $rules = [
            'name' => 'sometimes|required|string|max:128',
            'order_begin' => 'sometimes|integer|min:1|max:31',
            'order_end' => 'sometimes|integer|min:1|max:31',
          ];

        if($this->request->has('limits')) {
            foreach($this->request->get('limits') as $limit => $lim) {
                $rules['limits.'.$limit] = 'numeric|min:1|nullable';
            }
        }
            
        if($this->request->has('periods')) {
            foreach($this->request->get('periods') as $period => $per) {
                $rules['periods.'.$period] = 'integer|min:1|nullable';
            }
        }

        return $rules;
    }
}
