<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsPostRequest extends BaseRequest
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
            'title' => ['required',],
            'text' => ['required',],
            'image' => ['required_if:variation,==,news', 'file', 'mimes:jpg,bmp,png'],
            'language' => ['nullable',],
            'variation' => ['required',],
            'cta.link' => ['required_if:variation,==,awareness', 'url'],
            'cta.text' => ['required_if:variation,==,awareness', 'string', 'min:3'],
        ];
    }
}
