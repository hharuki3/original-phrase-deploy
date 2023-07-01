<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'japanese' => 'required',
            'phrase' => 'required',
            'memo' => 'required',
            'new_category' => [Rule::unique('categories', 'name')
                ->where(function ($query){
                    return $query->whereNull('deleted_at')
                                ->where('user_id', '=', \Auth::id());
                })
            ],
        ];
    }

    public function messages(){
        return [
            'japanese.required' => '*この項目は必須です。',
            'phrase.required' => '*この項目は必須です。',
            'memo.required' => '*この項目は必須です。',
            'new_category.unique' => '*カテゴリ名はすでに存在しています。',
            
        ];
    }
}
