<?php

namespace Modules\Cron\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\MergeSubject\Entities\MergeSubject;

class CrontRequest extends FormRequest
{

    public function rules()
    {
        $rules = [];
//        $rules['command'] =  'required|unique:el_cron,command,'.request('id');
        $rules['minute'] = 'required';
        $rules['hour'] = 'required';
        $rules['day'] = 'required';
        $rules['month'] = 'required';
        $rules['day_of_week'] = 'required';
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
    }

    public function attributes()
    {
        return [
//            'command' => trans('cron::language.task'),
            'minute' => trans('backend.minutes'),
            'hour' => trans('backend.hour'),
            'day' => trans('backend.day'),
            'month' => trans('backend.month'),
            'day_of_week' => trans('backend.day_of_week'),
        ];
    }
}
