<?php

namespace Modules\ModelHistory\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ModelHistory\Entities\ModelHistory;
use Modules\TableManager\Entities\Table;

class ModelHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $fromDate = $request->input('from_date  ');
            $toDate = $request->input('to_date');
            $sort = $request->input('sort','id');
            $order = $request->input('order','desc');
            $offset = $request->input('offset',0);
            $limit = $request->input('limit',20);
            $user = $request->input('user',0);
            $model_id = $request->input('model','');

            ModelHistory::addGlobalScope(new DraftScope());
            $query = ModelHistory::query();

            $query->select('*')->get();
            if($model_id){
                $model = Table::find($model_id)->code;
                $query->where(function ( $subquery) use ($model){
                    $subquery->orWhere('model','=',$model);
                    $subquery->orWhere('parent_model','=',$model);
                });
            }
            if ($fromDate && $toDate){
                $query->where(function(Builder $sub_query) use ($fromDate, $toDate){
                    $sub_query->orWhere('created_at','>=',$fromDate);
                    $sub_query->orWhere('created_at','<=', $toDate);
                });
            }
            if ($user){
                $query->where('created_by',$user);
            }
//            dd($model);
            $count = $query ->count();
            $query -> orderBy( $sort,$order);
            $query ->offset($offset);
            $query ->limit($limit);
            $rows = $query ->get();
            foreach ($rows as $row) {
                $row->created_date = get_datetime($row->created_at);
            }

            json_result(['total' => $count, 'rows' => $rows]);
        }
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        // return view('modelhistory::backend.index');
        return view('backend.history.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => 'history',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('modelhistory::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('modelhistory::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('modelhistory::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
