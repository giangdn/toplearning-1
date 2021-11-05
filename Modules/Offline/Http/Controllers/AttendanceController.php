<?php

namespace Modules\Offline\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineReference;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Offline\Exports\AttendaceExport;
use Modules\Offline\Imports\AttendanceImport;
use App\Models\Categories\Area;

class AttendanceController extends Controller
{
    public function index($course_id, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $errors = session()->get('errors');
        \Session::forget('errors');

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $course = OfflineCourse::find($course_id);
        $page_title = $course->name;
        $schedules = OfflineSchedule::where('course_id', '=', $course_id)->orderBy('lesson_date')->orderBy('start_time')->get();
        $schedule = $request->schedule;
        $qrcode_attendance = null;
        if ($schedule>0)
        {
            OfflineSchedule::findOrFail($schedule);
            $qrcode_attendance = json_encode(['course'=>$course_id,'schedule'=>$schedule,'type'=>'attendance']);
        }
        // dd($schedule);
        return view('offline::backend.attendance.index', [
            'page_title' => $page_title,
            'course' => $course,
            'schedules' => $schedules,
            'schedule' => $schedule,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'qrcode_attendance' => $qrcode_attendance,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData($course_id, Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->input('unit');
        $status = $request->input('status');
        $schedule = $request->schedule;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineRegister::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.email',
            'b.code',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name AS parent_name'
        ]);
        $query->from('el_offline_register AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('c.id', '=', $title);
        }

        if (!is_null($status)) {
            $query->where('b.status', '=', $status);
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $query->where('d.id', '=', $unit );
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        $absentList = Absent::get();
        $absentReasonList = AbsentReason::get();
        $disciplineList = Discipline::get();

        $course = OfflineCourse::find($course_id);
        foreach($rows as $row) {
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }

            $attendan = OfflineAttendance::checkExists($row->id, $schedule);
            $item = OfflineReference::checkExists($row->id, $schedule);

            $row->checked = 0;
            $row->percent = 0;
            $row->att_id = 0;
            $row->note = '';
            $row->reference = '';
            $row->download_reference = '';

            $discipline ='<select data-regid="'.$row->id.'" class="form-control discipline" data-placeholder="-- Vi phạm --" '. ($course->lock_course == 0 ? '' : 'disabled') .'>';
            $discipline .='<option>Chọn</option>';
            foreach($disciplineList as $v){
                $discipline .='<option'.($attendan && $attendan->discipline_id == $v->id ? ' selected' : '').' value="'.$v->id.'">'.$v->name.'</option>';
            }
            $discipline .='</select>';
            $row->discipline =  $discipline;

            $absent ='<select data-regid="'.$row->id.'" class="form-control absent" data-placeholder="-- Vắng --" '. ($course->lock_course == 0 ? '' : 'disabled') .'>';
            $absent .='<option>Chọn</option>';
            foreach($absentList as $v){
                $absent .='<option'.($attendan && $attendan->absent_id == $v->id ? ' selected' : '').' value="'.$v->id.'">'.$v->name.'</option>';
            }
            $absent .='</select>';
            $row->absent = $absent;

            $absent_reason ='<select data-regid="'.$row->id.'" class="form-control absent_reason" data-placeholder="-- Lý do vắng --" '. ($course->lock_course == 0 ? '' : 'disabled') .'>';
            $absent_reason .='<option>Chọn</option>';
            foreach($absentReasonList as $v){
                $absent_reason .='<option'.($attendan && $attendan->absent_reason_id == $v->id ? ' selected' : '').' value="'.$v->id.'">'.$v->name.'</option>';
            }
            $absent_reason .='</select>';
            $row->absent_reason = $absent_reason;

            if (isset($attendan['status']) && $attendan['status'] == 1){
                $row->checked = 1;
            }

            if ($attendan) {
                $row->percent = $attendan->percent;
                $row->att_id = $attendan->id;
                $row->note = $attendan->note;
                $row->schedule = $attendan->schedule_id;
               // $row->created_at2 = get_date($attendan->created_at, 'H:i:s d/m/Y');
              //  $row->updated_at2 = get_date($attendan->updated_at, 'H:i:s d/m/Y');
            }
            if($item){
                $row->download_reference = link_download($item->reference);
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveAll($course_id, Request $request) {
        $this->validateRequest([
            'register_id' => 'required|exists:el_offline_register,id',
            'schedule_id' => 'required|exists:el_offline_schedule,id',
        ], $request, OfflineAttendance::getAttributeName());

        $registers = $request->register_id;
        $schedule_id = $request->schedule_id;
        $count_schedule = OfflineSchedule::whereCourseId($course_id)->count();

        foreach ($registers as $key => $register_id){
            $user_id = OfflineRegister::find($register_id)->user_id;

            if(OfflineAttendance::checkExists($register_id, $schedule_id)){
                $model = OfflineAttendance::checkExists($register_id, $schedule_id);
                $model->percent = 100;
                $model->status = 1;
                $model->user_id = $user_id;
                $model->course_id = $course_id;
                $model->save();
            }else{
                $model = new OfflineAttendance();
                $model->register_id = $register_id;
                $model->schedule_id = $schedule_id;
                $model->status = 1;
                $model->percent = 100;
                $model->user_id = $user_id;
                $model->course_id = $course_id;
                $model->save();
            }

            $count_attendance = OfflineAttendance::whereRegisterId($register_id)->count();
            if ($count_attendance == $count_schedule){
                $setting = PromotionCourseSetting::where('course_id', $course_id)
                    ->where('type', 2)
                    ->where('status',1)
                    ->where('code', '=', 'attendance')
                    ->get();
                if ($setting->count() > 0){
                    $register = OfflineRegister::find($register_id);
                    foreach ($setting as $item){
                        $user_point = PromotionUserPoint::firstOrCreate([
                            'user_id' => $register->user_id
                        ], [
                            'point' => 0,
                            'level_id' => 0
                        ]);

                        $percent_attendance = OfflineAttendance::whereRegisterId($register_id)->select(\DB::raw('AVG(percent) AS percent'))->first();
                        if ($item->min_percent <= $percent_attendance->percent && $percent_attendance->percent <= $item->max_percent && $item->point){
                            $user_point->point += $item->point;
                            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $register->user_id);
                            $user_point->update();

                            $this->saveHistoryPromotion($register->user_id, $item->point, $item->course_id, $item->id);
                        }
                    }
                }
            }

        }
        json_message('ok');
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'register_id' => 'required|exists:el_offline_register,id',
            'schedule_id' => 'required|exists:el_offline_schedule,id',
            'status' => 'required|in:0,1',
        ], $request, OfflineAttendance::getAttributeName());

        $register_id = $request->input('register_id');
        $schedule_id = $request->input('schedule_id');
        $status = $request->input('status');
        $user_id = OfflineRegister::find($register_id)->user_id;
        $count_schedule = OfflineSchedule::whereCourseId($course_id)->count();

        if ($status == 1) {
            if(OfflineAttendance::checkExists($register_id, $schedule_id)){
                $model = OfflineAttendance::checkExists($register_id, $schedule_id);
                $model->percent = 100;
                $model->status = 1;
                $model->save();
            }else{
                $model = new OfflineAttendance();
                $model->register_id = $register_id;
                $model->schedule_id = $schedule_id;
                $model->status = 1;
                $model->percent = 100;
                $model->course_id = $course_id;
                $model->user_id = $user_id;
                $model->save();
            }

            $count_attendance = OfflineAttendance::whereRegisterId($register_id)->count();
            if ($count_attendance == $count_schedule){
                $setting = PromotionCourseSetting::query()
                    ->where('course_id', $course_id)
                    ->where('type', 2)
                    ->where('status',1)
                    ->where('code', '=', 'attendance')
                    ->get();
                if ($setting->count() > 0){
                    $register = OfflineRegister::find($register_id);
                    foreach ($setting as $item){
                        $user_point = PromotionUserPoint::firstOrCreate([
                            'user_id' => $register->user_id
                        ], [
                            'point' => 0,
                            'level_id' => 0
                        ]);

                        $percent_attendance = OfflineAttendance::whereRegisterId($register_id)->select(\DB::raw('AVG(percent) AS percent'))->first();
                        if ($item->min_percent <= $percent_attendance->percent && $percent_attendance->percent <= $item->max_percent && $item->point){
                            $history_point = PromotionUserHistory::whereUserId($register->user_id)
                                ->where('course_id', '=', $item->course_id)
                                ->where('type', '=', 2)
                                ->whereIn('promotion_course_setting_id', function ($sub) use ($course_id){
                                    $sub->select(['id'])
                                        ->from('el_promotion_course_setting')
                                        ->where('course_id', $course_id)
                                        ->where('type', 2)
                                        ->where('status',1)
                                        ->where('code', '=', 'attendance')
                                        ->pluck('id')
                                        ->toArray();
                                })
                                ->orderByDesc('created_at')
                                ->first();

                            if ($history_point){
                                $user_point->point = ($user_point->point - $history_point->point) + $item->point;
                            }else{
                                $user_point->point += $item->point;
                            }

                            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $register->user_id);
                            $user_point->update();

                            $this->saveHistoryPromotion($register->user_id, $item->point, $item->course_id, $item->id);
                        }
                    }
                }
            }

            json_message('ok');
        }else{
            $model = OfflineAttendance::checkExists($register_id, $schedule_id);
            $model->percent = null;
            $model->status = 0;
            $model->course_id = $course_id;
            $model->user_id = $user_id;
            $model->save();
            json_message('ok');
        }
    }

