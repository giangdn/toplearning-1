<?php

namespace App\Http\Controllers\Backend;

use App\Boxmap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GoogleMapController extends Controller
{
    public function index()
    {
        $boxmap = Boxmap::all();
       
        $dataMap  = Array();
        $dataMap['type']='FeatureCollection';
        $dataMap['features']=array();
        foreach($boxmap as $value){
            $feaures = array();
            $feaures['type']='Feature';
            $geometry = array("type"=>"Point","coordinates"=>[$value->lng, $value->lat]);
            $feaures['geometry']=$geometry;
            $properties=array('title'=>$value->title,"description"=>$value->description);
            $feaures['properties']= $properties;
            array_push($dataMap['features'],$feaures);
        }
        return view('backend.google_map.index')->with('dataArray',json_encode($dataMap));
    }

    public function store(Request $request)
    {
        $this->validateRequest([
            'title' => 'required',
            'description' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ], $request, Boxmap::getAttributeName());
        $create = Boxmap::create($request->all());
        if ($create) {
            return redirect('/admin-cp/google-map/')->with('success',"Add map success!");
        }   
        return redirect('/admin-cp/google-map/')->with($errors);    
    }

    public function listLocal() {
        return view('backend.google_map.list_local');
    }

    public function form($id = null) {
        $model = Boxmap::firstOrNew(['id' => $id]);
        $page_title = $model->id ? $model->name : trans('backend.add_new');
        return view('backend.google_map.form', [
            'model' => $model,
            'page_title' => $page_title
        ]);
    }

    public function getData(Request $request) {
        $search = $request->get('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Boxmap::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.google.map.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Boxmap::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'title' => 'required',
            'description' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ], $request, Boxmap::getAttributeName());
        
        $model = Boxmap::firstOrNew(['id' => $request->id]);
        $model->title = $request->title;
        $model->lat = $request->lat;
        $model->lng = $request->lng;
        $model->description = $request->description;        

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.google.map')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }
}
