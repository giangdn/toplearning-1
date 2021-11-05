<?php

namespace Modules\SplitSubject\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\MergeSubject\Entities\MergeSubject;

class SplitSubjectRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $id = request('id');
        $exists =MergeSubject::where(['id'=>$id,'status'=>1])->exists();
        if ($exists)
            throw ValidationException::withMessages([trans('splitsubject::splitsubject.message_subject_complete_deny_update')]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
            $rules['subject_new'] =  ['required',
                Rule::unique('el_merge_subject','subject_new')->where('type',2)->ignore(request('id'))];
            $rules['subject_old'] = 'required';
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
            'subject_new' => trans('splitsubject::splitsubject.subject_new'),
            'subject_old' => trans('splitsubject::splitsubject.subject_old'),
        ];
    }
}
