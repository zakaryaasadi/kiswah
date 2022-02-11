<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'team_id' => [
                'required',
            ],
            'first_name' => [
                'present',
            ],
            'last_name' => [
                'present',
            ],
            'company_name' => [
                'present',
            ],
            'company_address' => [
                'present',
            ],
            'email' => [
                'required',
            ],
            'phone' => [
                'required',
            ],
            'email_verified_at' => [
                'present',
            ],
            'password' => [
                'required',
            ],
            'timezone' => [
                'required',
            ],
            'task_access' => [
                'required',
            ],
            'add_driver_access' => [
                'required',
            ],
            'remember_token' => [
                'present',
            ],
        ];
    }
}
