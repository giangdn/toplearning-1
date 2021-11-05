<?php

namespace Modules\MergeSubject\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\MergeSubject\Entities\MergeSubject;

class MergeSubjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function prepareForValidation()
    {
        $id = request('id');
        $exists =MergeSubject::where(['id'=>$id,'status'=>1])->exists();
        if ($exists)
            throw ValidationException::withMessages([trans('mergesubject::mergesubject.message_subject_complete_deny_update')]);
    }

    public function rules()
    {
        $rules = [];

        if (request('mergeOption')==1){
            $rules['subject_old_complete'] = 'required|integer|min:0|max:100';
            $rules['subject_old'] = ['required',function($attribute,$value,$fail){
                if ((int)request('subject_old_complete') >count($value))
                    return $fail(trans('mergesubject::mergesubject.subject_old_complete_compare_subject_new'));
            }];
            $rules['subject_new'] =  'required|unique:el_merge_subject,subject_new,'.request('id');
        }else{
            $rules['subject_old_2'] = 'required';
            $rules['subject_new_2'] = 'required';
        }
        return $rules;
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
            'subject_old_complete' => trans('mergesubject::mergesubject.subject_old_complete'),
            'subject_old' => trans('mergesubject::mergesubject.subject_old'),
            'subject_new' => trans('mergesubject::mergesubject.subject_new'),
            'subject_old_2' => trans('mergesubject::mergesubject.subject_old'),
            'subject_new_2' => trans('mergesubject::mergesubject.subject_new'),
        ];
    }
}
