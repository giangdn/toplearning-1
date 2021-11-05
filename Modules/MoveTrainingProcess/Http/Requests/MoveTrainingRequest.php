<?php

namespace Modules\MoveTrainingProcess\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MoveTrainingRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $employee_old = trim(request('user_old'));
        $employee_new = trim(request('user_new'));
        if ($employee_old=='' || $employee_new==''){
             throw ValidationException::withMessages([trans('movetrainingprocess::language.invalid_field_empty')]);
        }

        if ($employee_new == $employee_old)
             throw ValidationException::withMessages([trans('movetrainingprocess::language.message_not_match_employee_code')]);
    }
    public function rules()
    {
        return [];
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

}
