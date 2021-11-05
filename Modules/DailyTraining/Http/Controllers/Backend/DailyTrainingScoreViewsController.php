<?php

namespace Modules\DailyTraining\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\DailyTraining\Entities\DailyTrainingCategory;
use Modules\DailyTraining\Entities\DailyTrainingSettingScoreViews;

class DailyTrainingScoreViewsController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('dailytraining::backend.score_views.index',[
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
        DailyTrainingSettingScoreViews::addGlobalScope(new DraftScope());
        $query = DailyTrainingSettingScoreViews::query();

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit = route('module.daily_training.score_views.edit', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'from' => 'required',
            'score' => 'required',
        ], $request, DailyTrainingSettingScoreViews::getAttributeName());

        $from = $request->input('from');
        $to = $request->input('to');
        $id = $request->id;
        
        $check1 = DailyTrainingSettingScoreViews::query()
            ->where('id','!=',$id)
            ->where('from', '<=', $from)
            ->where('to', '>=', $from);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không hợp lệ',
            ]);
        }

        if ($to){
            $check2 = DailyTrainingSettingScoreViews::query()
                ->where('id','!=',$id)
                ->where('from', '<=', $to)
                ->where('to', '>=', $to);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Điểm nhập không hợp lệ',
                ]);
            }

            if ($from >= $to){
                json_result([
                    'status' => 'error',
                    'message' => 'Khoảng lượt xem không hợp lệ',
                ]);
            }
        }

        $model = DailyTrainingSettingScoreViews::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.daily_training.score_views')
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form(Request $request) {
        $model = DailyTrainingSettingScoreViews::select(['id','from','to','score'])->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result($model);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        DailyTrainingSettingScoreViews::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
