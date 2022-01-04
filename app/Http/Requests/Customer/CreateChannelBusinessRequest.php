<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateChannelBusinessRequest extends FormRequest
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
            'hospital' => ['required', 'string', 'between:1,100'],
            'channel_business' => ['required', 'string', 'between:1,255'],
            'phone' => ['nullable', 'string', 'between:1,20'],
            'company' => ['nullable', 'string', 'between:1,100'],
            'produce' => ['nullable', 'string', 'between:1,100'],
            'money' => ['nullable', 'double'],
            'business_time' => ['nullable', 'string', 'between:1,100'],
        ];
    }


    /**
     * 获取验证错误的自定义属性。
     *
     * @return array
     */
    public function attributes()
    {
        return [

        ];
    }

    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [

        ];
    }
}
