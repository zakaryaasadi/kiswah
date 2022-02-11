<?php

namespace App\Http\Requests;


class DonationTypePostRequest extends BaseRequest
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
            'name' => ['required', 'min:3'],
            'icon' => ['required', 'file', 'mimes:jpg,bmp,png'],
            'is_available' => ['required', 'boolean'],
            'is_acceptable' => ['nullable', 'boolean'],
        ];
    }
}
