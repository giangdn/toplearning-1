<?php

namespace Modules\PermissionApproved\Http\Requests;

use App\Models\Categories\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\PermissionApproved\Entities\ApprovedProcess;

class ApprovedProcessRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['unit_id'] = 'required';
        return $rules;
    }
    protected function prepareForValidation()
    {
        $unit_id = request('unit_id');
        if (!$unit_id)
            throw ValidationException::withMessages(['Vui lòng chọn đơn vị']);
        $hierarchy = Unit::getHierarchyByUnit($unit_id);
//        dd($hierarchy, substr($hierarchy,0,strripos($hierarchy,'/')),strripos($hierarchy,'/'));
        $explode = explode('/',$hierarchy);
        $error= false; $hierarchyClone = $hierarchy;
        foreach ($explode as $index => $item) {
            $sec = substr($hierarchyClone,0,strripos($hierarchyClone,'/'));
            $hierarchyClone = $sec;
            $exists = ApprovedProcess::where('hierarchy',$sec)->exists();
            if ($exists){
                $error = true;
                break;
            }
        }

        $exists = ApprovedProcess::where('hierarchy','like',$hierarchy."%")->exists();
        if ($error || $exists)
            throw ValidationException::withMessages([trans('Phân nhánh quy trình phê duyệt này đã tồn tại')]);
    }
    public function authorize()
    {
        return true;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function messages()
    {
        return [
            'unit_id.required'=>'Vui lòng chọn đơn vị'
        ];
    }
}