    public function savePercent($course_id, Request $request) {
        $this->validateRequest([
            'percent' => 'required',
            'regid' => 'required',
        ], $request);

        $percent = $request->input('percent');
        $register_id = $request->input('regid');
        $schedule_id = $request->schedule;
        $user_id = OfflineRegister::find($register_id)->user_id;

        $model = OfflineAttendance::checkExists($register_id, $schedule_id);
        $model->percent = $percent;
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->save();

        $count_schedule = OfflineSchedule::whereCourseId($course_id)->count();
        $count_attendance = OfflineAttendance::whereRegisterId($register_id)->count();
        if ($count_attendance == $count_schedule){
            $setting = PromotionCourseSetting::where('course_id', $course_id)
                ->where('type', 2)
                ->where('status',1)
                ->where('code', '=', 'attendance')
                ->get();
            if ($setting->count() > 0){
                $register = OfflineRegister::find($register_id);
                foreach ($setting as $item){
                    $user_point = PromotionUserPoint::firstOrCreate([
                        'user_id' => $register->user_id
                    ], [
                        'point' => 0,
                        'level_id' => 0
                    ]);

                    $percent_attendance = OfflineAttendance::whereRegisterId($register_id)->select(\DB::raw('AVG(percent) AS percent'))->first();
                    if ($item->min_percent <= $percent_attendance->percent && $percent_attendance->percent <= $item->max_percent && $item->point){
                        $user_point->point += $item->point;
                        $user_point->level_id = PromotionLevel::levelUp($user_point->point, $register->user_id);
                        $user_point->update();

                        $this->saveHistoryPromotion($register->user_id, $item->point, $item->course_id, $item->id);
                    }
                }
            }
        }

        json_message('ok');
    }

