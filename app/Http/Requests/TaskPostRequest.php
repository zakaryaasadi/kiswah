<?php

namespace App\Http\Requests;


class TaskPostRequest extends BaseRequest
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
            'location_id' => ['required', 'exists:locations,id'],
            'donations' => ['required', 'array'],
            'donations.*' => ['numeric', 'exists:donation_types,id'],
            'job_description' => ['nullable', 'min:5', 'string'],
            'job_delivery_datetime' => ['sometimes', 'date', 'after:yesterday'],
            'is_pickup' => ['required'],
            'images' => ['sometimes', 'file', 'mimes:jpg,bmp,png'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string', 'min:3'],
            'meta_data' => ['sometimes'],
        ];
    }
}
