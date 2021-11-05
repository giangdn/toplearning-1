<?php

namespace Modules\MergeSubject\Http\Controllers;

use App\Models\Categories\Subject;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Modules\MergeSubject\Entities\MergeSubject;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\MergeSubject\Http\Requests\MergeSubjectRequest;
use Modules\MergeSubject\Imports\MergeSubjectImport;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;

class MergeSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        // return view('mergesubject::backend.index');
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
        MergeSubject::addGlobalScope(new DraftScope());
        $query = MergeSubject::query();
        $query->select(['el_merge_subject.*', 'b.name AS name_subject_new']);
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'el_merge_subject.subject_new' );
        $query->where('el_merge_subject.type', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('b.name','like','%' . $search . '%');
                $sub_query->orWhere('b.code','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query -> orderBy('el_merge_subject.'.$sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $subject_old_arr = json_decode($row->subject_old);
            $tmp_oldSubject = '';
            foreach ($subject_old_arr as $key => $subject_old) {
                $oldSubject = Subject::find($subject_old[0]);
                $tmp_oldSubject .= $oldSubject->name.' <strong>('.$oldSubject->code.')</strong>.<br>';
            }

            $row->old_subject = $tmp_oldSubject;
            if ($row->status===null)
                $row->status = trans('backend.pending');
            elseif($row->status==1)
                $row->status = trans('backend.approved');
            elseif($row->status==0)
                $row->status = trans('backend.deny');

            $row->edit_url = route('module.mergesubject.edit',['id'=>$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function create()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $page_title = trans('backend.merge_subject');
        $subjects = Subject::active()->get();
        return view('mergesubject::backend.create',
        [
            'page_title'=>$page_title,
            'subjects'=>$subjects,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]
        );
    }

    public function store(MergeSubjectRequest $request)
    {
        $mergeOption = $request->input('mergeOption');
        $subject_old_complete = $request->input('subject_old_complete');
        $subject_arr = [];
        if ($mergeOption==1){
            $subject_old = $request->input('subject_old');
            $subject_new = $request->input('subject_new');
            $note= $request->input('note');
            foreach ($subject_old as $key=>$value){
                $subject_org[] = Subject::find($value)->code;
                $oldSubjectCodeArr[] = array($value,1);
                $subject_arr[] = $value;
            }
            $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->whereIn('subject_id',$subject_arr)
                ->groupBy('user_id')->havingRaw("count(1)>=".count($subject_arr)." and count(pass)>={$subject_old_complete}")->distinct('user_id')->select('user_id')->get();
            $numberMergeSubject = $user_in_subject->count();
        }
        else{
            $subject_old_2 = $request->input('subject_old_2');
            $subject_old_complete_2 = $request->input('subject_old_complete_2');
            $subject_old_complete_hidden = $request->input('subject_old_complete_hidden');
            $subject_new = $request->input('subject_new_2');
            $note= $request->input('note_2');
            foreach ($subject_old_2 as $key => $item){
                $subject_org[] = Subject::find($item)->code;
                $oldSubjectCodeArr[] = array($item,$subject_old_complete_hidden[$key]);
                $subject_arr[] = $item;
                if ($subject_old_complete_hidden[$key]==1){
                    $where = ['subject_id'=>$item,'pass'=>1];
                    $flag = true;
                }
            }
            $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->whereIn('subject_id',$subject_arr)
                ->groupBy('user_id')->havingRaw('count(1)>='.count($subject_arr))->select('user_id')->get();
            $n=0;$user_arr=[];
            if ($flag==true){
                foreach ($user_in_subject as $index => $user) {
                    $exists =TrainingProcess::where(['user_id'=>$user->user_id])->where($where)->count();
                    $n+=$exists;
                    if ($exists)
                        $user_arr[]['user_id'] = $user->user_id;
                }
                $numberMergeSubject=$n;
            }else
                $numberMergeSubject=$user_in_subject->count();
            $user_in_subject = count($user_arr)>0? json_decode(json_encode($user_arr)):[];
        }
        $model = new MergeSubject();
        $model->subject_old_complete = $subject_old_complete;
        $model->subject_old = json_encode((object) $oldSubjectCodeArr);
        $model->subject_new = $subject_new;
        $model->note = $note;
        $model->merge_option = $mergeOption;
        $model->number_merge_subject = $numberMergeSubject;
        $model->type = 1;
        $model->pending = 1;
        if ($model->save()) {

            // update user cần merge
            foreach ($user_in_subject as $user){
                MergeSubjectUser::updateOrCreate(
                    ['user_id'=>$user->user_id,'merge_subject_id'=>$model->id,'type'=>1]
                );
            }

            // lưu log
            $subject_new = Subject::find($subject_new);
            $action='gộp chuyên đề mã ('.join(',',$subject_org).') vào mã '.$subject_new->code;
            TrainingProcessLogs::saveLogs($model->id,'merge_subject',$action,1);
            ///
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect'=>route('module.mergesubject.edit',['id'=>$model->id])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function edit($id)
    {
        $model = MergeSubject::find($id);
        $page_title = trans('backend.merge_subject');
        $subjects = Subject::active()->get();
        $subject_old_arr = json_decode($model->subject_old,true);
        $subject_list = function ($id) {
            return Subject::find($id);
        };
        $subject_old_rr = [];
        foreach ($subject_old_arr as $index => $item) {
            $subject_old_rr[] = $item[0];
        }

        $subject_new = Subject::find($model->subject_new);
        return view('mergesubject::backend.edit',
            [
                'id'=>$id,
                'page_title'=>$page_title,
                'subjects'=>$subjects,
                'model'=>$model,
                'subject_list'=>$subject_list,
                'subject_old_arr'=>$subject_old_arr,
                'subject_new'=>$subject_new,
                'subject_old_rr'=>$subject_old_rr,
            ]
        );
    }

    public function update(MergeSubjectRequest $request, $id)
    {
        $mergeOption = $request->input('mergeOption');
        $subject_old_complete = $request->input('subject_old_complete');
        $subject_arr = [];
        if ($mergeOption==1){
            $subject_old = $request->input('subject_old');
            $subject_new = $request->input('subject_new');
            $note= $request->input('note');
            foreach ($subject_old as $key=>$value){
                $subject_org[] = Subject::find($value)->code;
                $oldSubjectCodeArr[] = array($value,1);
                $subject_arr[] = $value;
            }
            $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->whereIn('subject_id',$subject_arr)
                ->groupBy('user_id')->havingRaw("count(1)>=".count($subject_arr)." and count(pass)>={$subject_old_complete}")->distinct('user_id')->select('user_id')->get();
            $numberMergeSubject = $user_in_subject->count();
        }else{
            $subject_old_2 = $request->input('subject_old_2');
            $subject_old_complete_2 = $request->input('subject_old_complete_2');
            $subject_old_complete_hidden = $request->input('subject_old_complete_hidden');
            $subject_new = $request->input('subject_new_2');
            $note= $request->input('note_2');
            foreach ($subject_old_2 as $key => $item){
                $subject_org[] = Subject::find($item)->code;
                $oldSubjectCodeArr[] = array($item,$subject_old_complete_hidden[$key]);
                $subject_arr[] = $item;
                if ($subject_old_complete_hidden[$key]==1){
                    $where = ['subject_id'=>$item,'pass'=>1];
                    $flag = true;
                }
            }
            $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->whereIn('subject_id',$subject_arr)
                ->groupBy('user_id')->havingRaw('count(1)>='.count($subject_arr))->select('user_id')->get();
            $n=0;$user_arr=[];
            if ($flag==true){
                foreach ($user_in_subject as $index => $user) {
                    $exists =TrainingProcess::where(['user_id'=>$user->user_id])->where($where)->count();
                    $n+=$exists;
                    if ($exists)
                        $user_arr[]['user_id'] = $user->user_id;
                }
                $numberMergeSubject=$n;
            }else
                $numberMergeSubject=$user_in_subject->count();
            $user_in_subject = count($user_arr)>0? json_decode(json_encode($user_arr)):[];
        }
        $model = MergeSubject::findOrFail($id);
        $model->subject_old_complete = $subject_old_complete;
        $model->subject_old = json_encode((object) $oldSubjectCodeArr);
        $model->subject_new = $subject_new;
        $model->note = $note;
        $model->merge_option = $mergeOption;
        $model->number_merge_subject = $numberMergeSubject;
        $model->type = 1;
        $model->pending = 1;
        if ($model->save()) {
            foreach ($user_in_subject as $user){
                MergeSubjectUser::updateOrCreate(
                    ['user_id'=>$user->user_id,'merge_subject_id'=>$model->id,'type'=>1]
                );
            }
            // lưu log
            $subject_new = Subject::find($subject_new);
            $action='update gộp chuyên đề mã ('.join(',',$subject_org).') vào mã '.$subject_new->code;
            TrainingProcessLogs::saveLogs($model->id,'update_merge_subject',$action,1);
            ///
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $index => $id) {
            $exists =MergeSubject::where(['id'=>$id,'status'=>1])->exists();
            if ($exists)
                unset($ids[$index]);
        }
        MergeSubject::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function approve(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = MergeSubject::query();
            $query->where('id', $id);
            $query->update(['status' => $status,'approved_by'=>\Auth::id(),'approved_date'=>time()]);
            // lưu log
            $subject = \DB::query()->from('el_merge_subject as a')->join('el_subject as b','a.subject_new','=','b.id')->where(['a.id'=>$id])->select('b.code','a.subject_old')->first();
            $subject_org = json_decode($subject->subject_old);
            $subject_org_code=[];
            foreach ($subject_org as $index => $item) {
                $subject_org_code[] = Subject::find($item[0])->code;
            }
            $action='Duyệt gộp chuyên đề mã ('.join(',',$subject_org_code).') vào mã '.$subject->code;
            TrainingProcessLogs::saveLogs($id,'approved_merge_subject',$action,1);
            ///
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function import(Request $request)
    {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $file = $request->file('import_file');

        $import = new MergeSubjectImport(\Auth::user());
        \Excel::import($import, $file);

//            (new MergeSubjectImport(\Auth::user()))->queue($file)->chain([
//                new Import(\Auth::user()),
//            ]);
        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();

        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.mergesubject.index'),
        ]);
    }
    public function showLogs()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $title = trans('backend.logs_subject_complete');
        return view('mergesubject::backend.logs',[
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
        $query->join('el_profile_view as profile','el_training_process_logs.created_by','profile.user_id')
            ->where(['el_training_process_logs.type'=>1])
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
