<?php

namespace App\Http\Requests;


class DonationTypePatchRequest extends BaseRequest
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
            'name' => ['sometimes', 'min:3'],
            'icon' => ['sometimes', 'file', 'mimes:jpg,bmp,png'],
//            'is_available' => ['sometimes','boolean'],
//            'is_acceptable' => ['nullable', 'boolean'],
        ];
    }
}
