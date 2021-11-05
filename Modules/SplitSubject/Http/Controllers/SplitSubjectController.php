<?php

namespace Modules\SplitSubject\Http\Controllers;

use App\Models\Categories\Subject;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\MergeSubject\Entities\MergeSubject;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\SplitSubject\Http\Requests\SplitSubjectRequest;
use Modules\SplitSubject\Imports\SplitSubjectImport;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;

class SplitSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        // return view('splitsubject::backend.index');
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
        $query->select(['el_merge_subject.*', 'b.name AS name_subject_old']);
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'el_merge_subject.subject_new' );
        $query->where('el_merge_subject.type', '=', 2);
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
            $row->name_subject_new = $tmp_oldSubject;
            if ($row->status===null)
                $row->status = trans('backend.pending');
            elseif($row->status==1)
                $row->status = trans('backend.approved');
            elseif($row->status==0)
                $row->status = trans('backend.deny');
            $row->edit_url = route('module.splitsubject.edit',['id'=>$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function create()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $page_title = trans('backend.split_subject');
        $subjects = Subject::active()->get();
        return view('splitsubject::backend.create',
            [
                'page_title'=>$page_title,
                'subjects'=>$subjects,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'learning_manager',
            ]
        );
    }

    public function store(SplitSubjectRequest $request)
    {
        $subject_old_complete = $request->input('subject_old_complete');
        $subject_new = $request->input('subject_new');
        $subject_arr = [];
        $subject_old = $request->input('subject_old');
        $note= $request->input('note');
        foreach ($subject_old as $key=>$value){
            $subject_split[] = Subject::find($value)->code;
            $oldSubjectCodeArr[] = array($value,1);
            $subject_arr[] = $value;
        }
        $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->where('subject_id',$subject_new)
            ->where('pass','=',1)->select('user_id')->get();
        $numberMergeSubject = $user_in_subject->count();

        $model = new MergeSubject();
        $model->subject_old_complete = $subject_old_complete;
        $model->subject_old = json_encode((object) $oldSubjectCodeArr);
        $model->subject_new = $subject_new;
        $model->note = $note;
        $model->number_merge_subject = $numberMergeSubject;
        $model->type = 2;
        $model->pending=1;
        if ($model->save()) {
            // update user cần split
            foreach ($user_in_subject as $user){
                MergeSubjectUser::updateOrCreate(
                    ['user_id'=>$user->user_id,'merge_subject_id'=>$model->id,'type'=>2]
                );
            }
            // lưu log
            $subject_org = Subject::find($subject_new);
            $action='tách chuyên đề mã '.$subject_org->code.' thành mã  ('.join(',',$subject_split).')';
            TrainingProcessLogs::saveLogs($model->id,'split_subject',$action,2);
            ///
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect'=>route('module.splitsubject.edit',['id'=>$model->id])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function edit($id)
    {
        $model = MergeSubject::find($id);
        $page_title = trans('backend.split_subject');
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
        return view('splitsubject::backend.edit',
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

    public function update(SplitSubjectRequest $request, $id)
    {
        $subject_old_complete = $request->input('subject_old_complete');
        $subject_arr = [];
        $subject_old = $request->input('subject_old');
        $subject_new = $request->input('subject_new');
        $note= $request->input('note');
        foreach ($subject_old as $key=>$value){
            $subject_split[] = Subject::find($value)->code;
            $oldSubjectCodeArr[] = array($value,1);
            $subject_arr[] = $value;
        }
        $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->where('subject_id',$subject_new)
            ->where('pass','=',1)->select('user_id')->get();
        $numberMergeSubject = $user_in_subject->count();

        $model = MergeSubject::findOrFail($id);
        $model->subject_old_complete = $subject_old_complete;
        $model->subject_old = json_encode((object) $oldSubjectCodeArr);
        $model->subject_new = $subject_new;
        $model->note = $note;
        $model->number_merge_subject = $numberMergeSubject;
        $model->type = 2;
        $model->pending = 1;
        if ($model->save()) {
            // update user cần split
            foreach ($user_in_subject as $user){
                MergeSubjectUser::updateOrCreate(
                    ['user_id'=>$user,'merge_subject_id'=>$model->id,'type'=>2]
                );
            }
            // lưu log
            $subject_org = Subject::find($subject_new);
            $action='update tách chuyên đề mã '.$subject_org->code.' thành mã  ('.join(',',$subject_split).')';
            TrainingProcessLogs::saveLogs($model->id,'update_split_subject',$action,2);
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
            $subject_new = json_decode($subject->subject_old);
            $subject_new_code=[];
            foreach ($subject_new as $index => $item) {
                $subject_new_code[] = Subject::find($item[0])->code;
            }
            $action='Duyệt tách chuyên đề mã '.$subject->code.' thành mã  ('.join(',',$subject_new_code).')';
            TrainingProcessLogs::saveLogs($id,'approved_split_subject',$action,2);
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

        $import = new SplitSubjectImport(\Auth::user());
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
            'redirect' => route('module.splitsubject.index'),
        ]);
    }
    public function showLogs()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $title = trans('splitsubject::splitsubject.log_split_subject');
        return view('splitsubject::backend.logs',[
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
            ->where(['el_training_process_logs.type'=>2])
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
