<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MissionPostRequest extends FormRequest
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
            'task_id' => [
                'required',
            ],
            'name' => [
                'present',
            ],
            'status' => [
                'required',
            ],
            'start_date' => [
                'present',
            ],
            'end_date' => [
                'present',
            ],
        ];
    }
}
