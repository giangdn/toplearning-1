<?php

namespace Modules\TrainingRoadmap\Http\Controllers;

use App\Models\Categories\TrainingProgram;
use App\Models\Categories\LevelSubject;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Http\Controllers\Controller;
use Modules\ReportNew\Entities\BC15;
use Modules\TrainingRoadmap\Exports\ExportSubjectByTitle;
use Modules\TrainingRoadmap\Exports\ExportRoadmap;
use Modules\TrainingRoadmap\Imports\TrainingRoadmapImport;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\UserCompletedSubject;
use function foo\func;

class TrainingRoadmapController extends Controller
{
    public function index($title_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');

        $model = Titles::find($title_id);
        $page_title = $model->name;
        return view('trainingroadmap::detail.index',[
            'title_id' => $title_id,
            'errors' => $errors,
            'page_title' => $page_title,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }
    public function getData($title_id, Request $request) {
        $sort = $request->input('sort', 'order');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $training_program = $request->input('training_program');
        $subject = $request->input('subject');

        TrainingRoadmap::addGlobalScope(new DraftScope());
        $query = TrainingRoadmap::query();
        $query->select([
            'el_trainingroadmap.*',
            'b.code AS subject_code',
            'b.name AS subject_name',
            'c.code AS training_program_code',
            'c.name AS training_program_name',
            'd.code AS level_subject_code',
            'd.name AS level_subject_name',
        ]);
        $query->from('el_trainingroadmap');
        $query->where('el_trainingroadmap.title_id', '=', $title_id);
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'el_trainingroadmap.subject_id');
        $query->leftJoin('el_training_program AS c', 'c.id', '=', 'el_trainingroadmap.training_program_id');
        $query->leftJoin('el_level_subject AS d', 'd.id', '=', 'el_trainingroadmap.level_subject_id');

        if ($training_program){
            $query->where('el_trainingroadmap.training_program_id', '=', $training_program);
        }
        if ($subject) {
            $query->where('el_trainingroadmap.subject_id', '=', $subject);
        }

        $count = $query->count();
        $query->orderBy('el_trainingroadmap.order', 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.trainingroadmap.detail.edit', ['id' => $title_id,'train_id'=>$row->id]);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function save($title_id,Request $request) {
        $this->validateRequest([
            'subject_id' => 'required|exists:el_subject,id',
        ], $request, TrainingRoadmap::getAttributeName());


        $subject_id = $request->input('subject_id');
        $level_subject_id = $request->input('level_subject_id');

        if(TrainingRoadmap::checkSubjectExits($subject_id, $title_id, $request->id)){
            json_message('Chuyên đề đã tồn tại', 'error');
        }
        $subject = Subject::find($subject_id);

        $model = TrainingRoadmap::firstOrNew(['id' => $request->id]);
        $model->title_id = $title_id;
        $model->fill($request->all());
        $model->training_form = is_array($request->training_form) ? json_encode($request->training_form) : '';
        if ($model->save()) {
            // save report bc15
            $this->saveToReportBC15($title_id,$subject_id);
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.trainingroadmap.detail',['id' => $title_id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    private function saveToReportBC15($title_id,$subject_id)
    {
        $prefix = \DB::getTablePrefix();
        $roadmap = \DB::table('el_trainingroadmap as a')->join('el_subject as b','a.subject_id','=','b.id')->where(['a.title_id'=>$title_id])->select('b.id','b.code','b.name',\DB::raw("'' as type"))->orderBy('order')->get();

//        $subquery = \DB::table('el_user_completed_subject')->selectRaw('distinct user_id, subject_id');
//        $profile = \DB::table('el_profile_view as a')->leftJoinSub($subquery,'b', function ($join) use ($subject_id){
//            $join->on('a.user_id', '=', 'b.user_id');
//            $join->where('b.subject_id', '=', $subject_id);
//        })
        $profile = \DB::table('el_profile_view as a')->select(
           \DB::raw($prefix."a.user_id,title_id,code,full_name,email,phone,area_name,unit_code,unit_name,null,null,null,null,position_name,
           title_name,join_company,status_id,status_name, 1 as mark,'".$roadmap->toJson()."'")
        )->where(['title_id'=>$title_id]);
        //delete truoc khi insert
        BC15::where(['title_id'=>$title_id])->delete();
        \DB::table('el_report_bc15')->insertUsing([
            'user_id','title_id','profile_code','full_name','email','phone','area','unit1_code','unit1_name','unit2_code','unit2_name','unit3_code','unit3_name','position','title','join_company','status_id','status','mark','subject'
        ],$profile);
    }
    public function form($title_id,$id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        if ($id) {
            $model = TrainingRoadmap::find($id);
            $subject = Subject::find($model->subject_id);
            $page_title = $subject->name;
            $title = Titles::find($title_id);
            $page_title_name = $title->name;
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $level_subject_id = LevelSubject::find($model->level_subject_id);
            !empty($model->training_form) ? $training_form = json_decode($model->training_form) : $training_form = [];
            return view('trainingroadmap::detail.form', [
                'model' => $model,
                'subject' => $subject,
                'page_title' => $page_title,
                'title_id' => $title_id ,
                'title' => $title,
                'page_title_name' => $page_title_name,
                'training_program' => $training_program,
                'level_subject_id' => $level_subject_id,
                'training_form' => $training_form,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'learning_manager',
            ]);
        }
        $model =  new TrainingRoadmap();
        $title = Titles::find($title_id);
        $page_title_name = $title->name;
        $page_title = trans('backend.add_new');

        return view('trainingroadmap::detail.form', [
            'model' => $model,
            'page_title' =>$page_title,
            'title_id' => $title_id ,
            'title' => $title,
            'page_title_name' => $page_title_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $title_id = $request->id;
        TrainingRoadmap::destroy($ids);
        $subjectJson = \DB::table('el_trainingroadmap as a')->join('el_subject as b','a.subject_id','=','b.id')->where(['a.title_id'=>$title_id])->select('b.code','b.name',\DB::raw("'' as type"))->orderBy('order')->get()->toJson();
        $this->removeReportBC15($title_id,$subjectJson);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    private function removeReportBC15($title_id,$subject){
        BC15::where(['title_id'=>$title_id])->update(['subject'=>$subject]);
    }
    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new TrainingRoadmapImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.trainingroadmap')
        ]);
    }

    public function export($title_id, Request $request){
        $subject_name = $request->subject_name;

        return (new ExportSubjectByTitle($title_id, $subject_name))->download('danh_sach_chi_tiet_hoc_phan_chuong_trinh_khung_'. date('d_m_Y') .'.xlsx');
    }

    public function exportRoadmap() {
        return (new ExportRoadmap())->download('danh_sach_hoc_phan_chuong_trinh_khung_'. date('d_m_Y') .'.xlsx');
    }

    public function checkTrainingRoadmap(Request $request){
        $title_old = $request->title_old;
        $title_new = $request->title_new;

        if ($title_old == $title_new){
            return json_result([
                'status' => 'error',
                'message' => 'Chức danh nguồn phải khác chức danh đích',
            ]);
        }

        $trainingroadmap = TrainingRoadmap::query()->where('title_id', '=', $title_new);
        if ($trainingroadmap->exists()){
            return json_result([
                'status' => 'warning',
                'message' => 'Chức danh đã có học phần. Bạn vẫn muốn sao chép?',
            ]);
        }

        return json_result([
            'status' => 'success',
            'message' => 'Bắt đầu sao chép?',
        ]);
    }

    public function copy(Request $request){
        $title_old = $request->title_old;
        $title_new = $request->title_new;

        $trainingroadmap_old = TrainingRoadmap::query()->where('title_id', '=', $title_old)->get();
        foreach ($trainingroadmap_old as $item){
            $check = TrainingRoadmap::query()->where('title_id', '=', $title_new)
                ->where('subject_id', '=', $item->subject_id);
            if ($check->exists()){
                $check->update([
                    'completion_time' => $item->completion_time,
                    'order' => $item->order,
                    'content' => $item->content,
                    'training_form' => $item->training_form,
                    'updated_by' => Auth::id()
                ]);
            }else{
                $newTrainingRoadmap = $item->replicate();
                $newTrainingRoadmap->title_id = $title_new;
                $newTrainingRoadmap->created_by = Auth::id();
                $newTrainingRoadmap->updated_by = Auth::id();
                $newTrainingRoadmap->save();
            }
        }

        return json_result([
            'status' => 'success',
            'message' => 'Sao chép thành công',
        ]);
    }

    public function saveOrder(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id => $value) {
            TrainingRoadmap::where(['id'=>$id])->update(['order'=>$value]);
        }
        return json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
        ]);
    }
}
