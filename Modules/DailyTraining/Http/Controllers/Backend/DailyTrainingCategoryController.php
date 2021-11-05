<?php

namespace Modules\DailyTraining\Http\Controllers\Backend;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\DailyTraining\Entities\DailyTrainingCategory;
use Modules\DailyTraining\Entities\DailyTrainingPermissionUserCategory;
use Modules\DailyTraining\Entities\DailyTrainingVideo;

class DailyTrainingCategoryController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('dailytraining::backend.category.index',[
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
        DailyTrainingCategory::addGlobalScope(new DraftScope());
        $query = DailyTrainingCategory::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = '';
            if ($row->name != 'Mặc định'){
                $row->edit_url = route('module.daily_training.edit', ['id' => $row->id]);
            }

            $total_video = DailyTrainingVideo::where('category_id', '=', $row->id)->count();
            $total_video_approve = DailyTrainingVideo::where('category_id', '=', $row->id)->where('approve', '=', 1)->count();

            $row->number_video = ($total_video_approve .'/'. $total_video);

            $row->video_url = route('module.daily_training.video', ['cate_id' => $row->id]);
            $row->permission_url = route('module.daily_training.permission',['cate_id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'status_video' => 'nullable',
        ], $request, DailyTrainingCategory::getAttributeName());

        $model = DailyTrainingCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->status_video = 0;
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.daily_training')
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form(Request $request) {
        $model = DailyTrainingCategory::select(['id','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $cate = DailyTrainingCategory::find($id);

            if ($cate->name == 'Mặc định'){
                continue;
            }

            $cate->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function savePermissionUser(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'category' =>'required',
        ], $request, [
            'ids' => 'Tài khoản người dùng',
            'category' => 'Danh mục'
        ]);

        $ids = $request->input('ids', null);
        $category = $request->input('category',null);
        foreach($ids as $id){
            $permission_user_cate = new DailyTrainingPermissionUserCategory();
            $permission_user_cate->category_id = $category;
            $permission_user_cate->user_id = $id;
            $permission_user_cate->save();
        }
        json_message('Cập nhật thành công');
    }

    public function permission($cate_id){
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        return view('dailytraining::backend.premission.index',[
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'cate_id'=>$cate_id,
        ]);
    }

    public function getUserPermission($category, Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Profile::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.code',
            'a.email',
            'a.lastname',
            'a.firstname',
            'a.status',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS unit_manager',
        ]);
        $query->from('el_profile as a')
            ->leftjoin('el_unit as b','b.code','=','a.unit_code')
            ->leftjoin('el_unit as d','d.code','=','b.parent_code')
            ->leftjoin('el_titles as c','c.code','=','a.title_code')
            ->where('a.user_id', '>', 2)
            ->whereNotIn('a.user_id', function ($sub) use ($category){
                $sub->select(['user_id'])
                    ->from('el_daily_training_permission_user_category')
                    ->where('category_id', '=', $category)
                    ->pluck('user_id')
                    ->toArray();
            });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('a.status', '=', $status);
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
            $query->where('a.title_code', '=', $title->code);
        }

        $query->view('a');
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->full_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
