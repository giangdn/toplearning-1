<?php

namespace Modules\SubjectComplete\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Notifications;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\MergeSubject\Entities\MergeSubject;
use Modules\SubjectComplete\Http\Requests\SubjectCompleteRequest;
use Modules\SubjectComplete\Imports\SubjectCompleteImport;
use Modules\SubjectComplete\Jobs\Import;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;
use Modules\User\Entities\WorkingProcess;

class SubjectCompleteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();
        // return view('subjectcomplete::backend.index',[
        //     'notifications' => $notifications
        // ]);
        return view('backend.learning_manager.index',[
            'notifications' => $notifications,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }
    public function getData(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = Profile::query();
        $query->select([
            'el_profile.id',
            'el_profile.user_id',
            'el_profile.code',
            'el_profile.email',
            'el_profile.firstname',
            'el_profile.lastname',
            'el_profile.status',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS area_name',
            'e.name AS unit_manager',
        ]);
        $query->from('el_profile');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->leftJoin('el_area AS d', 'd.code', '=', 'el_profile.area_code');
        $query->where('el_profile.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.id', $unit_id);
                $sub_query->orWhere('b.id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }

        if ($area){
            $area = Area::whereIn('id', explode(';', $area))->latest('id')->first();
            $area_id = Area::getArrayChild($area->code);

            $query->where(function ($sub_query) use ($area_id, $area) {
                $sub_query->orWhereIn('d.id', $area_id);
                $sub_query->orWhere('d.id', '=', $area->id);
            });
        }

        $count = $query->count();
        $query->orderBy('el_profile.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.subjectcomplete.edit', ['user_id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->area_url = route('module.backend.user.get_area', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function getTrainingProcessUser(Request $request,$user_id)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TrainingProcess::query();
        $query->where(['user_id'=>$user_id]);
        $count = $query->count();
        $query->orderBy( $sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->course_type = $row->course_type==1? trans('backend.online'): trans('backend.offline');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->result = $row->pass==1? trans('backend.completed'):trans('backend.incomplete');
            if ($row->process_type==2)
                $row->process_type = trans('backend.subject_complete');
            elseif ($row->process_type==4)
                $row->process_type = trans('backend.merge_subject');
            elseif ($row->process_type==5)
                $row->process_type = trans('backend.split_subject');
            else
                $row->process_type = '-';
            if($row->status==1)
                $row->status= '<label class="text-success">'.trans('backend.approved').'</label>';
            elseif($row->status===0)
                $row->status= '<label class="text-danger">'.trans('backend.deny').'</label>';
            else
                $row->status= '<label class="text-warning">'.trans('backend.pending').'</label>';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function store(SubjectCompleteRequest $request,$user_id)
    {
        $subject_id = $request->input('subject');
        $subject = Subject::findOrFail($subject_id);
        $profile = \DB::table('el_profile_view')->where('user_id',$user_id)->first();
        $titles_id = $request->input('titles');
        if (!$titles_id)
            $titles_id = $profile->title_id;
        $titles = Titles::find($titles_id);
        $note = $request->input('note');
        $model=TrainingProcess::updateOrCreate(
            [
                'user_id'=>$user_id,'subject_id'=>$subject->id
            ],
            [
                'subject_code'=>$subject->code,
                'subject_name'=>$subject->name,
                'titles_code'=>$titles->code,
                'titles_name'=>$titles->name,
                'unit_code'=>$profile->unit_code,
                'unit_name'=>$profile->unit_name,
                'start_date'=>date('Y-m-d H:i:s'),
                'end_date'=>date('Y-m-d H:i:s'),
                'pass'=>1,
                'process_type'=>2,
                'note'=>$note
            ]
        );
        $action='Thêm hoàn thành chuyên đề mã '.$subject->code.' cho học viên '.$profile->full_name.' ('.$profile->code.')';
        TrainingProcessLogs::saveLogs($model->id,'insert_subject_completion',$action,3);
        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect'=>route('module.subjectcomplete.edit',['user_id'=>$user_id])
        ]);
    }
    public function approve(Request $request)
    {
        $page_title = trans('backend.subject_complete');
        return view('subjectcomplete::backend.approve',
            [
                'page_title' =>$page_title,
            ]
        );
    }
    public function approved(Request $request)
    {
        $ids = $request->input('ids');
        $status = $request->input('status');

        foreach ($ids as $index => $id) {
            TrainingProcess::where(['id'=>$id])->update(['status'=>$status,'approved_by'=>\Auth::id(),'approved_date'=>date('Y-m-d H:i:s')]);
            $training_process = TrainingProcess::find($id);
            $text = $status==1?'Duyệt hoàn thành học phần':'Duyệt từ chối học phần';
            $profile = \DB::table('el_profile_view')->where(['user_id'=>$training_process->user_id])->first();
            $action = $text.' mã '.$training_process->subject_code.' học viên '.$profile->full_name.' ('.$profile->code.')';
            TrainingProcessLogs::saveLogs($id,'approve_subject_completion',$action,3);
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }
    public function getApprove(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TrainingProcess::query()->join('el_profile_view as a','a.user_id','=','el_training_process.user_id');
        $query->select('el_training_process.*','a.full_name','a.code');
        $query->where(['process_type'=>2]);
        $count = $query->count();
        $query->orderBy( $sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            if($row->status==1)
                $row->status= '<label class="text-success">'.trans('backend.approved').'</label>';
            elseif($row->status==0)
                $row->status= '<label class="text-danger">'.trans('backend.deny').'</label>';
            else
                $row->status= '<label class="text-warning">'.trans('backend.pending').'</label>';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function edit($user_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $page_title = trans('backend.subject_complete');
        $profile = \DB::table('el_profile_view')->where('user_id',$user_id)->first();
        return view('subjectcomplete::backend.edit',
            [
                'page_title'=>$page_title,
                'user_id'=>$user_id,
                'profile'=>$profile,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'learning_manager',
            ]);
    }
    public function showModal(Request $request, $user_id) {

        $subject = Subject::active()->get();
        $workingProcess = \DB::table('el_working_process as a')->leftJoin('el_titles as b','a.title_code','b.code')
        ->where(['a.user_id'=>$user_id])->select('a.id','b.name as title','b.code')->get();
        return view('subjectcomplete::backend.modal_add', [
            'subject' => $subject,
            'user_id'=>$user_id,
            'workingProcess'=>$workingProcess,
        ]);
    }
    public function showLogs()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $title = trans('backend.logs_subject_complete');
        return view('subjectcomplete::backend.logs',[
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
        $query->join('el_profile_view as profile','el_training_process_logs.created_by','profile.user_id')->where(['el_training_process_logs.type'=>3])
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
    public function import(Request $request)
    {

        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit_id = $request->unit_id;
        $file = $request->file('import_file');
        $name = 'import_subject_complete_' . \Str::random(10) . '.' . $file->extension();
//        $newfile = $file->move(storage_path('import_files'), $name);

        if($request->file('import_file')) {
//             $import = new SubjectCompleteImport(\Auth::user());
//             \Excel::import($import, $request->file('import_file'));

            (new SubjectCompleteImport(\Auth::user()))->queue($request->file('import_file'))->chain([
                new Import(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('module.subjectcomplete.index')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
        ]);
    }
}
