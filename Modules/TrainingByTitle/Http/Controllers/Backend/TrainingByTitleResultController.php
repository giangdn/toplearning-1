<?php

namespace Modules\TrainingByTitle\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Notifications;
use App\Profile;
use App\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineResult;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\User\Entities\UserCompletedSubject;

class TrainingByTitleResultController extends Controller
{
    public function index(){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };
        // return view('trainingbytitle::backend.training_by_title_result.index', [
        return view('backend.learning_manager.index',[
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }

    public function getDataUser(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = ProfileView::query();
        $query->select([
            'el_profile_view.*',
            'u.username'
        ]);
        $query->from('el_profile_view');
        $query->leftJoin('user AS u', 'u.id', '=', 'el_profile_view.user_id');
        $query->where('el_profile_view.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile_view.status', '=', $status);
        }
        if ($request->area) {
            $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile_view.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
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
            $query->where('el_profile_view.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('el_profile_view.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.training_by_title.result.detail', ['user_id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->area_url = route('module.backend.user.get_area', ['user_id' => $row->user_id]);

            $count_training_by_title_detail = TrainingByTitleDetail::where('title_id', '=', $row->title_id)->count();
            $count_subject_completed = UserCompletedSubject::whereUserId($row->user_id)->groupBy(['subject_id'])->count();
            $row->complete = $count_subject_completed.'/'.$count_training_by_title_detail;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function detail($user_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $fullname = Profile::fullname($user_id);
        return view('trainingbytitle::backend.training_by_title_result.detail',[
            'user_id' => $user_id,
            'full_name'=> $fullname,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }

    public function getDataUserDetail(Request $request, $user_id)
    {
        $user = ProfileView::where('user_id', '=', $user_id)->first();

        $query = TrainingByTitleDetail::query();
        $query->select([
            'a.subject_code',
            'a.subject_name',
            'b.course_id',
            'b.code as course_code',
            'b.name as course_name',
            'b.course_type',
            'b.start_date',
            'b.end_date',
        ]);
        $query->from("el_training_by_title_detail AS a");
        $query->leftJoin('el_course_view as b', 'b.subject_id', '=', 'a.subject_id');
        $query->where('b.status','=', 1);
        $query->where('a.title_id','=', $user->title_id);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->course_type == 1){
                $result = OnlineResult::whereCourseId($row->course_id)->where('user_id', '=', $user_id)->first();
                $course_type = 'Trực tuyến';
            }else{
                $result = OfflineResult::whereCourseId($row->course_id)->where('user_id', '=', $user_id)->first();
                $course_type = 'Tập trung';
            }
            $row->score = $result ? $result->score : '';
            $row->result = ($result && $result->result == 1) ? 'Hoàn thành' : 'Chưa hoàn thành';

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_type = $course_type;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
