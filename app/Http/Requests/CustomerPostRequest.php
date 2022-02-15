<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CustomerPostRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        $id = $this->id;
        return [
            'name' => ['required', 'min:4'],
            'username' => ['sometimes',
                Rule::unique('customers', 'username')->ignore($id, 'uuid')
            ],
            'email' => ['sometimes',
                Rule::unique('customers')->ignore($id, 'uuid')
            ],
            'address' => ['nullable'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
            'phone' => ['nullable',  Rule::unique('customers')->ignore($id, 'uuid')],
            'password' => ['sometimes', 'min:6'],
        ];
    }
}
