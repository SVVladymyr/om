<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isCompanyAdmin() || 
                Auth::user()->isClientAdmin() || 
                Auth::user()->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(Auth::user()->isManager()) {
            return [                
                'specification_id' => 'sometimes|nullable|integer',                
            ];
        }elseif(Auth::user()->isCompanyAdmin()) {
            return [
                'guid' => 'required_if:ancestor_id,| nullable|string|size:36',
                'one_c_id' => 'sometimes|nullable|integer|min:1',
                'name' => 'required|string|max:255',
                'code' => 'sometimes|nullable|string|max:128',
                'manager_id' => 'sometimes|nullable|integer' ,
                'master_id' => 'sometimes|nullable|integer',
                'ancestor_id' => 'sometimes|nullable|integer',
                'specification_id' => 'sometimes|nullable|integer',
                'phone_number' => 'required|regex:/^\+38\([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
                'address' => 'required|string|max:255',
                'main_contractor' => 'sometimes|nullable|string|max:255',
                'organization' => 'sometimes|nullable|string|max:255',
            ]; 
        }elseif(Auth::user()->isClientAdmin()) {
            return [
                'ancestor_id' => 'required|integer',
                'guid' => 'nullable|string|size:36',
                'one_c_id' => 'sometimes|nullable|integer|min:1',
                'name' => 'required|string|max:255',
                'code' => 'sometimes|nullable|string|max:128',
                'manager_id' => 'sometimes|nullable|integer' ,
                'master_id' => 'sometimes|nullable|integer',
                'specification_id' => 'sometimes|nullable|integer',
                'phone_number' => 'required|regex:/^\+38\([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
                'address' => 'required|string|max:255',
                'main_contractor' => 'sometimes|nullable|string|max:255',
                'organization' => 'sometimes|nullable|string|max:255',
            ]; 
        }
        
    }
}
