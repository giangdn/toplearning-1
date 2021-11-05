<?php

namespace App\Http\Controllers\Frontend;

use App\Automail;
use App\Permission;
use App\PermissionUser;
use App\PlanApp;
use App\PlanAppItem;
use App\PlanAppStatus;
use App\Profile;
use App\Models\Categories\StudentCost;
use App\Models\Categories\UnitManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\PlanApp\Entities\PlanAppTemplateCate;
use Modules\PlanApp\Entities\PlanAppTemplateItem;

class PlanAppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.plan_app.planapp', [
                /*'list' => $this->getData($request),*/
            ]);
        }
        return view('frontend.planapp');
    }

    public function form(Request $request, $course_id, $course_type)
    {
        if ($request->method()=='POST') {
            $validator = \Validator::make($request->all(),
                [
                    'name.*.*'=>'required',
                    'item_1.*.*'=>'required',
                    'item_2.*.*'=>'required',
                    'item_3.*.*'=>'required'
                ],
                [
                    'name.*.*'=>'Vui lòng nhập tất cả tiêu chí trong từng đề mục',
                    'item_1.*.*'=>'Vui lòng nhập tất cả tiêu chí trong từng đề mục',
                    'item_2.*.*'=>'Vui lòng nhập tất cả tiêu chí trong từng đề mục',
                    'item_3.*.*'=>'Vui lòng nhập tất cả tiêu chí trong từng đề mục'
                ]);
            if($validator->fails()){
                json_message($validator->errors()->all()[0], 'error');
            }
            if ($course_type==1)
                $plan_app_id = OnlineCourse::find($course_id,['plan_app_template'])->toArray()['plan_app_template'];
            else
                $plan_app_id = OfflineCourse::find($course_id,['plan_app_template'])->toArray()['plan_app_template'] ;
            $cates = PlanAppTemplateCate::where('plan_app_id',$plan_app_id)->get();

            foreach ($cates as $index => $cate) {
                foreach ($request->name[$cate->id] as $i => $ii) {
                    if (isset($request->item_id[$cate->id][$i]) && $request->item_id[$cate->id][$i]>0){
                        PlanAppItem::where('id','=',$request->item_id[$cate->id][$i])
                            ->update([
                                'name'=>$ii,
                                'criteria_1'=>$request->item_1[$cate->id][$i],
                                'criteria_2'=>$request->item_2[$cate->id][$i],
                                'criteria_3'=>$request->item_3[$cate->id][$i],
                                'sort'=>($i+1),
                                'user_id'=>Auth::id(),
                                'cate_id'=>$cate->id,
                                'plan_app_id'=>$plan_app_id,
                                'course_id'=>$course_id,
                                'course_type'=>$course_type,
                            ]);
                    }else{
                        $PlanAppItem = new PlanAppItem();
                        $PlanAppItem->name=$ii;
                        $PlanAppItem->criteria_1=$request->item_1[$cate->id][$i];
                        $PlanAppItem->criteria_2=$request->item_2[$cate->id][$i];
                        $PlanAppItem->criteria_3=$request->item_3[$cate->id][$i];
                        $PlanAppItem->sort=($i+1);
                        $PlanAppItem->user_id=Auth::id();
                        $PlanAppItem->cate_id=$cate->id;
                        $PlanAppItem->plan_app_id=$plan_app_id;
                        $PlanAppItem->course_id=$course_id;
                        $PlanAppItem->course_type=$course_type;
                        $PlanAppItem->save();
                    }

                }
            }
            $check = PlanApp::where('plan_app_id','=',$plan_app_id)
                ->where('user_id','=', Auth::id())
                ->where('course_id','=',$course_id)
                ->where('course_type','=', $course_type)->first();
            if ($check){
                PlanApp::query()->where('plan_app_id','=',$plan_app_id)
                    ->where('user_id','=', Auth::id())
                    ->where('course_id','=',$course_id)
                    ->where('course_type','=',$course_type)
                    ->update([
                        'status'=>($request->btn_save==1)?0:1,
                        'suggest_self'=>$request->suggest_self
                    ]);
            }else{
                $PlanApp = new PlanApp();
                $PlanApp->plan_app_id = $plan_app_id;
                $PlanApp->user_id = Auth::id();
                $PlanApp->course_id = $course_id;
                $PlanApp->course_type = $course_type;
                $PlanApp->suggest_self = $request->suggest_self;
                $PlanApp->status = ($request->btn_save==1)?0:1;
                $PlanApp->save();
            }

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('frontend.plan_app.form',['course'=>$course_id,'type'=>$course_type])
            ]);
        }else{
            $plan_app = PlanApp::where('user_id','=', Auth::id())
                ->where('course_id','=',$course_id)
                ->where('course_type','=',$course_type)
                ->first();

            $course = DB::table('el_course_view')
                ->where('id','=',$course_id)
                ->where('course_type','=',$course_type)
                ->first();

            $profile = DB::table('el_profile_view')->where('user_id','=',Auth::id())->first();

            if ($course_type == 2){
                $plan_app_template = OfflineCourse::find($course_id)->toArray()['plan_app_template'];
            }else{
                $plan_app_template = OnlineCourse::find($course_id)->toArray()['plan_app_template'];
            }

            $plan_app_template_cate = PlanAppTemplateCate::query()
                ->where('plan_app_id','=',$plan_app_template)->orderBy('sort')
                ->get();

            if ($plan_app)
                switch ($plan_app->status){
                case 1:
                case 2:
                    $enable ="disabled";
                    break;
                default:
                    $enable ="";
            }
            else
                $enable='';

            if (url_mobile()){
                return view('themes.mobile.frontend.plan_app.planapp_form', [
                    'plan_app_template_cate' => $plan_app_template_cate,
                    'plan_app' => $plan_app,
                    'course' => $course,
                    'profile' => $profile,
                    'enable' => $enable
                ]);
            }

            return view('frontend.planapp_form',
                [
                    'plan_app_template_cate'=>$plan_app_template_cate,
                    'plan_app'=>$plan_app,
                    'course'=>$course,
                    'profile'=>$profile,
                    'enable'=>$enable
                ]);
        }
    }
    public function formEvaluation(Request $request, $course_id, $course_type)
    {
        $course = DB::table('el_course_view')->where('id','=',$course_id)->where('course_type','=',$course_type)->first();
        $profile = DB::table('el_profile_view')->where('user_id','=',Auth::id())->first();
        $plan_app = PlanApp::where('user_id','=',Auth::id())
            ->where('course_id','=',$course_id)
            ->where('course_type','=',$course_type)
            ->first();
        if ($course_type == 2){
            $plan_app_template = OfflineCourse::find($course_id)->toArray()['plan_app_template'];
        }else{
            $plan_app_template = OnlineCourse::find($course_id)->toArray()['plan_app_template'];
        }
        $plan_app_template_cate = PlanAppTemplateCate::where('plan_app_id', '=', $plan_app_template)->orderBy('sort')->get();

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

        if (url_mobile()){
            return view('themes.mobile.frontend.plan_app.planapp_evaluation_form', [
                'plan_app_template_cate'=>$plan_app_template_cate,
                'plan_app'=>$plan_app,
                'course'=>$course,
                'profile'=>$profile,
                'visiable'=>$visiable
            ]);
        }

        return view('frontend.planapp_evaluation_form',
            [
                'plan_app_template_cate'=>$plan_app_template_cate,
                'plan_app'=>$plan_app,
                'course'=>$course,
                'profile'=>$profile,
                'visiable'=>$visiable
            ]);

    }
    public function saveFormEvaluation(Request $request, $course_id, $course_type)
    {
        if ($request->method()=='POST') {
            $validator = \Validator::make($request->all(),
                [
                    'self'  =>'required',
                    'result.*.*'=>'required',
                    'finish.*.*'=>'required',
                ],
                [
                    'self.required'  =>'Chưa chọn phần tự dánh giá của học viên',
                    'result.*.*'=>'Vui lòng nhập tất cả tiêu chí trong từng đề mục',
                    'finish.*.*'=>'Vui lòng nhập tất cả tiêu chí trong từng đề mục',
                ]);
            if($validator->fails()){
                json_message($validator->errors()->all()[0], 'error');
            }
            if ($course_type == 2){
                $course = OfflineCourse::find($course_id);
                $objectType = 'action_plan_manager_review_offline';
            }else{
                $course = OnlineCourse::find($course_id);
                $objectType = 'action_plan_manager_review_online';
            }
            $plan_app_id = $course->plan_app_template;
            $cates = PlanAppTemplateCate::where('plan_app_id',$plan_app_id)->get();
            foreach ($cates as $index => $cate) {
                foreach ($request->name[$cate->id] as $i => $ii) {
                    PlanAppItem::query()->where('id','=',$request->item_id[$cate->id][$i])->where('user_id','=',Auth::id())
                        ->update([
                            'result'=>$request->result[$cate->id][$i],
                            'finish'=>$request->finish[$cate->id][$i],
                        ]);

                }
            }
            PlanApp::query()->where('plan_app_id','=',$plan_app_id)
                ->where('user_id','=',Auth::id())
                ->where('course_id','=',$course_id)
                ->where('course_type','=',$course_type)
                ->update([
                    'status'=>($request->btn_save==1)?2:4,
                    'evaluation_self'=>(int)$request->self,
                    'evaluation_date'=> date('Y-m-d H:i:s')
                ]);
            if ($request->btn_save==1){
                $profile = Profile::find(Auth::id());
                $managers = UnitManager::with('managers:code,id,code,user_id,email,firstname,lastname,gender')->where(['unit_code'=>$profile->unit_code])->get()->pluck('managers')->flatten();
                foreach ($managers as $manager) {
                    $signature = getMailSignature($manager->user_id);
                    $params = [
                        'signature' => $signature,
                        'gender'    =>$manager->gender==1?'Anh':'Chị',
                        'full_name'    =>$manager->lastname.' '.$manager->firstname,
                        'course_code'    =>$course->code,
                        'course_name'    =>$course->name,
                        'employee_code' => $profile->code,
                        'employee_name' => $profile->lastname.' '.$profile->firstname,
                        'action_plan' => $course->planAppTemplate->name,
                        'start_date'    =>get_date($course->start_date),
                        'end_date'    =>get_date($course->end_date),
                        'url' => route('module.plan_app.user', ['course' => $course->id,'type' => $request->course_type]),
                    ];
                    $id = $plan_app_id.$manager->user_id.$profile->user_id;
                    $automail = new Automail();
                    $automail->template_code = 'review_action_plan_manager';
                    $automail->params = $params;
                    $automail->users = [$manager->user_id];
                    $automail->check_exists = true;
                    $automail->check_exists_status = 0;
                    $automail->object_id = $id;
                    $automail->object_type = $objectType;
                    $automail->addToAutomail();
                }
            }

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('frontend.plan_app.form.evaluation',['course'=>$course_id,'type'=>$course_type])
            ]);
        }
    }
    public function delete(Request $request)
    {
        $delete=PlanAppItem::query()->where(['id'=>$request->id,'user_id'=>Auth::id()])->delete();
        if ($delete){
            json_result([
                'status' => 'success',
                'message' => 'Xóa thành công'
            ]);
        }
    }
    public static function getPlanAppItem($cate_id)
    {
        return PlanAppTemplateItem::where('cate_id','=',$cate_id)->get();
    }
    public static function getPlanAppItemTarget($cate_id)
    {
        return PlanAppTemplateItem::where('cate_id','=',$cate_id)->where('sort','=',1)->first();
    }
    public static function getPlanAppItemUser($cate_id)
    {
        return PlanAppItem::where('cate_id','=',$cate_id)->where('user_id','=',Auth::id())->get();
    }
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::table('el_course_view as a')
            ->join('el_course_register_view as b',function($join){
                $join->on( 'a.course_id','=','b.course_id');
                $join->on( 'a.course_type','=','b.course_type');
            })
            ->leftJoin('el_plan_app as c', function ($join){
                $join->on('c.course_id','=','a.id');
                $join->on('c.course_type','=','a.course_type');
                $join->on('c.user_id','=','b.user_id');
            })
            ->where('b.user_id','=', Auth::id())
            ->where('a.action_plan','=',1)
            ->where('b.status','=',1)
            ->select(['a.*','c.status','c.start_date as start_evaluation','c.result']);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        /*if (url_mobile()){
            return $query->get();
        }*/

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->evaluation = $this->isEvaluation($row->start_evaluation, $row->status);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->status_text = PlanAppStatus::getStatus($row->status);
            $route = $row->course_type==1?'module.online.detail':'module.offline.detail';
            $row->course_url = route($route,['id'=>$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    private function isEvaluation($start_evaluation, $status)
    {
        /*$days = (strtotime(date('Y-m-d')) - strtotime($start_evaluation))/ (60 * 60 * 24);

        if (!$start_evaluation || $days<0 || $status<>2)
            return 0; // chưa tới hạn đánh giá
        if ($days>=0)*/
        if ($status>=2)
            return 1; // đánh giá
        return 0;
    }

    public function sendMailApprove(Request $request) {
        $this->validateRequest([
            'user_id' => 'required',
        ], $request, ['user_id' => 'Nhân viên']);

        if ($request->course_type == 2){
            $course = OfflineCourse::find($request->course_id);
        }else{
            $course = OnlineCourse::find($request->course_id);
        }
        $templateObject = $request->course_type==2?'action_plan_approve_offline':'action_plan_approve_online';
        $profile = Profile::find($request->user_id);
        $managers = UnitManager::with('managers:code,id,code,user_id,email,firstname,lastname,gender')->where(['unit_code'=>$profile->unit_code])->get()->pluck('managers')->flatten();
        foreach ($managers as $manager) {
            $signature = getMailSignature($manager->user_id);
            $automail = new Automail();
            $automail->template_code = 'action_plan_approve';
            $automail->params = [
                'signature' => $signature,
                'gender'    =>$manager->gender==1?'Anh':'Chị',
                'full_name'    =>$manager->lastname.' '.$manager->firstname,
                'course_code'    =>$course->code,
                'course_name'    =>$course->name,
                'start_date'    =>get_date($course->start_date),
                'end_date'    =>get_date($course->end_date),
                'employee_code'    =>$profile->code,
                'employee_name'    =>$profile->lastname.' '.$profile->firstname,
                'action_plan'    =>$course->planAppTemplate->name,
                'url' => route('module.plan_app.user', [
                    'course' => $course->id,
                    'type' => $request->course_type,
                ]),
            ];
            $automail->users = [$manager->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $manager->user_id.$profile->user_id;
            $automail->object_type = $templateObject;
            $automail->addToAutomail();
        }
    }
    public function sendMailManager(array $params,array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'review_action_plan_manager';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $register_id;
        $automail->object_type = 'delete_course_offline';
        $automail->addToAutomail();
    }
}
