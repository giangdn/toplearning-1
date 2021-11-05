<?php

namespace App\Http\Controllers\Backend;

use App\InfomationCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InfomationCompanyController extends Controller
{
    public function index() {
        $model = InfomationCompany::first();
        if(empty($model)) {
            $model = new InfomationCompany();
        }

        return view('backend.infomation_company.form', [
            'model' => $model,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'title' => 'required',
            'content' => 'required',
        ], $request, InfomationCompany::getAttributeName());

        $model = InfomationCompany::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.infomation_company')
            ]);
        }

        json_message('Không thể lưu', 'error');

    }
}
