<?php

declare(strict_types=1);

namespace App\Request\Login;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'mobile'    => 'required|digits:11|unique:users,mobile',
            'password'  => 'required|between:6,18|confirmed',
            'avatar'    => 'sometimes|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'gender'    => ['required', Rule::in(['男', '女', '未知'])]
        ];
    }
}
