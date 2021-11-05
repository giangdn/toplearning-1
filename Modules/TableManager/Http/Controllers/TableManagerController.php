<?php

namespace Modules\TableManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\TableManager\Entities\Table;

class TableManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        if ($request->ajax()){
            $search = $request->input('search');
            $sort = $request->input('sort','id');
            $order = $request->input('order','desc');
            $offset = $request->input('offset',0);
            $limit = $request->input('limit',20);
            $query = Table::query();
            $query->select('*')->get();
            $count = $query ->count();
            $query -> orderBy( $sort,$order);
            $query ->offset($offset);
            $query ->limit($limit);
            $rows = $query ->get();
            json_result(['total' => $count, 'rows' => $rows]);
        }
        return view('tablemanager::backend.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('tablemanager::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $model = new Table();
        $model->fill($request->all());
        $model->save();
        return json_success();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('tablemanager::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('tablemanager::modal.edit',
            [
                'table' => $table,
            ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $model = Table::firstOrNew(['id'=>$id]);
        $model->fill($request->all());
        $model->save();
        return json_success();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        Table::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
