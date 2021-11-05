<?php

namespace Modules\Permission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportUnitManagerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'import_file' => 'required|file'
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
    public function attributes()
    {
        return [
            'import_file' => 'File import',
        ];
    }
}
