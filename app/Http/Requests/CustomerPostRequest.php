<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPostRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'username' => ['required', 'unique:customers'],
            'email' => ['sometimes', 'unique:customers'],
            'address' => ['nullable'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
            'phone' => ['nullable', 'unique:customers'],
            'password' => ['required'],
        ];
    }
}
