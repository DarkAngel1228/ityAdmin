<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCustomerAndRecordRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'unique:customers', 'between:1,20'],
            'city' => ['required', 'string', 'between:1,50'],
            'county' => ['required', 'string', 'between:1,50'],
            'hospital' => ['required', 'string', 'between:1,100'],
            'department' => ['required', 'string', 'between:1,100'],
            'phone' => ['required', 'string', 'between:5,20'],
            'information' => ['required', 'string', 'between:1,255'],
            'demand' => ['required', 'string', 'between:1,255'],
            'visit' => ['required', 'string', 'between:1,1000'],
            'channel_business' => ['required', 'string', 'between:1,255'],
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
