<?php

namespace Modules\Promotion\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\PromotionMethodSetting;
use Modules\Survey\Entities\Survey;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('promotion::backend.promotion.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Promotion::addGlobalScope(new DraftScope());
        $query = Promotion::query()
            ->select(['el_promotion.*','b.name as groupname'])
            ->leftJoin('el_promotion_group as b','el_promotion.promotion_group','=','b.id');

        if ($search) {
            $query->where('el_promotion.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->images = image_file($row->images);
            $row->period = Carbon::parse($row->period)->format('d-m-Y H:i');
            $row->rules = Str::limit($row->rules,'20','...');
            $row->created_by = Profile::fullname($row->created_by);
            $row->updated_by = $row->updated_by ? Profile::fullname($row->updated_by) : null;
            $row->edit_url = route('module.promotion.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $promotion_groups = PromotionGroup::where('status','=',1)->get();
        return view('promotion::backend.promotion.create',[
            'promotion_groups' => $promotion_groups,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validateRequest([
            'code' => 'required|unique:el_promotion,code',
            'name' => 'required',
            'point' => 'required|numeric|min:0',
            'images' => 'nullable|string',
            'period' => 'required|date_format:d/m/Y H:i',
            'rules' => 'nullable',
            'amount' => 'nullable|numeric|max:1000',
            'promotion_group' => 'required|exists:el_promotion_group,id',
            'contact' => 'nullable',
            'status' => 'in:0,1',
        ],$request,Promotion::getAttributeName());
        $promotion = new Promotion();
        $promotion->fill($request->all());

        if ($request->input('images')) {
            $sizes = config('image.sizes.medium');
            $model->images = upload_image($sizes, $request->input('images'));
        }

        $promotion->period = Carbon::parse(str_replace('/','-', $request->input('period')))->toDateTime();
        $promotion->created_by = Auth::id();
        if($promotion->save()){
            $redirect = route('module.promotion.edit', ['id' => $promotion->id]);
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => $redirect,
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $promotion = Promotion::findOrFail($id);
        $promotion_groups = PromotionGroup::get();
        return view('promotion::backend.promotion.edit',[
            'promotion' => $promotion,
            'promotion_groups' => $promotion_groups,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validateRequest([
            'code' => 'required|unique:el_promotion,code,'. $id,
            'name' => 'required',
            'point' => 'required|numeric',
            'images' => 'nullable|string',
            'period' => 'required|date_format:d/m/Y H:i',
            'rules' => 'nullable',
            'amount' => 'nullable|numeric|max:1000',
            'promotion_group' => 'required|exists:el_promotion_group,id',
            'contact' => 'nullable',
            'status' => 'in:0,1',
        ],$request,Promotion::getAttributeName());

        $promotion = Promotion::findOrFail($id);
        $promotion->fill($request->all());

        if ($request->input('images')) {
            $sizes = config('image.sizes.medium');
            $promotion->images = upload_image($sizes, $request->input('images'));
        }

        $promotion->period = Carbon::parse(str_replace('/','-', $request->input('period')))->toDateTime();
        $promotion->updated_by = Auth::id();
        if($promotion->save()){
            $redirect = route('module.promotion');
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => $redirect,
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Promotion::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveSetting(Request $request){
        $get_survey = Survey::find($request->course_id);
        if ($request->input('method') == 0){
            if (date_convert($request->start_date) && !date_convert($request->end_date)){
                json_message('Mời nhập thời gian kết thúc', 'error');
            }
            if (date_convert($request->start_date) && date_convert($request->end_date) && date_convert($request->start_date) >= date_convert($request->end_date)){
                json_message('Thời gian kết thúc phải sau bắt đầu', 'error');
            }
            if(!empty($get_survey) && $request->type == 4) {
                if (date_convert($request->start_date) && date_convert($request->end_date) && date_convert($request->start_date) < $get_survey->start_date){
                    json_message('Thời gian bắt đầu tính điểm phải sau thời gian bắt đầu khảo sát', 'error');
                }
                if (date_convert($request->start_date) && date_convert($request->end_date) && date_convert($request->end_date) > $get_survey->end_date){
                    json_message('Thời gian kết thúc tính điểm phải trước thời gian kết thúc khảo sát', 'error');
                }
            }

            $settings = PromotionCourseSetting::firstOrNew(['course_id' => $request->course_id, 'type' => $request->type, 'code' => 'complete']);
            $settings->course_id = $request->course_id;
            $settings->type = $request->type;
            $settings->code = 'complete';
            $settings->status = 1;
            $settings->method = $request->input('method');
            $settings->start_date = $request->start_date ? date_convert($request->start_date) : null;
            $settings->end_date = $request->end_date ? date_convert($request->end_date) : null;
            $settings->point = $request->point_complete;
            $settings->save();
        }

        if ($request->input('method') == 1){
            if ($request->min_score < 0){
                json_message('Điểm không được nhỏ hơn 0', 'error');
            }
            if ($request->min_score && $request->max_score && $request->min_score >= $request->max_score){
                json_message('Khoảng điểm sai', 'error');
            }
            $check_1 = PromotionCourseSetting::query()
                ->where('course_id', '=', $request->course_id)
                ->where('type', '=', $request->type)
                ->where('code', '=', 'landmarks')
                ->where('min_score', '<=', $request->min_score)
                ->where('max_score', '>=', $request->min_score);

            $check_2 = PromotionCourseSetting::query()
                ->where('course_id', '=', $request->course_id)
                ->where('type', '=', $request->type)
                ->where('code', '=', 'landmarks')
                ->where('min_score', '<=', $request->max_score)
                ->where('max_score', '>=', $request->max_score);

            if ($check_1->exists() || $check_2->exists()){
                json_message('Khoảng điểm không hợp lệ', 'error');
            }

            $settings = new PromotionCourseSetting();
            $settings->course_id = $request->course_id;
            $settings->type = $request->type;
            $settings->code = 'landmarks';
            $settings->status = 1;
            $settings->method = $request->input('method');
            $settings->min_score = $request->min_score ? $request->min_score : null;
            $settings->max_score = $request->max_score ? $request->max_score : null;
            $settings->point = $request->point_landmarks;
            $settings->save();
        }

        if ($request->input('method') == 2){
            $arr_code = $request->code;
            $point = $request->point;

            foreach ($arr_code as $key => $item){
                $settings = PromotionCourseSetting::firstOrNew(['course_id' => $request->course_id, 'type' => $request->type, 'code' => $item]);
                $settings->course_id = $request->course_id;
                $settings->type = $request->type;
                $settings->code = $item;
                $settings->status = 1;
                $settings->method = $request->input('method');
                $settings->point = $point[$key];
                $settings->save();
            }
        }

        if ($request->input('method') == 3){
            if ($request->min_percent < 0){
                json_message('Điểm không được nhỏ hơn 0', 'error');
            }
            if ($request->min_percent && $request->max_percent && $request->min_percent >= $request->max_percent){
                json_message('Khoảng % sai', 'error');
            }
            $check_1 = PromotionCourseSetting::query()
                ->where('course_id', '=', $request->course_id)
                ->where('type', '=', $request->type)
                ->where('code', '=', 'attendance')
                ->where('min_percent', '<=', $request->min_percent)
                ->where('max_percent', '>=', $request->min_percent);

            $check_2 = PromotionCourseSetting::query()
                ->where('course_id', '=', $request->course_id)
                ->where('type', '=', $request->type)
                ->where('code', '=', 'attendance')
                ->where('min_percent', '<=', $request->max_percent)
                ->where('max_percent', '>=', $request->max_percent);

            if ($check_1->exists() || $check_2->exists()){
                json_message('Khoảng % không hợp lệ', 'error');
            }

            $settings = new PromotionCourseSetting();
            $settings->course_id = $request->course_id;
            $settings->type = $request->type;
            $settings->code = 'attendance';
            $settings->status = 1;
            $settings->method = $request->input('method');
            $settings->min_percent = $request->min_percent ? $request->min_percent : null;
            $settings->max_percent = $request->max_percent ? $request->max_percent : null;
            $settings->point = $request->point_attendance;
            $settings->save();
        }

        /*$settings = PromotionCourseSetting::findOrFail($id);

        $settings->fill($request->all());
        if ($request->input('method') == 1)
            $settings->point = $request->input('sPoint');
        if (!$request->has('status'))
            $settings->status = 0;
        $settings->update();

        $action = $request->has('action');
        if($request->input('method') == 0 && $request->input('status') == 1 && !$action){
            $this->validateRequest([
                'score' => 'required|numeric|max:100|min:0',
                'point' => 'required|numeric|min:0',
            ],$request,PromotionCourseSetting::getAttributeName());
            $method = new PromotionMethodSetting();
            $method->setting_id = $settings->id;
            $method->fill($request->all())->saveOrFail();
        }*/

        if ($request->input('type') == 1){
            $route = route('module.online.edit', ['id' => $settings->course_id]);
        }
        if ($request->input('type') == 2){
            $route = route('module.offline.edit', ['id' => $settings->course_id]);
        }
        if ($request->input('type') == 3){
            if ($request->quiz_by_course){
                $route = route('module.online.quiz.edit', ['course_id' => $request->quiz_by_course, 'id' => $settings->course_id]);
            }else{
                $route = route('module.quiz.edit', ['id' => $settings->course_id]);
            }
        }
        if ($request->input('type') == 4){
            $route = route('module.survey.edit', ['id' => $settings->course_id]);
        }

        $redirect = $route;
        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
            'redirect' => $redirect."?tabs=promotion",
        ]);
    }

    public function deleteSettingMethod(Request $request){
        $ids = $request->input('ids', null);
        PromotionCourseSetting::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getPromotionSetting(Request $request, $courseId, $course_type, $code){
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = PromotionCourseSetting::query()
            ->where('code', '=', $code)
            ->where('type', '=', $course_type)
            ->where('course_id','=', $courseId);

        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if ($row->code == 'landmarks'){
                $row->score = number_format($row->min_score,2) . ' => ' . number_format($row->max_score, 2);
            }

            if ($row->code == 'attendance'){
                $row->score = number_format($row->min_percent, 2) . ' => ' . number_format($row->max_percent, 2);
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Promotion::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Promotion::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }
}
