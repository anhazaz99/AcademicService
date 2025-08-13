<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MonHocRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Có thể thêm logic kiểm tra quyền ở đây
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $monHocId = $this->route('monhoc') ?? $this->route('id');
        
        return [
            'ten_mon' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mon_hocs')->ignore($monHocId)
            ],
            'so_tin_chi' => 'required|integer|min:1|max:20',
            'khoa_id' => 'required|exists:khoas,id',
            'giao_vien_id' => 'required|exists:giao_viens,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'ten_mon.required' => 'Tên môn học không được để trống',
            'ten_mon.string' => 'Tên môn học phải là chuỗi',
            'ten_mon.max' => 'Tên môn học không được vượt quá 255 ký tự',
            'ten_mon.unique' => 'Tên môn học đã tồn tại',
            'so_tin_chi.required' => 'Số tín chỉ không được để trống',
            'so_tin_chi.integer' => 'Số tín chỉ phải là số nguyên',
            'so_tin_chi.min' => 'Số tín chỉ phải lớn hơn 0',
            'so_tin_chi.max' => 'Số tín chỉ không được vượt quá 20',
            'khoa_id.required' => 'Khoa không được để trống',
            'khoa_id.exists' => 'Khoa không tồn tại',
            'giao_vien_id.required' => 'Giáo viên không được để trống',
            'giao_vien_id.exists' => 'Giáo viên không tồn tại'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ],422)
        );
    }
}
