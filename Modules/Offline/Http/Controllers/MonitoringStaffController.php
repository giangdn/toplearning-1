<?php

namespace Modules\Offline\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineMonitoringStaff;
use Modules\Offline\Entities\OfflineTeacher;
use App\Models\Categories\TrainingTeacher;

class MonitoringStaffController extends Controller
{
    public function index($course_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $course = OfflineCourse::find($course_id);
        $page_title = $course->name;

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('offline::backend.monitoring_staff.index', [
            'page_title' => $page_title,
            'course' => $course,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData($course_id, Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineMonitoringStaff::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.email',
            'b.expbank',
            'b.code',
            'c.name AS title_name',
            'd.name AS unit_name',
            'f.name AS unit_manager',
        ]);
        $query->from('el_offline_course_monitoring_staff AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'd.parent_code');
        $query->where('a.course_id', '=', $course_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('c.id', '=', $title);
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('d.id', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        $count = $query->count();
        $query->orderBy('b.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'user_id' => 'required|exists:el_profile,id',
        ], $request, OfflineMonitoringStaff::getAttributeName());

        $user_id = $request->input('user_id');
        if(OfflineMonitoringStaff::checkExists($course_id, $user_id)){
            json_message('Cán bộ đã tồn tại', 'error');
        }
        $model = new OfflineMonitoringStaff();
        $model->user_id = $user_id;
        $model->fullname = Profile::fullname($user_id);
        $model->course_id = $course_id;

        if ($model->save()) {

            $redirect = route('module.offline.monitoring_staff', ['id' => $course_id]);

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove($course_id, Request $request) {
        $ids = $request->input('ids', null);
        OfflineMonitoringStaff::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function saveNote($course_id, Request $request) {
        $this->validateRequest([
            'note' => 'nullable',
            'off_monitoring_staff_id' => 'required',
        ], $request);

        $note = $request->input('note');
        $off_monitoring_staff_id = $request->input('off_monitoring_staff_id');

        $model = OfflineMonitoringStaff::find($off_monitoring_staff_id);
        $model->note = $note;
        $model->save();
        json_message('ok');
    }

}
