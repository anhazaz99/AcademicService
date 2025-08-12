<?php

namespace App\Http\Requests\GIaoVien;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateGV extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $giaoVienId = $this->route('giaovien'); // Lấy ID giáo viên từ route

        return [
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
                'regex:/^[\p{L}\p{N}\s,.-]+$/u'
            ],
            'gioi_tinh' => 'nullable|string|in:Nam,Nữ,Khác',
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:giao_viens,email,' . $giaoVienId
                // 'regex:/@ten-truong\.edu\.vn$/i' // Bỏ comment nếu muốn bắt buộc email trường
            ],
            'dia_chi' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[\p{L}\p{N}\s,.-]+$/u' // Cho chữ, số, khoảng trắng, dấu phẩy/chấm/gạch ngang
            ],
            'sdt' => [
                'nullable',
                'regex:/^(0[2-9][0-9]{8}|0[0-9]{9})$/' // 10 số, bắt đầu bằng 0
            ],
            'khoa_id' => 'required|integer|min:1|exists:khoas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ma_gv.required'   => 'Vui lòng nhập mã giáo viên',
            'ma_gv.string'     => 'Mã giáo viên phải là chuỗi ký tự',
            'ma_gv.max'        => 'Mã giáo viên không được vượt quá 50 ký tự',
            'ma_gv.regex'      => 'Mã giáo viên chỉ được chứa chữ, số, gạch ngang hoặc gạch dưới',
            'ma_gv.unique'     => 'Mã giáo viên đã tồn tại',

            'ho_ten.required'   => 'Vui lòng nhập họ tên',
            'ho_ten.string'     => 'Họ tên phải là chuỗi ký tự',
            'ho_ten.max'        => 'Họ tên không được vượt quá 255 ký tự',
            'ho_ten.regex'      => 'Họ tên chỉ được chứa chữ và khoảng trắng',

            'gioi_tinh.in'      => 'Giới tính phải là Nam, Nữ hoặc Khác',

            'email.required'    => 'Vui lòng nhập email',
            'email.email'       => 'Email không hợp lệ',
            'email.max'         => 'Email không được vượt quá 255 ký tự',
            'email.unique'      => 'Email đã tồn tại',
            'email.regex'       => 'Email phải thuộc miền cho phép',

            'dia_chi.string'    => 'Địa chỉ phải là chuỗi ký tự',
            'dia_chi.max'       => 'Địa chỉ không được vượt quá 1000 ký tự',
            'dia_chi.regex'     => 'Địa chỉ chỉ được chứa chữ, số và một số ký tự cho phép (,.-)',

            'sdt.regex'         => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0',

            'khoa_id.required'  => 'Vui lòng chọn khoa',
            'khoa_id.integer'   => 'Khoa ID phải là số nguyên',
            'khoa_id.min'       => 'Khoa ID phải lớn hơn 0',
            'khoa_id.exists'    => 'Khoa không tồn tại',
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
