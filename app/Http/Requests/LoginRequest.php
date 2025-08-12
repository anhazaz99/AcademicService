<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                'exists:users,username',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            ],
        ];
    }
    // Tùy chỉnh thông báo lỗi
    public function messages()
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ, số và dấu gạch dưới.',
            'username.exists' => 'Tên đăng nhập không tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 số và 1 ký tự đặc biệt.',
        ];
    }
    //Trả về dữ liệu dạng JSON khi validation
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ])
        );
    }
}
