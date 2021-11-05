<?php

namespace Modules\PlanSuggest\Http\Controllers;

use App\Models\Categories\TrainingForm;
use App\Permission;
use App\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Scopes\DraftScope;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Modules\PlanSuggest\Entities\PlanSuggest;
use App\Http\Controllers\Controller;
use Modules\PlanSuggest\Exports\PlanSuggestExport;
use Symfony\Component\HttpKernel\DataCollector\DumpDataCollector;
use Illuminate\Support\Str;
class PlanSuggestController extends Controller
{

    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $user = Profile::where('user_id', \Auth::id())->first();
        $title = Titles::where('code','=',$user->title_code)->first();
        $unit = Unit::all()->where('status','=',1);
        return view('plansuggest::backend.index',[
            'user' => $user,
            'title' => $title,
            'unit'=>$unit,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataPlanSuggest(Request $request) {
        $month = $request->input('month');
        $year = $request->input('year');
        $unit = $request->input('unit');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $status = $request->input('status', 0);
        PlanSuggest::addGlobalScope(new DraftScope());
        $query = PlanSuggest::query()
            ->select([
                'el_plan_suggest.*',
                'b.name as unit_name',
                'c.name as training_form'
            ])
            ->leftJoin('el_unit as b','el_plan_suggest.unit_code','=','b.code')
            ->leftJoin('el_training_form as c', 'c.id', '=', 'el_plan_suggest.training_form');

        if ($month)
            $query->where(\DB::raw('month(start_date)'),'=', $month);
        if ($year)
            $query->where(\DB::raw('year(start_date)'),'=', $year);
        if ($unit)
            $query->where('el_plan_suggest.unit_code','=', $unit);
        if ($status)
            $query->where('el_plan_suggest.status','=', $status);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->time = get_date($row->start_date, 'd/m/Y') . ' => ' . get_date($row->end_date, 'd/m/Y');
            $row->edit_url = route('module.plan_suggest.form.edit',['id'=>$row->id]);
            $row->download_file = $row->attach ? link_download('uploads/'.$row->attach) : '';
            $row->download_report = $row->attach_report ? link_download('uploads/'.$row->attach_report) : '';
            $row->type = ($row->type == 1 ? 'Online' : ($row->type == 2 ? 'Offline' : 'Tập trung'));
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function Form(Request $request, $id=0)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $units =  UnitManager::getArrayUnitManagedByUser();
        $subject = Subject::where('status','=',1)->get();
        $title = Titles::where('status','=',1)->get();

        $students = \DB::query()->from('el_profile_view');
        if (!Permission::isAdmin()){
            $students = $students->leftJoin('el_unit as b', 'b.code', '=', 'unit_code');
            $students = $students->whereIn('b.id', $units);
        }
        $students = $students->where('user_id', '>', 2)->get();
        $approved = 0;
        
        if ($id>0){
            $page_title = "Cập nhật";
            $model = PlanSuggest::find($id);
            $model->title = array_values(json_decode($model->title,true));
            $model->students = $model->students ? array_values(json_decode($model->students,true)) : [];
            $model->intend = get_date($model->intend,'m/Y');

            $training_form = TrainingForm::find($model->training_form);
            if (in_array($model->status,[1,2,3]) && Permission::isAdmin()) {
                $approved = 1;
            }
        }else{
            $page_title = trans('backend.create_new_plan');
            $model = new PlanSuggest();
            $model->title = [];
            $model->students = [];
        }

        return view('plansuggest::backend.form',[
            'subject' => $subject,
            'title' => $title,
            'page_title' => $page_title,
            'students' => $students,
            'model' => $model,
            'approved' => $approved,
            'training_form' => ($id > 0) ? $training_form : '',
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function loadUserByTitle(Request $request){
        $title = $request->title_ids;

        $units =  UnitManager::getArrayUnitManagedByUser();

//        $user = Profile::where('user_id','=', Auth::id())->first();
//        $unit_code = UnitManager::where('user_code', '=', $user->code)->pluck('unit_code')->toArray();

        $students = \DB::query()->from('el_profile_view');
        if (!Permission::isAdmin()){
            $students = $students->leftJoin('el_unit as b', 'b.code', '=', 'unit_code');
            $students = $students->whereIn('b.id', $units);
        }
//        if (!Permission::isAdmin()){
//            $students = $students->whereIn('unit_code', $unit_code);
//        }
        if ($title){
            $students = $students->whereIn('title_id', $title);
        }
        $students = $students->where('user_id', '>', 2)->get();

        json_result($students);
    }

    public function save(Request $request)
    {
        if ($request->input('save')>=2)
        {
            PlanSuggest::query()->where('id','=',$request->id)
                ->update([
                'status'=>(int)$request->save,
                'approved_by'=>\Auth::id()
            ]);
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.plan_suggest.form.edit', ['id' => $request->id])
            ]);
        }else {
            $this->validateRequest([
                'start_date' => 'required',
                'end_date' => 'required',
                'title' => 'required',
                'subject_name' => 'required',
                'type' => 'required',
                'amount' => 'required|integer|min:0|max:500',
            ], $request,PlanSuggest::getAttributeName());

            foreach ($request->title as $key => $item) {
                $arr[$item] = $item;
            }

            $arr_student = [];
            if ($request->students){
                foreach ($request->students as $key => $item) {
                    $arr_student[$item] = $item;
                }
            }

            $model = PlanSuggest::firstOrNew(['id' => $request->id]);
            $model->fill($request->all());
            $model->start_date = date_convert($request->start_date);
            $model->end_date = date_convert($request->end_date, '23:59:59');
            $model->unit_code = Profile::where('user_id', '=', \Auth::id())->value('unit_code');
            $model->created_by = \Auth::id();
            $model->attach = path_upload($model->attach);
            $model->attach_report = path_upload($model->attach_report);
            $model->title = json_encode($arr);
            $model->students = count($arr_student) > 0 ? json_encode($arr_student) : '';
            $model->status = (int)$request->input('save');
            if ($model->save()) {
                json_result([
                    'status' => 'success',
                    'message' => trans('lageneral.successful_save'),
                    'redirect' => \route('module.plan_suggest.form.edit', ['id' => $model->id])
                ]);
            }
        }
    }
    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $content='';
        foreach ($ids as $index => $id) {
            if($this->checkDelete($id))
                PlanSuggest::destroy($ids);
            else{
                $name = PlanSuggest::where('id','=',$id)->value('subject_name');
                $content .='<div class="alert alert-danger" role="alert">Khóa học: <i>['.$name.']</i> trạng thái đã đuyệt hoặc đang chờ duyệt</div>';
            }
        }
        if ($content)
            json_result([
                'status'=>'error',
                'message'=>'Dữ liệu chưa được xóa',
                'content'=>$content
            ]);
        else
            json_result(['status'=>'success','message'=>'Xóa thành công']);
    }
    public function approved(Request $request) {
        $ids = $request->input('ids', null);
        $content = '';
        foreach ($ids as $index => $id) {
            $planSuggest = PlanSuggest::find($id);
            if ($planSuggest->status == 2){
                continue;
            }
            if($this->checkApproved($id)){
                PlanSuggest::query()->where('id','=',$id)->update(['status'=>2,'approved_by'=>\Auth::id()]);
            } else{
                $name = PlanSuggest::where('id','=',$id)->value('subject_name');
                $content .='<div class="alert alert-danger" role="alert">Khóa học: <i>['.$name.']</i> trạng thái không được phép phê duyệt</div>';
            }
        }
        if ($content){
            json_result([
                'status'=>'error',
                'message'=>'Dữ liệu chưa được phê duyệt',
                'content'=>$content
            ]);
        }else{
            json_result(['status'=>'success','message'=>'Phê duyệt thành công']);
        }
    }
    public function deny(Request $request) {
        $ids = $request->input('ids', null);
        $content='';
        foreach ($ids as $index => $id) {
            $planSuggest = PlanSuggest::find($id);
            if ($planSuggest->status == 3){
                continue;
            }
            if($this->checkDeny($id))
                PlanSuggest::query()->where('id','=',$id)->update(['status'=>3,'approved_by'=>\Auth::id()]);
            else{
                $name = PlanSuggest::where('id','=',$id)->value('subject_name');
                $content .='<div class="alert alert-danger" role="alert">Khóa học: <i>['.$name.']</i> trạng thái không được phép từ chối</div>';
            }
        }
        if ($content)
            json_result([
                'status'=>'error',
                'message'=>'Dữ liệu chưa được từ chối',
                'content'=>$content
            ]);
        else
            json_result(['status'=>'success','message'=>'Cập nhật từ chối thành công']);
    }
    private function checkDelete($id)
    {
        $exists = PlanSuggest::query()->where('id','=',$id)->whereIn('status', [0,3])->exists();
        if ($exists)
            return true;
        return false;
    }
    private function checkApproved($id)
    {
        $exists = PlanSuggest::query()
            ->where('id','=',$id)
            ->whereIn('status', [1,3])
            ->exists();

        if ($exists){
            return true;
        }
        return false;
    }
    private function checkDeny($id)
    {
        $exists = PlanSuggest::query()->where('id','=',$id)->whereIn('status', [1,2,3])->exists();
        if ($exists)
            return true;
        return false;
    }
    public function download(Request $request, $file)
    {
        $path = "/uploads/plansuggest/";
        $pathToFile = Config('app.datafile.dataroot') . $path.'/'.$file;
        if (!\File::exists($pathToFile))
            return abort(404);
        return \response()->download($pathToFile);
    }
    public function export(Request $request){
        $month = $request->month;
        $year = $request->year;
        $unit = $request->unit;
        $status = $request->status;

        return (new PlanSuggestExport($month, $year, $unit, $status))->download('danh_sach_de_xuat_ke_hoach_dao_tao_'. date('d_m_Y')
            .'.xlsx');

    }
}
