<?php

namespace Modules\Certificate\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Certificate\Entities\Certificate;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class CertificateController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('certificate::backend.certificate.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        Certificate::addGlobalScope(new DraftScope());
        $query = Certificate::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('code','like','%' . $search . '%');
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
            $row->image = image_file($row->image);
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }

    public function form(Request $request) {
        $model = Certificate::select(['id','image','code','name'])->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest ([
            'code' => 'required',
            'name' => 'required',
            'image' => 'required|string'
        ], $request, Certificate::getAttributeName());
        
        $model = Certificate::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        
        $sizes = config('image.sizes.medium');
        $model->image = upload_image($sizes, $request->image);

        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = \Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }
}
