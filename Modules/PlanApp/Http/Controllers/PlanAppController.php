<?php

namespace Modules\PlanApp\Http\Controllers;

use App\Automail;
use App\Permission;
use App\PlanApp;
use App\PlanAppItem;
use App\PlanAppStatus;
use App\Profile;
use App\Models\Categories\UnitManager;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\PlanApp\Entities\PlanAppTemplate;
use App\Http\Controllers\Controller;
use Modules\PlanApp\Entities\PlanAppTemplateCate;
use Modules\PlanApp\Entities\PlanAppTemplateItem;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\TemplateProcessor;

class PlanAppController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('planapp::backend.index');
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        PlanAppTemplate::addGlobalScope(new DraftScope());
        $query = PlanAppTemplate::query()->select("*");
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.plan_app.template.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public static function getPlanAppItem($cate_id)
    {
        return PlanAppTemplateItem::where('cate_id','=',$cate_id)->get();
    }

    public function form($id = 0) {
        if ($id) {
            $model = PlanAppTemplate::find($id);
            $page_title = $model->name;
            $cate = PlanAppTemplateCate::where('plan_app_id','=',$id)->get();
            $view = 'planapp::backend.form';
        }
        else {
            $model = new PlanAppTemplate();
            $page_title = trans('backend.add_new') ;
            $cate=null;
            $view = 'planapp::backend.form_create';
        }

        return view($view, [
            'model' => $model,
            'cate'=>$cate,
            'page_title' => $page_title,
        ]);
    }

    public function save(Request $request) {

        $this->validateRequest([
            'name' => 'required'
        ], $request,
            PlanAppTemplate::getAttributeName());
        $cates = $request->cate;
        $items = $request->item;
        foreach ($cates as $key => $cate) {
            if (!$cate)
                json_message('Vui lòng nhập đề mục '.$key, 'error');
            foreach ($items[$key] as $item) {
                if (!$item)
                    json_message('Vui lòng nhập tất cả tiêu chí của đề mục '.$key, 'error');
            }
        }
        $model = PlanAppTemplate::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($model->save()) {
            //delete trước khi update
            if ($request->id) {
                $ids = $request->cate_id;
                PlanAppTemplateCate::query()->where(['plan_app_id' => $model->id])->whereNotIn('id', $ids)->delete();
            }
            /****update đề mục mẫu đánh giá*********/

            foreach ($request->cate as $index => $item) {
                if (!$item) continue;
                $cate_data = PlanAppTemplateCate::where('sort','=',$index)->where('plan_app_id','=',$model->id)->first();
                if($cate_data){
                    PlanAppTemplateCate::where('sort', '=', $index)
                        ->where('plan_app_id', '=', $model->id)
                        ->update([
                            'name' => $item,
                            'sort' => $index
                        ]);
                    foreach ($request->item[$index] as $_index => $_item) {
                        if (PlanAppTemplateItem::query()->where('cate_id','=',$cate_data->id)->where('sort','=',$_index)->exists()){
                            PlanAppTemplateItem::where('sort', '=', $_index)
                                ->where('cate_id', '=', $cate_data->id)
                                ->update([
                                    'name' => $_item,
                                    'sort' => $_index,
                                    'data_type' => (int) $request->type[$index][$_index]
                                ]);
                        }else{
                            $obj = new PlanAppTemplateItem();
                            $obj->name = $_item;
                            $obj->sort = $_index;
                            $obj->cate_id = $cate_data->id;
                            $obj->data_type =(int) $request->type[$index][$_index];
                            $obj->save();
                        }

                    }
                }
                else{
                    $cate = new PlanAppTemplateCate();
                    $cate->name = $item;
                    $cate->sort = $index;
                    $cate->plan_app_id = $model->id;
                    if($cate->save()){
                        /**update field cho từng đề mục ***/
                        foreach ($request->item[$index] as $_index => $_item) {
                            $obj = new PlanAppTemplateItem();
                            $obj->name = $_item;
                            $obj->sort = $_index;
                            $obj->cate_id = $cate->id;
                            $obj->data_type =(int) $request->type[$index][$_index];
                            $obj->save();
                        }
                    }
                }

            }

            /************************************/
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.plan_app.template.edit', [
                    'id' => $model->id
                ])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function edit($id)
    {
        return view('planapp::edit');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        PlanAppTemplate::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function showCourses()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('planapp::backend.course',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getCourses(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $units =  UnitManager::getArrayUnitManagedByUser();

        PlanApp::addGlobalScope(new DraftScope());
        $subQuery = PlanApp::query()->select('course_id','course_type')->distinct();
        $query= \DB::table('el_course_view as a')
            ->select(['a.*'])
            ->joinSub($subQuery,'b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->where('a.action_plan', '=', 1)
            ->where('a.status', '=', 1);

        if ($search) {
            $query->where(function ($grquery) use ($search){
                $grquery->where('a.name', 'like', "%". $search ."%");
                $grquery->orWhere('a.code', 'like', "%". $search ."%");
            });
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->edit_url = route('module.plan_app.user', ['course' => $row->id,'type'=>$row->course_type]);
            $row->export_word = route('module.plan_app.export_plan_course', ['course' => $row->id,'type'=>$row->course_type]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function showUsers($course_id,$course_type)
    {
        return view('planapp::backend.user',['course_id'=>$course_id,'course_type'=>$course_type]);
    }

    public function getUsers(Request $request, $course_id, $course_type)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'full_name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $table = $course_type==1?'el_online_register':'el_offline_register';
        $units =  UnitManager::getArrayUnitManagedByUser();
        $query = \DB::table("$table as a")
            ->join('el_plan_app as b',function ($join){
              $join->on('a.user_id','=','b.user_id');
              $join->on('a.course_id','=','b.course_id');
            })
            ->leftJoin('el_profile as c', 'c.user_id', '=', 'a.user_id')
            ->leftJoin('el_unit as d', 'd.code', '=', 'c.unit_code')
            ->leftJoin('el_unit as e', 'e.code', '=', 'd.parent_code')
            ->leftJoin('el_titles as f', 'f.code', '=', 'c.title_code')
            ->where('a.course_id','=', $course_id);
             if (!Permission::isAdmin()){
//                 $query->join('el_unit as e', 'e.code', '=', 'c.unit_code');
                 $query->whereIn('d.id', $units);
             }
        $query->select([
                'b.id',
                'a.user_id',
                'b.status',
                'd.name as unit_name',
                'c.code',
                'c.lastname',
                'c.firstname',
                'c.email',
                'f.name as title_name',
                'b.evaluation_date',
                'e.name as parent_name'
            ]);

        if ($search) {
            $query->where(function ($grquery) use ($search){
                $grquery->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $grquery->orWhere('c.code', 'like', "%". $search ."%");
            });
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->full_name = $row->lastname . ' ' . $row->firstname;
            $row->status_text = PlanAppStatus::getStatus($row->status);
            if (in_array($row->status,[4,5])) // đánh giá Đánh giá hiệu quả đào tạo
                $route = 'module.plan_app.user.form.evaluation';
            else
                $route = 'module.plan_app.user.form';
//            $route = 'module.plan_app.user.form.evaluation';
            $row->edit_url = route($route, ['id' => $row->id,'user'=>$row->user_id]);
            $row->expired = ($row->status==4 && $this->isEvaluation($row->evaluation_date))?1:0;
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    private function isEvaluation($evaluation_date)
    {
        /*$days = (strtotime(date('Y-m-d')) - strtotime($evaluation_date))/ (60 * 60 * 24);
        if ($days>10)
            return 1;*/ // hết hạn đánh giá
        return 0;
    }

    public function savePlanAppUser(Request $request, $id,$user_id)
    {
        $course = PlanApp::query()->where('id','=',$id)->where('user_id','=',$user_id)->first();
        if ($course->course_type==1)
            $plan_app_day = OnlineCourse::query()->where('id','=',$course->course_id)->value('plan_app_day');
        else
            $plan_app_day = OfflineCourse::query()->where('id','=',$course->course_id)->value('plan_app_day');
        PlanApp::query()->where('id','=',$id)->where('user_id','=',$user_id)
            ->update([
                'status'=>$request->btn_save=='approved'?2:3,
                'approved_date'=> date("Y-m-d h:i:s"),
                'start_date'=> $request->btn_save=='approved'? add_day($plan_app_day,'Y-m-d'):null
            ]);
        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => $request->url()
        ]);
    }

    public function formPlanAppUser(Request $request, $id,$user_id)
    {
        $plan_app = PlanApp::query()->where('id','=',$id)->where('user_id','=',$user_id)->select(['id','plan_app_id','course_id','course_type','suggest_self','status'])->firstOrFail();
        $course = \DB::table('el_course_view')->where('id','=',$plan_app->course_id)->where('course_type','=',$plan_app->course_type)->first();
        $profile = \DB::table('el_profile_view')->where('user_id','=',$user_id)->first();
        $plan_app_template_cate = PlanAppTemplateCate::query()
            ->where('plan_app_id','=',$plan_app->plan_app_id)->orderBy('sort')
            ->get();
        if ($plan_app)
            switch ($plan_app->status){
                case 1:
                    $visiable ="visiable";
                    break;
                default:
                    $visiable ="";
            }
        else
            $visiable='';
        return view('planapp::backend.form_user',
            [
                'plan_app_template_cate'=>$plan_app_template_cate,
                'plan_app'=>$plan_app,
                'user_id'=>$user_id,
                'course_id'=>$plan_app->course_id,
                'course_type'=>$plan_app->course_type,
                'course'=>$course,
                'profile'=>$profile,
                'visiable'=>$visiable,
                'page_title'=>'Phê duyệt Đánh giá hiệu quả đào tạo'
            ]);

    }

    public function saveFormEvaluation(Request $request, $id, $user_id)
    {
        if ($request->method()=='POST') {
            PlanApp::query()
                ->where('id','=',$id)
                ->where('user_id','=',$user_id)
                ->update([
                    'status'=>5,
                    'evaluation_manager'=>$request->evaluation_manager,
                    'suggest_manager'=>$request->suggest_manager,
                    'reason_reality_manager'=>$request->reason,
                    'reality_manager'=>$request->evaluation,
                    'result'=>$request->result
                ]);
            $planApp = PlanApp::find($id);
            $course = $planApp->course_type==1?OnlineCourse::find($planApp->course_id):OfflineCourse::find($planApp->course_id);
            $object_type = $planApp->course_type==1?'action_plan_complete_online':'action_plan_complete_offline';
            $profile = Profile::find($user_id);
            $signature = getMailSignature($profile->user_id);
            $params = [
                'signature' => $signature,
                'gender'    =>$profile->gender==1?'Anh':'Chị',
                'full_name'    =>$profile->lastname.' '.$profile->firstname,
                'course_code'    =>$course->code,
                'course_name'    =>$course->name,
                'date_action_plan'    =>get_date($planApp->evaluation_date),
            ];
            $automail = new Automail();
            $automail->template_code = 'action_plan_complete';
            $automail->params = $params;
            $automail->users = [$user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $planApp->id;
            $automail->object_type = $object_type;
            $automail->addToAutomail();

            if ($planApp->result == 1){
                $setting = PromotionCourseSetting::where('course_id', $planApp->course_id)
                    ->where('type', $planApp->course_type)
                    ->where('status', 1)
                    ->where('code', '=', 'evaluate_training_effectiveness')
                    ->first();
                if ($setting && $setting->point){
                    $user_point = PromotionUserPoint::firstOrCreate([
                        'user_id' => $profile->user_id
                    ], [
                        'point' => 0,
                        'level_id' => 0
                    ]);
                    $user_point->point += $setting->point;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $profile->user_id);
                    $user_point->update();

                    $this->saveHistoryPromotion($profile->user_id, $setting->point, $setting->course_id, $planApp->course_type, $setting->id);
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        }
    }

    public function sendMailActionPlanFinish(array $params,array $user_id,int $planapp_id)
    {
        $automail = new Automail();
        $automail->template_code = 'action_plan_complete';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $planapp_id;
        $automail->object_type = 'delete_course_offline';
        $automail->addToAutomail();
    }

    public static function getPlanAppItemUser($cate_id,$user_id)
    {
        return PlanAppItem::where('cate_id','=',$cate_id)->where('user_id','=',$user_id)->get();
    }

    public function formEvaluation(Request $request, $id, $user_id)
    {
        $plan_app = PlanApp::where('id','=',$id)->where('user_id','=',$user_id)->first();
        $course = \DB::table('el_course_view')->where('id','=',$plan_app->course_id)->where('course_type','=',$plan_app->course_type)->first();
        $profile = \DB::table('el_profile_view')->where('user_id','=',$user_id)->first();
        $plan_app_template_cate = PlanAppTemplateCate::query()
            ->where('plan_app_id','=',$plan_app->plan_app_id)->orderBy('sort')
            ->get();
        if ($plan_app)
            switch ($plan_app->status){
                case 2:
                    $visiable ="visiable";
                    break;
                default:
                    $visiable ="";
            }
        else
            $visiable='';
        return view('planapp::backend.form_evaluation',
            [
                'plan_app_template_cate'=>$plan_app_template_cate,
                'plan_app'=>$plan_app,
                'visiable'=>$visiable,
                'user_id'=>$user_id,
                'course_id'=>$course->id,
                'course_type'=>$course->course_type,
                'course'=>$course,
                'profile'=>$profile,
                'page_title'=>'Đánh giá hiệu quả đào tạo',
            ]);

    }

    public static function getPlanAppItemTarget($cate_id)
    {
        return PlanAppTemplateItem::where('cate_id','=',$cate_id)->where('sort','=',1)->first();
    }

    public function exportPlanCourse($course_id,$type)
    {
        if ($type==1)
            $course = OnlineCourse::find($course_id);
        else
            $course = OfflineCourse::find($course_id);
        $users = \DB::query()->select('a.user_id','a.reality_manager','a.reason_reality_manager','a.result','b.code','b.firstname','b.lastname', 'c.name as title')
            ->from('el_plan_app as a')
            ->join('el_profile as b','a.user_id','b.user_id')
            ->join('el_titles as c','c.code','b.title_code')
            ->where(['a.course_id'=>$course_id,'a.course_type'=>$type,'a.status'=>5])
            ->get();
        $values =[];
        $i = 1;
        foreach ($users as $key=> $user) {
            $values[$key] = [
                'user_id'=>$i,
                'reality_manager'=>$user->reality_manager,
                'pass'=>$user->result==1?'X':'',
                'fail'=>$user->result==2?'X':'',
                'full_name'=>$user->lastname.' '.$user->firstname,
                'code'=>$user->code,
                'title'=>$user->title,
                'app_reality'=>$user->reality_manager==1?'X':'',
                'not_app_reality'=>$user->reality_manager==2?$user->reason_reality_manager:'',
                ];
            $i++;
        }

        $total = count($users);
        $sum_pass = $users->where('result',1)->count();
        $pathWord = public_path('modules/planapp/template/plan_app_course_template.docx');
        $phpWord = new TemplateProcessor($pathWord);
        $phpWord->setValues([
            'course_name'=>$course->name,
            'training_unit'=>$course->training_unit?$course->training_unit:'',
            'start_date'=>get_date($course->start_date),
            'end_date'=>get_date($course->end_date),
            'day'=>date('d'),
            'month'=>date('m'),
            'year'=>date('Y'),
            'total'=>$total,
            'sum_pass'=>$sum_pass,
            'percent'=>$total>0? round(($sum_pass/$total)*100,2):''
        ]);

        $phpWord->cloneRowAndSetValues('user_id',$values);
//        $phpWord->saveAs(storage_path().'/result.docx');
        $file_name = Str::slug($course->name);
        header("Content-Disposition: attachment; filename=". $file_name .".docx");
        $phpWord->saveAs("php://output");

    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $type, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = $type;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        if ($type == 1){
            $course_name = OnlineCourse::query()->find($course_id)->name;
        }else{
            $course_name = OfflineCourse::query()->find($course_id)->name;
        }

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng khoá học.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của khoá học "'. $course_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }
}
