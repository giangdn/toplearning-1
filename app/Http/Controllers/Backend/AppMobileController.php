<?php

namespace App\Http\Controllers\Backend;

use App\AppMobile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic;

class AppMobileController extends Controller
{
    public function index() {
        $model_android = AppMobile::where('type', '=', 1)->first();
        $model_apple = AppMobile::where('type', '=', 2)->first();
        return view('backend.app_mobile.index',[
            'model_android' => $model_android,
            'model_apple' => $model_apple,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required',
            'link' => 'required',
        ], $request, AppMobile::getAttributeName());

        $model = AppMobile::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = path_upload($request->image);
        $model->link = $request->link;
        $model->type = $request->type;
        if (empty($request->id)){
            $model->created_by = \Auth::id();
        }
        $model->updated_by = \Auth::id();

        $save = $model->save();
        if($save){
            $uploadPath = data_file($model->image, true, 'upload');
            $resize_image = ImageManagerStatic::make($uploadPath);
            $resize_image->resize(132, 42);
            $resize_image->save($uploadPath);

            json_message('Lưu thành công', 'success');
        }else{
            json_message('Không thể lưu', 'error');
        }
    }
}
