<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsUpdateRequest extends BaseRequest
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
            'title' => ['sometimes', 'min:5'],
            'text' => ['sometimes', 'min:5'],
            'image' => ['sometimes', 'file', 'mimes:jpg,bmp,png'],
            'type' => ['nullable',],
            'url' => ['nullable', 'url'],
            'language' => ['nullable',],
            'variation' => ['nullable',],

        ];
    }
}
