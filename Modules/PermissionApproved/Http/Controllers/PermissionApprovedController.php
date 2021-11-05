<?php

namespace Modules\PermissionApproved\Http\Controllers;

use App\Console\Commands\Title;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PermissionApproved\Entities\ModelApproved;
use Modules\PermissionApproved\Entities\PermissionApproved;
use Modules\PermissionApproved\Entities\PermissionApprovedObject;
use Modules\PermissionApproved\Entities\PermissionApprovedTitle;
use Modules\PermissionApproved\Entities\PermissionApprovedUser;
use Modules\PermissionApproved\Http\Requests\PermissionApprovedRequest;

class PermissionApprovedController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $unit_id = $request->query('unit_id');
        $model_approved = $request->query('model_approved');
        if ($request->ajax()) {
            $sort = $request->input('sort', 'level');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $query = PermissionApproved::query();
            if ($unit_id){
                $query->where('unit_id',$unit_id);
            }
            if ($model_approved){
                $query->where('model_approved',$model_approved);
            }
            $count = $query->count();
            $query->orderBy($sort, $order);
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            foreach ($rows as $index => $row) {
                $permissionApprovedId = $row->id;
                $row->title_name=$this->getGroupConcatTitle($permissionApprovedId);
                $row->full_name=$this->getGroupConcatUser($permissionApprovedId);
                $row->object_name = $this->getObjectName($permissionApprovedId);
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }
        $modelApproved = ModelApproved::active()->get();
        $unit = Unit::findOrFail($unit_id);
        return view('permissionapproved::index',[
            'modelApproved'=>$modelApproved,
            'unit'=>$unit,
        ]);
    }
    private function getObjectName($permissionApprovedId){
        $object_name = \DB::query()->from('el_permission_approved_object as a')
            ->join('el_approved_object_level as b','a.object_id','b.id')->where('a.permission_approved_id',$permissionApprovedId)
            ->select('b.name')->value('b.name');
        return $object_name;
    }
    private function getGroupConcatTitle($permissionApprovedId){
        $title = \DB::query()->from('el_titles as a')->select('name')
            ->whereExists(function ($subquery) use($permissionApprovedId){
                $subquery->select('b.title_id')
                    ->from('el_permission_approved_title as b')
                    ->where('b.permission_approved_id', $permissionApprovedId)
                    ->whereColumn('b.title_id','=','a.id');
            })->select('name')->pluck('name')->implode(', ');
        return $title;
    }
    private function getGroupConcatUser($permissionApprovedId){
        $full_name = \DB::query()->from('el_profile_view as a')->select('full_name')
            ->whereExists(function ($subquery) use($permissionApprovedId){
                $subquery->select('b.user_id')
                    ->from('el_permission_approved_user as b')
                    ->where('b.permission_approved_id', $permissionApprovedId)
                    ->whereColumn('b.user_id','=','a.id');
            })->select('full_name')->pluck('full_name')->implode(', ');
        return $full_name;
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $unit_id = $request->unit_id;
        $model_approved = $request->model_approved;
        $objects = \DB::table('el_approved_object_level as a')
            ->select('a.id','a.name')->get();
        $level = (int) PermissionApproved::where(['unit_id'=>$unit_id,'model_approved'=>$model_approved])->max('level');
        return view('permissionapproved::modal.create',
            [
                'objects' => $objects,
                'level' => $level+1,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PermissionApprovedRequest $request)
    {

        $unit_id = $request->unit_id;
        $model_approved = $request->model_approved;
        $model = new PermissionApproved();
        $model->fill($request->all());
        $level = (int) PermissionApproved::where(['unit_id'=>$unit_id,'model_approved'=>$model_approved])->max('level');
        $model->level = $level+1;
        $model->has_change = 1;
        $model->save();
        $this->updateHasChange($unit_id,$model_approved);
        if ($request->object_id){
            $permissionObject = new PermissionApprovedObject();
            $permissionObject->fill($request->all());
            $permissionObject->level = $level+1;
            $permissionObject->object_id = $request->object_id;
            $permissionObject->permission_approved_id = $model->id;
            $permissionObject->save();
        }
        if ( $request->employees){
            foreach ($request->employees as $index => $employee) {
                $permissionUser = new PermissionApprovedUser();
                $permissionUser->fill($request->all());
                $permissionUser->level = $level+1;
                $permissionUser->user_id = $employee;
                $permissionUser->permission_approved_id = $model->id;
                $permissionUser->save();
            }
        }
        if ($request->titles){
            foreach ($request->titles as $index => $title) {
                $permissionTitle = new PermissionApprovedTitle();
                $permissionTitle->fill($request->all());
                $permissionTitle->level = $level+1;
                $permissionTitle->title_id = $title;
                $permissionTitle->permission_approved_id = $model->id;
                $permissionTitle->save();
            }
        }
        json_message('Cập nhật thành công');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $permissionApproved = PermissionApproved::findOrFail($id);
        $titles = Titles::join('el_permission_approved_title as b','el_titles.id','=','b.title_id')->where('b.permission_approved_id','=',$id)->get(['el_titles.id','el_titles.name']);
        $users = \DB::table('el_profile_view as a')->join('el_permission_approved_user as b','b.user_id','=','a.id')->where('b.permission_approved_id','=',$id)->get(['a.id','a.full_name']);
        $objects = \DB::table('el_approved_object_level as a')
            ->leftjoin('el_permission_approved_object as b', function (Builder $join) use ($id){
                $join->on('b.object_id','=','a.id');
                $join->where('b.permission_approved_id','=',$id);
            })
            ->select('a.id','a.name','b.object_id')->get();
        return view('permissionapproved::modal.edit',
            [
                'titles' => $titles,
                'users' => $users,
                'objects' => $objects,
                'id'   =>$id,
                'level'   =>(int)$permissionApproved->level
            ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(PermissionApprovedRequest $request, $id)
    {
        $permissionApproved =  PermissionApproved::where(['id'=>$id])->first();
        $permissionApproved->has_change=1;
        $permissionApproved->save();
        $this->updateHasChange($permissionApproved->unit_id,$permissionApproved->model_approved);
        $level = $permissionApproved->level;
        $permission_approved_id = $permissionApproved->id;
        PermissionApprovedObject::where('permission_approved_id',$id)->delete();
        if ($request->object_id){
            $permissionObject = new PermissionApprovedObject();
            $permissionObject->fill($request->all());
            $permissionObject->level = $level+1;
            $permissionObject->object_id = $request->object_id;
            $permissionObject->permission_approved_id = $permission_approved_id;
            $permissionObject->save();
        }
        PermissionApprovedUser::where('permission_approved_id',$id)->delete();
        if ( $request->employees){
            foreach ($request->employees as $index => $employee) {
                $permissionUser = new PermissionApprovedUser();
                $permissionUser->fill($request->all());
                $permissionUser->level = $level+1;
                $permissionUser->user_id = $employee;
                $permissionUser->permission_approved_id = $permission_approved_id;
                $permissionUser->save();
            }
        }
        PermissionApprovedTitle::where('permission_approved_id',$id)->delete();
        if ($request->titles){
            foreach ($request->titles as $index => $title) {
                $permissionTitle = new PermissionApprovedTitle();
                $permissionTitle->fill($request->all());
                $permissionTitle->level = $level+1;
                $permissionTitle->title_id = $title;
                $permissionTitle->permission_approved_id = $permission_approved_id;
                $permissionTitle->save();
            }
        }
        return json_success();
    }
    private function updateHasChange($unit_id,$model_approved){
        PermissionApproved::where(['model_approved'=>$model_approved,'unit_id'=>$unit_id])->update(['has_change'=>1]);
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $unit_id = $request->unit_id;
        $model_approved = $request->model_approved;
        $permissionApproved = PermissionApproved::where(['unit_id'=>$unit_id,'model_approved'=>$model_approved])->select('id','level')->orderByDesc('level')->limit(1)->first();
        PermissionApprovedObject::where(['permission_approved_id'=>$permissionApproved->id])->delete();
        PermissionApprovedTitle::where(['permission_approved_id'=>$permissionApproved->id])->delete();
        PermissionApprovedUser::where(['permission_approved_id'=>$permissionApproved->id])->delete();
        PermissionApproved::where(['id'=>$permissionApproved->id])->delete();
        $this->updateHasChange($unit_id,$model_approved);
        json_success();
    }
}
