<?php

namespace Modules\MoveTrainingProcess\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\MoveTrainingProcess\Entities\MoveTrainingProcess;
use Modules\MoveTrainingProcess\Http\Requests\MoveTrainingRequest;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;

class MoveTrainingProcessController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        // return view('movetrainingprocess::backend.index');
        return view('backend.learning_manager.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);
        $prefix = \DB::getTablePrefix();
//        MoveTrainingProcess::addGlobalScope(new DraftScope());
        $query = MoveTrainingProcess::query();
        $query->select('el_move_training_process.id','el_move_training_process.status','el_move_training_process.created_at',
            'el_move_training_process.approved_date',
            \DB::raw("CONCAT({$prefix}a.lastname, ' ',{$prefix}a.firstname) as employee_old"),
            \DB::raw("CONCAT({$prefix}b.lastname, ' ',{$prefix}b.firstname) as employee_new"),
            \DB::raw("CONCAT({$prefix}c.lastname, ' ',{$prefix}c.firstname) as created_by"),
            \DB::raw("CONCAT({$prefix}d.lastname, ' ',{$prefix}d.firstname) as approved_by")
        );
        $query->leftJoin('el_profile as a', 'el_move_training_process.employee_old', '=', 'a.user_id' );
        $query->leftJoin('el_profile as b', 'el_move_training_process.employee_new', '=', 'b.user_id' );
        $query->leftJoin('el_profile as c', 'el_move_training_process.created_by', '=', 'c.user_id' );
        $query->leftJoin('el_profile as d', 'el_move_training_process.approved_by', '=', 'd.user_id' );
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('b.name','like','%' . $search . '%');
                $sub_query->orWhere('b.code','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query -> orderBy( $sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->approved_at = get_date($row->approved_date);
            $row->created_date = get_date($row->created_at);
            if ($row->status===null)
                $row->status = trans('backend.pending');
            elseif($row->status==1)
                $row->status = trans('backend.approved');
            elseif($row->status==0)
                $row->status = trans('backend.deny');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $index => $id) {
            $exists =MoveTrainingProcess::where(['id'=>$id,'status'=>1])->exists();
            if ($exists)
                unset($ids[$index]);
        }
        MoveTrainingProcess::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function showModalMoveTrainingProcess(Request $request)
    {
        $employee_code_old = $request->input('employee_code_old');
        $employee_code_new = $request->input('employee_code_new');
        $profile_old = \DB::table('el_profile_view')->where(['code'=>$employee_code_old])->first();
        $profile_new = \DB::table('el_profile_view')->where(['code'=>$employee_code_new])->first();
        $exists = TrainingProcess::where(['pass'=>1,'user_id'=>$profile_old->user_id])->exists();

        return view('movetrainingprocess::backend.modal_move', [
            'profile_old' => $profile_old,
            'profile_new'=>$profile_new,
            'exists'=>$exists,
            'user_id'=>$profile_old->user_id
        ]);
    }

    public function getTrainingProcessOld(Request $request, $user_id)
    {
        $search = $request->input('search');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);
        $query = TrainingProcess::query();
        $query->where(['pass'=>1,'user_id'=>$user_id]);
        $count = $query ->count();
        $query -> orderBy( $sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            if ($row->process_type == 2)
                $row->process_type = trans('backend.subject_complete');
            elseif ($row->process_type == 4)
                $row->process_type = trans('backend.merge_subject');
            elseif ($row->process_type == 5)
                $row->process_type = trans('backend.split_subject');
            else
                $row->process_type = '-';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function submitMoveTrainingProcess(MoveTrainingRequest $request)
    {
        $user_old = $request->input('user_old');
        $user_new = $request->input('user_new');
        $exists = MoveTrainingProcess::where(['employee_old'=>$user_old])->exists();
        if ($exists)
            json_message('Nhân viên cần chuyển đã tồn tại','error');
        $trainingProcess = TrainingProcess::where(['pass'=>1,'user_id'=>$user_old])->get('id');
        $id=[];
        foreach ($trainingProcess as $index => $item) {
            $id[] = $item->id;
        }
        if ($trainingProcess->isNotEmpty()) {
            $model = new MoveTrainingProcess();
            $model->employee_old = $user_old;
            $model->employee_new = $user_new;
            $model->move_process_id = json_encode((object)$id);
            if ($model->save()) {
                foreach ($trainingProcess as $index => $item) {
                    TrainingProcess::where(['id' => $item->id])->update(['move_id' => $model->id]);
                }
                //save logs
                $profile = \DB::table('el_move_training_process as a')->join('el_profile as b','a.employee_old','=','b.user_id')
                    ->join('el_profile as c','a.employee_new','=','c.user_id')->select('b.code as code_old','c.code as code_new')
                    ->where('a.id',$model->id)->first();
                $action = 'Thực hiện chuyển quá trình đào tạo từ mã nhân viên'.$profile->code_old.' sang mã nhân viên '.$profile->code_new;
                TrainingProcessLogs::saveLogs($model->id,'move_training_process',$action,4);
            }
            json_result(['status' => 'success', 'message' => 'Cập nhật thành công', 'redirect' => route('module.movetrainingprocess.index')]);
        }else
            json_message('Nhân viên này không có quá trình đào tạo để chuyển','error');
    }

    public function approved(Request $request)
    {
        $ids = $request->input('ids');
        $status = $request->input('status');

        foreach ($ids as $index => $id) {
            $moveTrainingProcess = MoveTrainingProcess::where(['id'=>$id])->whereRaw('COALESCE(status,0)<>1')->first();
            MoveTrainingProcess::where('id',$id)->update(['status'=>$status,'approved_by'=>\Auth::id(),'approved_date'=>date('Y-m-d H:i:s')]);
            //update training process
            if ($status==1 && $moveTrainingProcess)
                TrainingProcess::where(['move_id'=>$id])->update(
                    [
                        'user_id'=>$moveTrainingProcess->employee_new,
                        'status'=>$status,
                        'approved_by'=>\Auth::id(),
                        'approved_date'=>date('Y-m-d H:i:s'),
                        'process_type'=>3,
                        'pass'=>1,
                        'note'=>'Chuyển QTĐT',
                        'start_date'=>date('Y-m-d H:i:s'),
                        'end_date'=>date('Y-m-d H:i:s'),
                        'move_id'=>$id
                    ]
                );

            $text = $status==1?'Duyệt chuyển quá trình đào tạo':'Từ chối chuyển quá trình đào tạo';
            $profile = \DB::table('el_move_training_process as a')->join('el_profile as b','a.employee_old','=','b.user_id')
                ->join('el_profile as c','a.employee_new','=','c.user_id')->select('b.code as code_old','c.code as code_new')
                ->where('a.id',$id)->first();
            $action = $text.' mã nhân viên'.$profile->code_old.' sang mã nhân viên '.$profile->code_new;
            TrainingProcessLogs::saveLogs($id,'approve_move_training_process',$action,4);
        }
        if($status == 1) {
            json_message('Duyệt thành công','success');
        } else {
            json_message('Đã từ chối','success');
        }
        
    }
    public function showLogs()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $title = trans('backend.logs_subject_complete');
        return view('movetrainingprocess::backend.logs',[
            'title'=>$title,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }
    public function getLogs(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = TrainingProcessLogs::query();
        $query->join('el_profile_view as profile','el_training_process_logs.created_by','profile.user_id')->where(['el_training_process_logs.type'=>4])
            ->select('el_training_process_logs.*','profile.full_name','profile.code');
        $count = $query->count();
        $query->orderBy( $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_date = get_date($row->created_at);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
