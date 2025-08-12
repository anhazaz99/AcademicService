<?php

namespace App\Http\Requests\diemSV;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DiemRequest extends FormRequest
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
        $diemId = $this->route('diem');
        $rules = [
            'sinh_vien_id' => 'required|exists:sinh_viens,id',
            //'mon_hoc_id' => 'required|exists:mon_hocs,id',
            'diem_TX' => 'nullable|numeric|min:0|max:10',
            'lan_thi' => 'nullable|integer|min:1|max:3',
            'ngay_thi' => 'nullable|date',
            'diem_DK' => 'nullable|numeric|min:0|max:10',
            'diem_thi' => 'nullable|numeric|min:0|max:10',
        ];
        
        return $rules; // Thêm return này
    }

    public function messages(): array
    {
        return [
            'sinh_vien_id.required' => 'Vui lòng chọn sinh viên',
            'sinh_vien_id.exists' => 'Sinh viên không tồn tại',
            //'mon_hoc_id.required' => 'Vui lòng chọn môn học',
            //'mon_hoc_id.exists' => 'Môn học không tồn tại',
            'diem_TX.required' => 'Vui lòng nhập điểm thường xuyên',
            'diem_TX.numeric' => 'Điểm thường xuyên phải là số',
            'diem_TX.min' => 'Điểm thường xuyên không được nhỏ hơn 0',
            'diem_TX.max' => 'Điểm thường xuyên không được lớn hơn 10',
            'diem_DK.numeric' => 'Điểm điều kiện phải là số',
            'diem_DK.min' => 'Điểm điều kiện không được nhỏ hơn 0',
            'diem_DK.max' => 'Điểm điều kiện không được lớn hơn 10',
            'diem_thi.numeric' => 'Điểm thi phải là số',
            'diem_thi.min' => 'Điểm thi không được nhỏ hơn 0',
            'diem_thi.max' => 'Điểm thi không được lớn hơn 10',
            'lan_thi.min' => 'Lần thi phải từ 1 trở lên',
            'lan_thi.max' => 'Lần thi không được quá 3',
            'ngay_thi.date' => 'Ngày thi không hợp lệ',
        ];
    }

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
