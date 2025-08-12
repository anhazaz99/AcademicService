<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GiaovienRequest extends FormRequest
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
        $giaoVienId = $this->route('giaovien'); // Lấy ID từ route parameter
        
        return [
            'user_id' => 'required|exists:users,id',
            'ho_ten' => 'required|string|max:255',
            'gioi_tinh' => 'nullable|string|in:Nam,Nữ',
            'email' => 'required|email|max:255|unique:giao_viens,email,' . $giaoVienId,
            'dia_chi' => 'nullable|string|max:1000',
            'sdt' => 'nullable|string|max:20',
            'khoa_id' => 'required|exists:khoas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn người dùng',
            'user_id.exists' => 'Người dùng không tồn tại',
            'ho_ten.required' => 'Vui lòng nhập họ tên',
            'ho_ten.max' => 'Họ tên không được vượt quá 255 ký tự',
            'gioi_tinh.in' => 'Giới tính phải là Nam hoặc Nữ',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 1000 ký tự',
            'sdt.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'khoa_id.required' => 'Vui lòng chọn khoa',
            'khoa_id.exists' => 'Khoa không tồn tại',
        ];
    }

    /**
     * Trả về lỗi dưới dạng JSON khi validation thất bại
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
