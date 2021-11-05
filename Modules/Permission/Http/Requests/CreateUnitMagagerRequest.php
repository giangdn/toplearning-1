<?php

namespace Modules\Permission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitMagagerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        if (request()->method=='POST')
            return [
                'unit_id' => 'required|unique:el_unit_manager_setting,unit_id,'.request('id'),
                'priority1' => 'required'
            ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
//    public function messages()
//    {
//        return [
//            'unit_id.required'=>'Vui lòng chọn đơn vị'
//        ];
//    }
    public function attributes()
    {
        return [
            'unit_id' => 'Đơn vị',
            'priority1' => 'Ưu tiên 1',
        ];
    }
}
