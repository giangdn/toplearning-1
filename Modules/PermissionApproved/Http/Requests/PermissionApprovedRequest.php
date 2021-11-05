<?php

namespace Modules\PermissionApproved\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PermissionApprovedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        return $rules;
    }

    protected function prepareForValidation()
    {
        if (request('object_id')<=0 && !request('titles') && !request('employees'))
            throw ValidationException::withMessages([trans('Vui lòng chọn 1 trong tất cả các trường')]);
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
    public function attributes()
    {
        return [
        ];
    }
}