    public function saveAbsent($course_id, Request $request) {
        $this->validateRequest([
            'absent_id' => 'required',
            'regid' => 'required',
        ], $request);

        $absent_id = $request->input('absent_id');
        $register_id = $request->input('regid');
        $schedule_id = $request->schedule;
        $user_id = OfflineRegister::find($register_id)->user_id;

        $model = OfflineAttendance::checkExists($register_id, $schedule_id);
        if ($model){
            $model->absent_id = $absent_id;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();
        }else
        {
            $model = new OfflineAttendance();
            $model->register_id = $register_id;
            $model->schedule_id = $schedule_id;
            $model->status = 0;
            $model->percent = 0;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->absent_id = $absent_id;
            $model->save();
        }

        json_message('ok');
    }

    public function saveAbsentReason($course_id, Request $request) {
        $this->validateRequest([
            'absent_reason_id' => 'required',
            'regid' => 'required',
        ], $request);

        $absent_reason_id = $request->input('absent_reason_id');
        $register_id = $request->input('regid');
        $schedule_id = $request->schedule;
        $user_id = OfflineRegister::find($register_id)->user_id;

        $model = OfflineAttendance::checkExists($register_id, $schedule_id);
        if ($model){
            $model->absent_reason_id = $absent_reason_id;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();
        }else
        {
            $model = new OfflineAttendance();
            $model->register_id = $register_id;
            $model->schedule_id = $schedule_id;
            $model->status = 0;
            $model->percent = 0;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->absent_reason_id = $absent_reason_id;
            $model->save();
        }

        json_message('ok');
    }

