<?php

namespace Modules\Online\Http\Controllers\Frontend;

use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Permission;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineRegister;
use Modules\VirtualClassroom\Entities\VirtualClassroomTeacher;


class ClassroomController extends Controller
{
    public function index() {
        return view('online::frontend.teacher');
    }

    public function getDataVirtualClassroom(Request $request) {
        $search = $request->get('q');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $profile = Profile::find(Auth::id());
        $unit_user = Unit::getTreeParentUnit($profile->unit_code);

        $traning_teacher = TrainingTeacher::where('user_id', $profile->user_id)->first();

        $query = OnlineCourse::query();
        $query->select([
            'course.id',
            'course.code as course_code',
            'course.name as course_name',
            'bbb.code as bbb_code',
            'bbb.name as bbb_name',
            'bbb.start_date',
            'bbb.end_date',
            'bbb.id as bbb_id',
            'course_activity.id as activity_id',
            'course_activity.lesson_id',
        ]);
        $query->from('el_online_course as course');
        $query->leftJoin('el_online_course_activity as course_activity', 'course_activity.course_id', '=', 'course.id');
        $query->leftJoin('el_virtual_classroom as bbb', 'bbb.id', '=', 'course_activity.subject_id');
        $query->where('course_activity.activity_id', '=', 6);
        $query->where('course.status', '=', 1);
        $query->where('course.isopen', '=', 1);

        if (!Permission::isAdmin()){
            if ($traning_teacher){
                $query->whereIn('bbb.id', function ($sub) use ($traning_teacher){
                    $sub->select(['virtual_classroom_id'])
                        ->from('el_virtual_classroom_teacher')
                        ->where('teacher_id', '=', $traning_teacher->id);
                });
            }

            if (Permission::isUnitManager()){
                $query->whereNull('course.unit_id');
                foreach ($unit_user as $item){
                    $query->orWhere('course.unit_id', 'like', '%'.$item->id.'%');
                }
            }
        }


        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('course.code', 'like', '%'. $search .'%');
                $subquery->orWhere('course.name', 'like', '%'. $search .'%');
            });
        }

        if ($fromdate) {
            $query->where('bbb.start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('bbb.start_date', '<=', date_convert($todate, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if ($row->start_date > date('Y-m-d H:i:s')){
                $row->status = 'Chưa tới giờ';
            }elseif ($row->start_date <= date('Y-m-d H:i:s') && $row->end_date > date('Y-m-d H:i:s')){
                $row->status = 'Đang diễn ra';
            }elseif ($row->end_date < date('Y-m-d H:i:s')){
                $row->status = 'Đã kết thúc';
            }
            $row->url_bbb = '';
            $register = OnlineRegister::checkExists(Auth::id(), $row->id);
            if ($row->start_date <= date('Y-m-d H:i:s') && $row->end_date > date('Y-m-d H:i:s')){
                if ($register || $traning_teacher){
                    $row->url_bbb = route('module.online.goactivity', ['id' => $row->id, 'aid' => $row->activity_id, 'lesson' => $row->lesson_id]);
                }
            }

            $count_register = OnlineRegister::countRegisters($row->id);
            $count_joined = OnlineCourseActivityHistory::query()
                ->select(['a.user_id'])
                ->from('el_online_course_activity_history as a')
                ->leftJoin('el_online_register as b', 'b.user_id', '=', 'a.user_id')
                ->where('a.course_id', '=', $row->id)
                ->where('b.course_id', '=', $row->id)
                ->where('a.activity_id', '=', 6)
                ->groupBy(['a.user_id'])
                ->get()->count();

            $row->quantity = $count_joined .'/'. $count_register;

            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
            $row->quantity_url = route('module.online.get_list_user', ['course_id' => $row->id]);

            $teacher = VirtualClassroomTeacher::query()
                ->select([
                    'b.name',
                    'b.code',
                ])
                ->from('el_virtual_classroom_teacher as a')
                ->leftJoin('el_training_teacher as b', 'b.id', '=', 'a.teacher_id')
                ->where('a.virtual_classroom_id', '=', $row->bbb_id)
                ->first();
            $first_time_join = OnlineCourseActivityHistory::query()
                ->select(['created_at as time_join'])
                ->from('el_online_course_activity_history')
                ->where('course_id', '=', $row->id)
                ->where('activity_id', '=', 6)
                ->whereIn('user_id', function ($sub){
                    $sub->select(['user_id'])
                        ->from('el_training_teacher')
                        ->where('status', '=', 1);
                })
                ->orderBy('created_at')->first();

            $last_time_join = OnlineCourseActivityHistory::query()
                ->select(['created_at as time_join'])
                ->from('el_online_course_activity_history')
                ->where('course_id', '=', $row->id)
                ->where('activity_id', '=', 6)
                ->whereIn('user_id', function ($sub){
                    $sub->select(['user_id'])
                        ->from('el_training_teacher')
                        ->where('status', '=', 1);
                })
                ->orderByDesc('created_at')->first();

            $row->teacher = $teacher->name .' ('. $teacher->code .')';
            $row->first_time_join = $first_time_join ?  get_date($first_time_join->time_join, 'H:i d/m/Y') : '';
            $row->last_time_join = $last_time_join ? get_date($last_time_join->time_join, 'H:i d/m/Y') : '';
        }

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function getListUser($course_id){
        $get_user_joined = OnlineCourseActivityHistory::query()
            ->select([
                'a.created_at as time_join',
                'c.firstname',
                'c.lastname',
                'c.code as user_code',
                'e.name as course_name',
                'e.code as course_code',
                'd.name as bbb_name',
                'd.code as bbb_code',
                'd.start_date',
                'd.end_date',
            ])
            ->from('el_online_course_activity_history as a')
            ->leftJoin('el_online_register as b', 'b.user_id', '=', 'a.user_id')
            ->leftJoin('el_profile as c', 'c.user_id', '=', 'a.user_id')
            ->leftJoin('el_virtual_classroom as d', 'd.course_id', '=', 'a.course_id')
            ->leftJoin('el_online_course as e', 'e.id', '=', 'a.course_id')
            ->where('a.course_id', '=', $course_id)
            ->where('b.course_id', '=', $course_id)
            ->where('a.activity_id', '=', 6)
            ->get();

        return view('online::modal.list_user_join_bbb', [
            'get_user_joined' => $get_user_joined
        ]);
    }
}
