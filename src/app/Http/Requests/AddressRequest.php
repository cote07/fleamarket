<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'name' => ['required', 'sometimes'],
            'postal_code' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です',
            'postal_code.required' => '郵便番号は必須です',
            'postal_code.regex' => '郵便番号は「XXXX-XXXX」の形式で入力してください',
            'address.required' => '住所は必須です',
            'building.required' => '建物名は必須です',
        ];
    }
}
