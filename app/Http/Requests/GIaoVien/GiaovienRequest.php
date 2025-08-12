<?php

namespace App\Http\Requests\GIaoVien;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GiaovienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $giaoVienId = $this->route('giaovien'); // ID giáo viên khi update

        return [
            'user_id' => 'required|integer|exists:users,id',

            'ma_gv' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_-]+$/', // Chỉ cho chữ, số, gạch ngang, gạch dưới
                'unique:giao_viens,ma_gv,' . $giaoVienId
            ],

            'ho_ten' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u' // Chỉ chữ + khoảng trắng, hỗ trợ dấu tiếng Việt
            ],

            'gioi_tinh' => 'nullable|string|in:Nam,Nữ,Khác',

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:giao_viens,email,' . $giaoVienId
            ],

            'dia_chi' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[\p{L}\p{N}\s,.-]+$/u' // Chữ, số, khoảng trắng, dấu phẩy, chấm, gạch ngang
            ],

            'sdt' => [
                'nullable',
                'regex:/^(0[2-9][0-9]{8}|0[0-9]{9})$/' // Hỗ trợ số di động + cố định hợp lệ
            ],

            'khoa_id' => 'required|integer|min:1|exists:khoas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn người dùng',
            'user_id.integer'  => 'Người dùng ID phải là số nguyên',
            'user_id.exists'   => 'Người dùng không tồn tại',

            'ma_gv.required'   => 'Vui lòng nhập mã giáo viên',
            'ma_gv.string'     => 'Mã giáo viên phải là chuỗi ký tự',
            'ma_gv.max'        => 'Mã giáo viên không được vượt quá 50 ký tự',
            'ma_gv.regex'      => 'Mã giáo viên chỉ được chứa chữ, số, gạch ngang hoặc gạch dưới',
            'ma_gv.unique'     => 'Mã giáo viên đã tồn tại',

            'ho_ten.required'  => 'Vui lòng nhập họ tên',
            'ho_ten.string'    => 'Họ tên phải là chuỗi ký tự',
            'ho_ten.max'       => 'Họ tên không được vượt quá 255 ký tự',
            'ho_ten.regex'     => 'Họ tên chỉ được chứa chữ và khoảng trắng',

            'gioi_tinh.in'     => 'Giới tính phải là Nam, Nữ hoặc Khác',

            'email.required'   => 'Vui lòng nhập email',
            'email.email'      => 'Email không hợp lệ',
            'email.max'        => 'Email không được vượt quá 255 ký tự',
            'email.unique'     => 'Email đã tồn tại',

            'dia_chi.max'      => 'Địa chỉ không được vượt quá 1000 ký tự',
            'dia_chi.regex'    => 'Địa chỉ chỉ được chứa chữ, số, khoảng trắng và một số ký tự (,.-)',

            'sdt.regex'        => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0',

            'khoa_id.required' => 'Vui lòng chọn khoa',
            'khoa_id.integer'  => 'Khoa ID phải là số nguyên',
            'khoa_id.min'      => 'Khoa ID phải lớn hơn 0',
            'khoa_id.exists'   => 'Khoa không tồn tại',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'  => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
