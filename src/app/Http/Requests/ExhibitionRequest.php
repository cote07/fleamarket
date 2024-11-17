<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'item_picture' => ['required', 'mimes:jpeg,png'],
            'content' => ['required', 'array'],
            'condition' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名は必須です',
            'description.required' => '商品説明は必須です',
            'description.max' => '商品説明は最大255文字までです',
            'item_picture.required' => '商品画像のアップロードは必須です',
            'item_picture.mimes' => '商品画像は.jpegまたは.png形式である必要があります',
            'content.required' => 'カテゴリーは選択必須です',
            'condition.required' => '商品の状態は選択必須です',
            'price.required' => '価格は入力必須です',
            'price.numeric' => '価格は数値である必要があります',
            'price.min' => '価格は0円以上である必要があります',
        ];
    }
}
