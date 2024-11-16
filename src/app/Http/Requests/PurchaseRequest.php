<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'payment.required' => '支払い方法を選択してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $postal_code = $this->input('postal_code');
            $address = $this->input('address');
            $building = $this->input('building');

            if (empty($postal_code) || empty($address) || empty($building)) {
                $validator->errors()->add('shipping_address', '配送先情報は必須です。');
            }
        });
    }
}
