<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class FooRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'foo.required' => 'foo is required',
            'bar.required'  => 'bar is required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'foo' => 'required|min:6|max:255',
            'bar' => 'required',
        ];
    }
}
