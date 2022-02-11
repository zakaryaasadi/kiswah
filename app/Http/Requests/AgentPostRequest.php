<?php

namespace App\Http\Requests;


class AgentPostRequest extends BaseRequest
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
            'name' => [
                'present',
            ],
            'fleet_type' => [
                'required',
            ],
            'tags' => [
                'present',
            ],
            'status' => [
                'required',
            ],
            'email' => [
                'required',
            ],
            'phone' => [
                'required',
            ],
        ];
    }
}
