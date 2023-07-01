<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailRequest extends FormRequest
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
            'email' => 'required|email:filter,dns',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスは必須です。',
            'email.email'  => '正しいメールアドレスの形式で入力してください。',
        ];
    }
}
