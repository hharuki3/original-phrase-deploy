<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'new_group' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'new_group.required' => 'この項目は必須です。',
        ];
    }
}
