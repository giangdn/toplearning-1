<?php

namespace Modules\Permission\Http\Controllers;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Permission\Entities\UnitManagerSetting;
use Modules\Permission\Http\Requests\CreateUnitMagagerRequest;
use Modules\Permission\Http\Requests\ImportUnitManagerRequest;
use Modules\Permission\Imports\UnitManagerImport;

class UnitManagerSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()){
             $search = $request->input('search');
             $user_manager = $request->input('user_code');
             $sort = $request->input('sort', 'id');
             $order = $request->input('order', 'desc');
             $offset = $request->input('offset', 0);
             $limit = $request->input('limit', 20);
//             Unit::addGlobalScope(new DraftScope());
             $query = UnitManagerSetting::query();
             $query->select([
                 'el_unit_manager_setting.id',
                 'el_unit_manager_setting.unit_id',
                 'b.code',
                 'b.name',
                 'el_unit_manager_setting.priority1',
                 'el_unit_manager_setting.priority2',
                 'el_unit_manager_setting.priority3',
                 'el_unit_manager_setting.priority4',
             ]);
             $query->join('el_unit as b','el_unit_manager_setting.unit_id','=','b.id');
             if ($search) {
                 $query->where(function ($subquery) use ($search) {
                     $subquery->orWhere('el_unit.code', 'like', '%'. $search .'%');
                     $subquery->orWhere('el_unit.name', 'like', '%'. $search .'%');
                 });
             }
             $count = $query->count();
             $query->orderBy('el_unit_manager_setting.'.$sort, $order);
             $query->offset($offset);
             $query->limit($limit);
             $rows = $query->get();
             foreach ($rows as $row) {
                 $row->priority1 = $this->getTitleCode($row->priority1);
                 $row->priority2 = $this->getTitleCode($row->priority2);
                 $row->priority3 = $this->getTitleCode($row->priority3);
                 $row->priority4 = $this->getTitleCode($row->priority4);
                 $row->edit_url = route('backend.permission.unitmanager.edit',$row->id);
             }
             json_result(['total' => $count, 'rows' => $rows]);
         }
        
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);
         
        return view('permission::UnitManager.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }
    private function getTitleCode($priority){
        if (!$priority)
            return '';
        $priority = json_decode($priority);
        $titleArr = Titles::whereIn('id',$priority)->select('code')->get();
        return $titleArr->implode('code',', ');
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        $model = new UnitManagerSetting();
        return view('permission::UnitManager.create',[
            'model'=>$model,
            'page_title'=>'Thêm quản lý đơn vị',
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateUnitMagagerRequest $request)
    {

        $priority1 = $request->priority1?json_encode(array_map('intval',$request->priority1)):null;
        $priority2 = $request->priority2?json_encode(array_map('intval',$request->priority2)):null;
        $priority3 = $request->priority3?json_encode(array_map('intval',$request->priority3)):null;
        $priority4 = $request->priority4?json_encode(array_map('intval',$request->priority4)):null;
        $model = new UnitManagerSetting();
        $model->unit_id =$request->unit_id;
        $model->priority1 =$priority1;
        $model->priority2 =$priority2;
        $model->priority3 =$priority3;
        $model->priority4 =$priority4;
        $model->save();
        json_success();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('permission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $model = UnitManagerSetting::findOrFail($id);
        $unit = Unit::find($model->unit_id);
        return view('permission::UnitManager.edit',[
            'model'=>$model,
            'priority1'=>$this->getPriority($model->priority1),
            'priority2'=>$this->getPriority($model->priority2),
            'priority3'=>$this->getPriority($model->priority3),
            'priority4'=>$this->getPriority($model->priority4),
            'unit'=>$unit,
            'page_title'=>'Cập nhật setup trưởng đơn vị quản lý'
        ]);
    }
    private function getPriority($priority){
        if (!$priority)
            return [];
        $priority = json_decode($priority);
        return Titles::whereIn('id',$priority)->select('id','name')->get();
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CreateUnitMagagerRequest $request, $id)
    {
        $priority1 = $request->priority1?json_encode(array_map('intval',$request->priority1)):null;
        $priority2 = $request->priority2?json_encode(array_map('intval',$request->priority2)):null;
        $priority3 = $request->priority3?json_encode(array_map('intval',$request->priority3)):null;
        $priority4 = $request->priority4?json_encode(array_map('intval',$request->priority4)):null;
        $model = UnitManagerSetting::findOrFail($id);
        $model->unit_id =$request->unit_id;
        $model->priority1 =$priority1;
        $model->priority2 =$priority2;
        $model->priority3 =$priority3;
        $model->priority4 =$priority4;
        $model->save();
        json_success();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        UnitManagerSetting::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function import(ImportUnitManagerRequest $request){

        $file = $request->file('import_file');

        $import = new UnitManagerImport(\Auth::user());
        \Excel::import($import, $file);

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('backend.permission.unitmanager'),
        ]);
    }
}
