<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SinhVienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'   => 'exists:users,id',
            'ho_ten'    => 'required|string|max:255',
            'ngay_sinh' => 'nullable|date|before_or_equal:today',
            'gioi_tinh' => 'required|in:Nam,Nu',
            'lop_id'    => 'required|exists:lops,id',
            'ma_sv'     => 'required|string|max:255|unique:sinh_viens,ma_sv',
            'dia_chi'   => 'nullable|string|max:255',
            'sdt'       => 'nullable|string|regex:/^0[0-9]{9}$/',
            'email'     => 'nullable|email|max:50|unique:sinh_viens,email',
            'password' => [
                'string',
                'min:8',
                'max:64',
                'confirmed', // cần password_confirmation
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'   => 'Vui lòng chọn người dùng.',
            'user_id.exists'     => 'Người dùng không tồn tại.',
            'ho_ten.required'    => 'Vui lòng nhập họ tên.',
            'ngay_sinh.date'     => 'Ngày sinh không hợp lệ.',
            'ngay_sinh.before_or_equal' => 'Ngày sinh phải nhỏ hơn hoặc bằng hôm nay.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'gioi_tinh.in'       => 'Giới tính phải là Nam hoặc Nữ.',
            'lop_id.required'    => 'Vui lòng chọn lớp.',
            'lop_id.exists'      => 'Lớp không tồn tại.',
            'ma_sv.required'     => 'Vui lòng nhập mã sinh viên.',
            'ma_sv.unique'       => 'Mã sinh viên đã tồn tại.',
            'sdt.regex'          => 'Số điện thoại không hợp lệ (phải đủ 10 chữ số, bắt đầu bằng 0).',
            'email.email'        => 'Email không hợp lệ.',
            'email.unique'       => 'Email đã tồn tại.',
            'password.min' => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 số và 1 ký tự đặc biệt.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
