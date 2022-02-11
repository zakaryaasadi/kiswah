<?php

namespace App\Http\Requests;


class LocationsPostRequest extends BaseRequest
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
            'title' => ['required'],
            'is_default' => ['required'],
            'address' => ['required',],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'description' => ['nullable',],
            'floor' => ['nullable',],
            'apartment_no' => ['nullable',],
            'building_no' => ['nullable',],
        ];
    }
}
