<?php

namespace Modules\SubjectComplete\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectCompleteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = request('user_id');
        $subject_id = request('subject');
        $rules = [];
            $rules['subject'] =  ['required',Rule::unique('el_training_process','subject_id')->where(function ($query) use ($user_id,$subject_id){
                 $query->where('process_type','=',2)->where('user_id','=',$user_id)->where('subject_id','=',$subject_id);
            })];
            $rules['note'] = 'required|max:255';
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
            'subject' => trans('subjectcomplete::subjectcomplete.subject'),
            'note' => trans('subjectcomplete::subjectcomplete.note'),
        ];
    }
}