    public function saveDiscipline($course_id, Request $request) {
        $this->validateRequest([
            'discipline_id' => 'required',
            'regid' => 'required',
        ], $request);

        $discipline_id = $request->input('discipline_id');
        $register_id = $request->input('regid');
        $schedule_id = $request->schedule;
        $user_id = OfflineRegister::find($register_id)->user_id;
        $model = OfflineAttendance::checkExists($register_id, $schedule_id);
        if ($model){
            $model->discipline_id = $discipline_id;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();
        }else
        {
            $model = new OfflineAttendance();
            $model->register_id = $register_id;
            $model->schedule_id = $schedule_id;
            $model->status = 0;
            $model->percent = 0;
            $model->discipline_id = $discipline_id;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();
        }


        json_message('ok');
    }

    public function saveNote($course_id, Request $request) {
        $this->validateRequest([
            'note' => 'nullable',
            'regid' => 'required',
        ], $request);

        $note = $request->input('note');
        $register_id = $request->input('regid');
        $schedule_id = $request->schedule;
        $user_id = OfflineRegister::find($register_id)->user_id;
        if($schedule_id != null){

            if(OfflineAttendance::checkExists($register_id, $schedule_id)){
                $model = OfflineAttendance::checkExists($register_id, $schedule_id);
                $model->note = $note;
                $model->user_id = $user_id;
                $model->course_id = $course_id;
                $model->save();
                json_message('ok');
            }
            $model = new OfflineAttendance();
            $model->register_id = $register_id;
            $model->schedule_id = $schedule_id;
            $model->note = $note;
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();

            json_message('ok');
        }
        json_result([
            'status' => 'error',
            'message' => 'Chưa chọn buổi học',
        ]);
    }

    public function getModalReference($course_id, Request $request) {
        $this->validateRequest([
            'regid' => 'required',
        ], $request);

        $model = OfflineReference::checkExists($request->regid, $request->schedule);

        return view('offline::modal.reference', [
            'model' => $model,
            'regid' => $request->regid,
            'course_id' => $course_id,
            'schedule' => $request->schedule,
        ]);
    }

    public function saveReference($course_id, Request $request){
        $this->validateRequest([
            'regid' => 'required',
            'schedule' => 'required',
            'reference' => 'required|string',
        ], $request, [
            'schedule' => 'Buổi học',
            'reference' => 'Đơn xin phép',
        ]);

        $register_id = $request->regid;
        $schedule_id = $request->schedule;
        $user_id = OfflineRegister::find($register_id)->user_id;
        $model = OfflineReference::checkExists($register_id, $schedule_id);

        if($model){
            $model->reference = path_upload($request->reference);
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();
        }else{
            $model = OfflineReference::firstOrNew(['id' => $request->id]);
            $model->register_id = $register_id;
            $model->schedule_id = $schedule_id;
            $model->reference = path_upload($request->reference);
            $model->user_id = $user_id;
            $model->course_id = $course_id;
            $model->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Thêm thành công',
        ]);
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 2;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = OfflineCourse::query()->find($course_id)->name;

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
    public function export($course_id, $schedule) {
        return (new AttendaceExport($course_id, $schedule))->download('danh_sach_diem_danh_'. date('d_m_Y') .'.xlsx');
    }

    public function import($course_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new AttendanceImport($course_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.offline.attendance', ['id' => $course_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => $redirect,
        ]);
    }
}

