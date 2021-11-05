<?php

namespace Modules\DashboardUnit\Http\Controllers;

use App\CourseView;
use App\Models\Categories\Area;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\DashboardUnit\Entities\DashboardUnitByCourse;
use Modules\DashboardUnit\Entities\DashboardUnitByQuiz;
use Modules\DashboardUnit\Exports\ExportDashboardUserCourseEmployee;
use Modules\DashboardUnit\Exports\ExportDashboardUserQuiz;
use Modules\DashboardUnit\Exports\ExportDashboardUserTrainingForm;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizPart;
use Modules\DashboardUnit\Exports\ExportDashboardTrainingForm;
use Modules\DashboardUnit\Exports\ExportDashboardCourseEmployee;
use Modules\DashboardUnit\Exports\ExportDashboardQuiz;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineResult;

class DashboardUnitController extends Controller
{
    public function index(Request $request)
    {
        OnlineCourse::addGlobalScope(new CompanyScope());
        OfflineCourse::addGlobalScope(new CompanyScope());
        CourseView::addGlobalScope(new CompanyScope());
        QuizPart::addGlobalScope(new CompanyScope());

        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);
        $unit_type = UnitType::get();
        // dd($request->unit);
        $max_unit_level = Unit::where(function ($sub) use ($unit_user, $child_arr){
            $sub->orWhere('id', '=', @$unit_user->id);
            $sub->orWhereIn('id', $child_arr);
        })->max('level');

        $count_offline_by_course = $this->countOfflineByCourse($unit_user, $child_arr, $request);
        $count_online_by_course = $this->countOnlineByCourse($unit_user, $child_arr, $request);
        $count_user_by_online_course = $this->countUserByOnlineCourse($unit_user, $child_arr, $request);
        $count_user_by_offline_course = $this->countUserByOfflineCourse($unit_user, $child_arr, $request);
        $count_part_by_quiz = $this->countPartByQuiz($unit_user, $child_arr, $request);
        $count_user_by_quiz = $this->countUserByQuiz($unit_user, $child_arr, $request);

        $lineChartCourseByTrainingForm = $this->lineChartCourseByTrainingForm($unit_user, $child_arr, $request);
        $pieChartCourseByTrainingForm = $this->pieChartCourseByTrainingForm($unit_user, $child_arr, $request);

        $lineChartUserByTrainingForm = $this->lineChartUserByTrainingForm($unit_user, $child_arr, $request);
        $pieChartUserByTrainingForm = $this->pieChartUserByTrainingForm($unit_user, $child_arr, $request);

        $lineChartCourseByCourseEmployee = $this->lineChartCourseByCourseEmployee($unit_user, $child_arr, $request);
        $pieChartCourseByCourseEmployee = $this->pieChartCourseByCourseEmployee($unit_user, $child_arr, $request);

        $lineChartUserByCourseEmployee = $this->lineChartUserByCourseEmployee($unit_user, $child_arr, $request);
        $pieChartUserByCourseEmployee = $this->pieChartUserByCourseEmployee($unit_user, $child_arr, $request);

        $lineChartPartByQuizType = $this->lineChartPartByQuizType($unit_user, $child_arr, $request);
        $pieChartPartByQuizType = $this->pieChartPartByQuizType($unit_user, $child_arr, $request);

        $lineChartUserByQuizType = $this->lineChartUserByQuizType($unit_user, $child_arr, $request);
        $pieChartUserByQuizType = $this->pieChartUserByQuizType($unit_user, $child_arr, $request);

        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };
        $level_name_unit = function ($level) {
            return Unit::getLevelName($level);
        };

        $unit_request = $request->unit ? Unit::whereIn('id', explode(';', $request->unit))->latest('id')->first() : '';
        $list_unit_request = $unit_request ? Unit::getTreeParentUnit($unit_request->code) : [];
        $unit_type_request = $request->unit_type;
        $start_date_request = $request->start_date;
        $end_date_request = $request->end_date;
        $area_request = $request->area ? Area::whereIn('id', explode(';', $request->area))->latest('id')->first() : '';
        $list_area_request = $area_request ? Area::getTreeParentArea($area_request->code) : [];

        return view('dashboardunit::index', [
            'level_name_area' => $level_name_area,
            'level_name_unit' => $level_name_unit,
            'unit_user' => $unit_user,
            'max_unit_level' => $max_unit_level,
            'unit_type' => $unit_type,

            'list_unit_request' => $list_unit_request,
            'unit_type_request' => $unit_type_request,
            'start_date_request' => $start_date_request,
            'end_date_request' => $end_date_request,
            'list_area_request' => $list_area_request,

            'count_online_by_course' => $count_online_by_course,
            'count_offline_by_course' => $count_offline_by_course,
            'count_user_by_online_course' => $count_user_by_online_course,
            'count_user_by_offline_course' => $count_user_by_offline_course,
            'count_part_by_quiz' => $count_part_by_quiz,
            'count_user_by_quiz' => $count_user_by_quiz,

            'lineChartCourseByTrainingForm' => $lineChartCourseByTrainingForm,
            'pieChartCourseByTrainingForm' => $pieChartCourseByTrainingForm,

            'lineChartUserByTrainingForm' => $lineChartUserByTrainingForm,
            'pieChartUserByTrainingForm' => $pieChartUserByTrainingForm,

            'lineChartCourseByCourseEmployee' => $lineChartCourseByCourseEmployee,
            'pieChartCourseByCourseEmployee' => $pieChartCourseByCourseEmployee,

            'lineChartUserByCourseEmployee' => $lineChartUserByCourseEmployee,
            'pieChartUserByCourseEmployee' => $pieChartUserByCourseEmployee,

            'lineChartPartByQuizType' => $lineChartPartByQuizType,
            'pieChartPartByQuizType' => $pieChartPartByQuizType,

            'lineChartUserByQuizType' => $lineChartUserByQuizType,
            'pieChartUserByQuizType' => $pieChartUserByQuizType,
        ]);
    }

    private function countOnlineByCourse($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $count_online_by_course = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $query = OnlineCourse::query();
            $query->select(['el_online_course.id']);
            $query->where('el_online_course.status', '=', 1);
            $query->where('el_online_course.isopen', '=', 1);
            $query->where('el_online_course.start_date','<=', $last_month)
                ->where(function ($sub) use ($first_month, $last_month){
                    $sub->where('el_online_course.end_date','>=', $last_month);
                    $sub->orwhere('el_online_course.end_date', '>=', $first_month);
                    $sub->orWhereNull('el_online_course.end_date');
                });

            if ($unit || $unit_type || $area) {
                $query->leftjoin('el_online_register_view as b', 'b.course_id', '=', 'el_online_course.id');
            }

            if ($unit) {
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($unit_type) {
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('b.unit_id', $unit_by_type);
            }
            if ($area) {
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as c', 'c.id', '=', 'b.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('c.area_id', $area_id);
                    $sub_query->orWhere('c.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date) {
                $start_date = date_convert($request->start_date);
                $query->where('el_online_course.start_date', '>=', $start_date);
            }
            if ($request->end_date) {
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_online_course.start_date', '<=', $end_date);
            }

            $query->groupBy(['el_online_course.id']);
            $online_by_course = $query->get();
            $count_online_by_course += $online_by_course->count();
        }

        return $count_online_by_course;
    }

    private function countOfflineByCourse($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $count_offline_by_course = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $query = OfflineCourse::query();
            $query->select(['el_offline_course.id']);
            $query->where('el_offline_course.status', '=', 1);
            $query->where('el_offline_course.isopen', '=', 1);
            $query->where('el_offline_course.start_date','<=', $last_month)
                ->where(function ($sub) use ($first_month, $last_month){
                    $sub->where('el_offline_course.end_date','>=', $last_month);
                    $sub->orwhere('el_offline_course.end_date', '>=', $first_month);
                    $sub->orWhereNull('el_offline_course.end_date');
                });

            if ($unit || $unit_type || $area) {
                $query->leftjoin('el_offline_register_view as b', 'b.course_id', '=', 'el_offline_course.id');
            }

            if ($unit) {
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($unit_type) {
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('b.unit_id', $unit_by_type);
            }
            if ($area) {
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as c', 'c.id', '=', 'b.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('c.area_id', $area_id);
                    $sub_query->orWhere('c.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date) {
                $start_date = date_convert($request->start_date);
                $query->where('el_offline_course.start_date', '>=', $start_date);
            }
            if ($request->end_date) {
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_offline_course.end_date', '<=', $end_date);
            }
            $query->groupBy(['el_offline_course.id']);
            $offline_by_course = $query->get();
            $count_offline_by_course += $offline_by_course->count();
        }
        return $count_offline_by_course;
    }

    private function countUserByOnlineCourse($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $prefix = \DB::getTablePrefix();

        $count_user_by_course = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $query = CourseView::query();
            $query->join('el_course_register_view as b', function ($sub) {
                $sub->on('b.course_id', '=', 'el_course_view.course_id');
                $sub->on('b.course_type', '=', 'el_course_view.course_type');
                $sub->where('b.course_type', '=', 1);
            });
            $query->where('el_course_view.status', '=', 1);
            $query->where('el_course_view.isopen', '=', 1);
            $query->where('el_course_view.course_type', '=', 1);
            $query->whereExists(function ($sub) use ($prefix, $i) {
                $sub->select(['id'])
                    ->from('el_online_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id')
                    ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
            });
            $query->where('el_course_view.start_date','<=', $last_month)
                ->where(function ($sub) use ($first_month, $last_month){
                    $sub->where('el_course_view.end_date','>=', $last_month);
                    $sub->orwhere('el_course_view.end_date', '>=', $first_month);
                    $sub->orWhereNull('el_course_view.end_date');
                });

            if ($unit) {
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($unit_type) {
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('b.unit_id', $unit_by_type);
            }
            if ($area) {
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as c', 'c.id', '=', 'b.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('c.area_id', $area_id);
                    $sub_query->orWhere('c.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date) {
                $start_date = date_convert($request->start_date);
                $query->where('el_course_view.start_date', '>=', $start_date);
            }
            if ($request->end_date) {
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_course_view.end_date', '<=', $end_date);
            }

            $online_by_course = $query->get();
            $count_user_by_course += $online_by_course->count();
        }

        return $count_user_by_course;
    }

    private function countUserByOfflineCourse($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $prefix = \DB::getTablePrefix();

        $count_user_by_course = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $query = CourseView::query();
            $query->join('el_course_register_view as b', function ($sub) {
                $sub->on('b.course_id', '=', 'el_course_view.course_id');
                $sub->on('b.course_type', '=', 'el_course_view.course_type');
                $sub->where('b.course_type', '=', 2);
            });
            $query->where('el_course_view.status', '=', 1);
            $query->where('el_course_view.isopen', '=', 1);
            $query->where('el_course_view.course_type', '=', 2);
            $query->whereExists(function ($sub) {
                $sub->select(['id'])
                    ->from('el_offline_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id');
            });
            $query->where('el_course_view.start_date','<=', $last_month)
                ->where(function ($sub) use ($first_month, $last_month){
                    $sub->where('el_course_view.end_date','>=', $last_month);
                    $sub->orwhere('el_course_view.end_date', '>=', $first_month);
                    $sub->orWhereNull('el_course_view.end_date');
                });

            if ($unit) {
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($unit_type) {
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('b.unit_id', $unit_by_type);
            }
            if ($area) {
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as c', 'c.id', '=', 'b.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('c.area_id', $area_id);
                    $sub_query->orWhere('c.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date) {
                $start_date = date_convert($request->start_date);
                $query->where('el_course_view.start_date', '>=', $start_date);
            }
            if ($request->end_date) {
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_course_view.end_date', '<=', $end_date);
            }
            $offline_by_course = $query->get();
            $count_user_by_course += $offline_by_course->count();
        }

        return $count_user_by_course;
    }

    private function countPartByQuiz($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $prefix = \DB::getTablePrefix();

        $count_part_by_quiz = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $query = QuizPart::query();
            $query->select(['el_quiz_part.id']);
            $query->leftjoin('el_quiz_register as b', 'b.part_id', '=', 'el_quiz_part.id');
            $query->where('b.type', 1);
            $query->whereIn('el_quiz_part.quiz_id', function ($sub2){
                $sub2->select(['id'])
                    ->from('el_quiz')
                    ->where('status', '=', 1)
                    ->where('is_open', '=', 1)
                    ->pluck('id')->toArray();
            });
            $query->where(\DB::raw('month('.$prefix.'el_quiz_part.start_date)'), '<=', $i)
                ->where(function ($sub) use ($prefix, $i){
                    $sub->orWhereNull('el_quiz_part.end_date');
                    $sub->orWhere(\DB::raw('month('.$prefix.'el_quiz_part.end_date)'), '>=', $i);
                });

            if ($unit || $unit_type || $area){
                $query->leftjoin('el_profile_view as c', 'c.user_id', '=', 'b.user_id');
            }

            if ($unit) {
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('c.unit_id', $unit_id);
                    $sub_query->orWhere('c.unit_id', '=', $units->id);
                });
            }
            if ($unit_type) {
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('c.unit_id', $unit_by_type);
            }
            if ($area) {
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as d', 'd.id', '=', 'c.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('d.area_id', $area_id);
                    $sub_query->orWhere('d.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date) {
                $start_date = date_convert($request->start_date);
                $query->where('el_quiz_part.start_date', '>=', $start_date);
            }
            if ($request->end_date) {
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_quiz_part.end_date', '<=', $end_date);
            }
            $query->groupBy(['el_quiz_part.id']);
            $quiz_part_by_course = $query->get();
            $count_part_by_quiz += $quiz_part_by_course->count();
        }
        return $count_part_by_quiz;
    }

    private function countUserByQuiz($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $prefix = \DB::getTablePrefix();

        $count_user_by_quiz = 0;
        for ($i = 1; $i <= 12; $i++) {
            $i = ($i < 10) ? '0' . $i : $i;

            $query = QuizPart::query();
            $query->leftJoin('el_quiz_register as b', 'b.part_id', '=', 'el_quiz_part.id');
            $query->where('b.type', '=', 1);
            $query->whereIn('el_quiz_part.quiz_id', function ($sub2){
                $sub2->select(['id'])
                    ->from('el_quiz')
                    ->where('status', '=', 1)
                    ->where('is_open', '=', 1)
                    ->pluck('id')->toArray();
            });
            $query->whereExists(function ($sub) use ($prefix, $i) {
                $sub->select(['id'])
                    ->from('el_quiz_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.quiz_id', '=', 'b.quiz_id')
                    ->where('result.type', '=', 1)
                    ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
            });
            /*$query->where(\DB::raw('month('.$prefix.'el_quiz_part.start_date)'), '<=', $i)
                ->where(function ($sub) use ($prefix, $i){
                    $sub->orWhereNull('el_quiz_part.end_date');
                    $sub->orWhere(\DB::raw('month('.$prefix.'el_quiz_part.end_date)'), '>=', $i);
                });*/

            if ($unit || $unit_type || $area){
                $query->leftjoin('el_profile_view as c', 'c.user_id', '=', 'b.user_id');
            }

            if ($unit) {
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('c.unit_id', $unit_id);
                    $sub_query->orWhere('c.unit_id', '=', $units->id);
                });
            }
            if ($unit_type) {
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('c.unit_id', $unit_by_type);
            }
            if ($area) {
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as d', 'd.id', '=', 'c.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('d.area_id', $area_id);
                    $sub_query->orWhere('d.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date) {
                $start_date = date_convert($request->start_date);
                $query->where('el_quiz_part.start_date', '>=', $start_date);
            }
            if ($request->end_date) {
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_quiz_part.end_date', '<=', $end_date);
            }
//dd($query->toSql());
            $count_user_by_quiz += $query->get()->count();
        }

        return $count_user_by_quiz;
    }

    // Thống kê số lớp theo loại hình đào tạo
    private function lineChartCourseByTrainingForm($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;
        $prefix = \DB::getTablePrefix();
        $year = date('Y');
        $result = [];

        $head = [];
        $head[] = 'Tháng';

        $training_form_arr = [];
        $training_form = TrainingForm::get(['id', 'name']);
        foreach ($training_form as $item){
            $training_form_arr[$item->id] = $item->name;
        }
        foreach ($training_form_arr as $key => $item) {
            $head[] = $item;
        }
        $head[] = 'Tổng';
        $result[] = $head;
        for ($i = 1; $i <= 12; $i++){
            $content = [];
            $i = ($i < 10) ? '0'.$i : $i;
            $content[] = (string) $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $total = 0;
            foreach ($training_form_arr as $key => $item) {
                $query = CourseView::query();
                $query->select(['el_course_view.course_id', 'el_course_view.course_type']);
                $query->where('el_course_view.training_form_id', $key);
                $query->where('el_course_view.status', '=', 1);
                $query->where('el_course_view.isopen', '=', 1);
                $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $i)
                    ->where(function ($sub) use ($prefix, $i){
                        $sub->orWhereNull('el_course_view.end_date');
                        $sub->orWhere(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $i);
                    });
                /*$query->where('a.start_date','<=', $last_month)
                    ->where(function ($sub) use ($first_month, $last_month){
                        $sub->where('a.end_date','>=', $last_month);
                        $sub->orwhere('a.end_date', '>=', $first_month);
                    });*/
                if ($unit || $unit_type || $area){
                    $query->leftjoin('el_course_register_view as b', function ($sub){
                        $sub->on('b.course_id','=','el_course_view.course_id');
                        $sub->on('b.course_type','=','el_course_view.course_type');
                    });
                }

                if ($unit){
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);

                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('b.unit_id', $unit_id);
                        $sub_query->orWhere('b.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type){
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('b.unit_id', $unit_by_type);
                }
                if ($area){
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('c.area_id', $area_id);
                        $sub_query->orWhere('c.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date){
                    $start_date = date_convert($request->start_date);
                    $query->where('el_course_view.start_date', '>=', $start_date);
                }
                if ($request->end_date){
                    $end_date = date_convert($request->end_date, '23:59:59');
                    $query->where('el_course_view.start_date', '<=', $end_date);
                }

                $query->groupBy(['el_course_view.course_id', 'el_course_view.course_type']);
                $list_course = $query->get();
                $course_by_units = $list_course->count();

                $content[] = (int) $course_by_units;
                $total += (int) $course_by_units;
            }
            $content[] = $total;

            $result[] = $content;
        }

        return [
            'content' => $result,
        ];
    }
    private function pieChartCourseByTrainingForm($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;
        $prefix = \DB::getTablePrefix();

        $result = [];
        $result[] = [
            'Hình thức',
            'Số lượng'
        ];

        $training_form_arr = [];
        $training_form = TrainingForm::get(['id', 'name']);
        foreach ($training_form as $item){
            $training_form_arr[$item->id] = $item->name;
        }

        foreach ($training_form_arr as $key => $item) {
            $content = [];
            $content[] = $item;

            $course_by_units = 0;
            for ($i = 1; $i <= 12; $i++) {
                $i = ($i < 10) ? '0' . $i : $i;

                $query = CourseView::query();
                $query->select(['el_course_view.course_id', 'el_course_view.course_type']);
                $query->where('el_course_view.training_form_id', $key);
                $query->where('el_course_view.status', '=', 1);
                $query->where('el_course_view.isopen', '=', 1);
                $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $i)
                    ->where(function ($sub) use ($prefix, $i){
                        $sub->orWhereNull('el_course_view.end_date');
                        $sub->orWhere(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $i);
                    });

                if ($unit || $unit_type || $area) {
                    $query->leftjoin('el_course_register_view as b', function ($sub) {
                        $sub->on('b.course_id', '=', 'el_course_view.course_id');
                        $sub->on('b.course_type', '=', 'el_course_view.course_type');
                    });
                }

                if ($unit) {
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);

                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('b.unit_id', $unit_id);
                        $sub_query->orWhere('b.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type) {
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('b.unit_id', $unit_by_type);
                }
                if ($area) {
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as c', 'c.id', '=', 'b.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('c.area_id', $area_id);
                        $sub_query->orWhere('c.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date) {
                    $start_date = date_convert($request->start_date);
                    $query->where('el_course_view.start_date', '>=', $start_date);
                }
                if ($request->end_date) {
                    $end_date = date_convert($request->end_date, '23:59:59');
                    $query->where('el_course_view.start_date', '<=', $end_date);
                }

                $query->groupBy(['el_course_view.course_id', 'el_course_view.course_type']);
                $list_course = $query->get();
                $course_by_units += $list_course->count();
            }

            $content[] = (int) $course_by_units;

            $result[] = $content;
        }
        //dd($result);
        return $result;
    }

    //Thống kê lượt CBNV theo hình thức đào tạo
    private function lineChartUserByTrainingForm($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;
        $prefix = \DB::getTablePrefix();
        $year = date('Y');
        $result = [];

        $head = [];
        $head[] = 'Tháng';

        $training_form_arr = [];
        $training_form = TrainingForm::get(['id', 'name']);
        foreach ($training_form as $item){
            $training_form_arr[$item->id] = $item->name;
        }

        foreach ($training_form_arr as $key => $item) {
            $head[] = $item;
        }
        $head[] = 'Tổng';
        $result[] = $head;

//        $online_result = OnlineResult::query()
//            ->select(['id','user_id', 'course_id', DB::raw('1 as course_type') ,'updated_at as updated_at2']);
//        $course_result = OfflineResult::query()
//            ->select(['id','user_id', 'course_id',  DB::raw('2 as course_type') ,'updated_at as updated_at2']);
//        $course_result->union($online_result);
//        $querySql = $course_result->toSql();
//
//        $course_result = DB::table(DB::raw("($querySql) as a"))->mergeBindings($course_result->getQuery());

        for ($i = 1; $i <= 12; $i++){
            $content = [];
            $i = ($i < 10) ? '0'.$i : $i;
            $content[] = (string) $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $total = 0;
            foreach ($training_form_arr as $key => $item) {
                $query = CourseView::query();
                $query->join('el_course_register_view as b', function ($sub){
                    $sub->on('b.course_id','=','el_course_view.course_id');
                    $sub->on('b.course_type','=','el_course_view.course_type');
                });
                $query->where('el_course_view.training_form_id', $key);
                $query->where('el_course_view.status', '=', 1);
                $query->where('el_course_view.isopen', '=', 1);
                $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $i)
                    ->where(function ($sub) use ($prefix, $i){
                        $sub->orWhereNull('el_course_view.end_date');
                        $sub->orWhere(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $i);
                    });
                $query->where(function ($sub) use ($prefix, $i){
                    $sub->orWhereExists(function ($sub1) use ($prefix, $i){
                        $sub1->select(['id'])
                            ->from('el_online_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.course_id', '=', 'b.course_id')
                            ->whereColumn(DB::raw(1), '=', 'b.course_type')
                            ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
                    });
                    $sub->orWhereExists(function ($sub2){
                        $sub2->select(['id'])
                            ->from('el_offline_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.course_id', '=', 'b.course_id')
                            ->whereColumn(DB::raw(2), '=', 'b.course_type');
                    });
                });

                /*$query->where('a.start_date','<=', $last_month)
                    ->where(function ($sub) use ($first_month, $last_month){
                        $sub->where('a.end_date','>=', $last_month);
                        $sub->orwhere('a.end_date', '>=', $first_month);
                    });*/

                if ($unit){
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);
                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('b.unit_id', $unit_id);
                        $sub_query->orWhere('b.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type){
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('b.unit_id', $unit_by_type);
                }
                if ($area){
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('c.area_id', $area_id);
                        $sub_query->orWhere('c.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date){
                    $start_date = date_convert($request->start_date);
                    $query->where('el_course_view.start_date', '>=', $start_date);
                }
                if ($request->end_date){
                    $end_date = date_convert($request->end_date, '23:59:59');
                    $query->where('el_course_view.start_date', '<=', $end_date);
                }

                $list_course = $query->get();
                $course_user_training_form = $list_course->count();

                $content[] = (int) $course_user_training_form;
                $total += (int) $course_user_training_form;
            }
            $content[] = $total;

            $result[] = $content;
        }
        return [
            'head' => $head,
            'content' => $result,
        ];
    }
    private function pieChartUserByTrainingForm($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;
        $prefix = \DB::getTablePrefix();
        $result = [];
        $result[] = [
            'Hình thức',
            'Số lượng'
        ];

        $training_form_arr = [];
        $training_form = TrainingForm::get(['id', 'name']);
        foreach ($training_form as $item){
            $training_form_arr[$item->id] = $item->name;
        }

//        $online_result = OnlineResult::query()
//            ->select(['id','user_id', 'course_id', DB::raw('1 as course_type') ,'updated_at']);
//        $course_result = OfflineResult::query()
//            ->select(['id','user_id', 'course_id',  DB::raw('2 as course_type') ,'updated_at']);
//        $course_result->union($online_result);
//        $querySql = $course_result->toSql();
//
//        $course_result = DB::table(DB::raw("($querySql) as a"))->mergeBindings($course_result->getQuery());

        foreach ($training_form_arr as $key => $item) {
            $content = [];
            $content[] = $item;

            $course_by_units = 0;
            for ($i = 1; $i <= 12; $i++) {
                $i = ($i < 10) ? '0' . $i : $i;
                $query = CourseView::query();
                $query->join('el_course_register_view as b', function ($sub) {
                    $sub->on('b.course_id', '=', 'el_course_view.course_id');
                    $sub->on('b.course_type', '=', 'el_course_view.course_type');
                });
                $query->where('el_course_view.training_form_id', $key);
                $query->where('el_course_view.status', '=', 1);
                $query->where('el_course_view.isopen', '=', 1);
                $query->where(\DB::raw('month(' . $prefix . 'el_course_view.start_date)'), '<=', $i)
                    ->where(function ($sub) use ($prefix, $i) {
                        $sub->orWhereNull('el_course_view.end_date');
                        $sub->orWhere(\DB::raw('month(' . $prefix . 'el_course_view.end_date)'), '>=', $i);
                    });
                $query->where(function ($sub) use ($prefix, $i){
                    $sub->orWhereExists(function ($sub1) use ($prefix, $i){
                        $sub1->select(['id'])
                            ->from('el_online_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.course_id', '=', 'b.course_id')
                            ->whereColumn(DB::raw(1), '=', 'b.course_type')
                            ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
                    });
                    $sub->orWhereExists(function ($sub2){
                        $sub2->select(['id'])
                            ->from('el_offline_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.course_id', '=', 'b.course_id')
                            ->whereColumn(DB::raw(2), '=', 'b.course_type');
                    });
                });
                if ($unit) {
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);
                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('b.unit_id', $unit_id);
                        $sub_query->orWhere('b.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type) {
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('b.unit_id', $unit_by_type);
                }
                if ($area) {
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as c', 'c.id', '=', 'b.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('c.area_id', $area_id);
                        $sub_query->orWhere('c.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date) {
                    $start_date = date_convert($request->start_date);
                    $query->where('el_course_view.start_date', '>=', $start_date);
                }
                if ($request->end_date) {
                    $end_date = date_convert($request->end_date, '23:59:59');
                    $query->where('el_course_view.start_date', '<=', $end_date);
                }

                $list_course = $query->get();
                $course_by_units += $list_course->count();
            }

            $content[] = (int) $course_by_units;

            $result[] = $content;
        }
        return $result;
    }

    //Thống kê số lớp Tân tuyển & Hiện hữu
    private function lineChartCourseByCourseEmployee($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $year = date('Y');
        $result = [];

        $head = [];
        $head[] = 'Tháng';
        $head[] = 'Tân tuyển';
        $head[] = 'Hiện hữu';
        $head[] = 'Tổng';

        $result[] = $head;

        for ($i = 1; $i <= 12; $i++){
            $content = [];
            $i = ($i < 10) ? '0'.$i : $i;
            $content[] = (string) $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $total = 0;
            for($course_employee = 1; $course_employee <= 2; $course_employee++) {
                $query = OfflineCourse::query();
                $query->select(['el_offline_course.id']);
                $query->where('el_offline_course.course_employee', $course_employee);
                $query->where('el_offline_course.status', '=', 1);
                $query->where('el_offline_course.isopen', '=', 1);
                $query->where('el_offline_course.start_date','<=', $last_month)
                    ->where(function ($sub) use ($first_month, $last_month){
                        $sub->where('el_offline_course.end_date','>=', $last_month);
                        $sub->orwhere('el_offline_course.end_date', '>=', $first_month);
                    });

                if ($unit || $unit_type || $area){
                    $query->leftjoin('el_course_register_view as b', function ($sub){
                        $sub->on('b.course_id','=','el_offline_course.id');
                        $sub->where('b.course_type','=',2);
                    });
                }

                if ($unit){
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);

                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('b.unit_id', $unit_id);
                        $sub_query->orWhere('b.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type){
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('b.unit_id', $unit_by_type);
                }
                if ($area){
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('c.area_id', $area_id);
                        $sub_query->orWhere('c.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date){
                    $start_date = date_convert($request->start_date);
                    $query->where('el_offline_course.start_date', '>=', $start_date);
                }
                if ($request->end_date){
                    $end_date = date_convert($request->end_date,'23:59:59');
                    $query->where('el_offline_course.end_date', '<=', $end_date);
                }

                $query->groupBy(['el_offline_course.id']);
                $list_course = $query->get();
                $course_by_units = $list_course->count();

                $content[] = (int) $course_by_units;
                $total += (int) $course_by_units;
            }
            $content[] = $total;

            $result[] = $content;
        }
        return [
            'head' => $head,
            'content' => $result,
        ];
    }
    private function pieChartCourseByCourseEmployee($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $result = [];
        $result[] = [
            'Hình thức',
            'Số lượng'
        ];

        for($course_employee = 1; $course_employee <= 2; $course_employee++) {
            $content = [];
            $content[] = $course_employee == 1 ? 'Tân tuyển' : 'Hiện hữu';

            $query = OfflineCourse::query();
            $query->select(['el_offline_course.id']);
            $query->leftjoin('el_course_register_view as b', function ($sub){
                $sub->on('b.course_id','=','el_offline_course.id');
                $sub->where('b.course_type','=',2);
            });
            $query->where('el_offline_course.course_employee', $course_employee);
            $query->where('el_offline_course.status', '=', 1);
            $query->where('el_offline_course.isopen', '=', 1);

            if ($unit){
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($unit_type){
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('b.unit_id', $unit_by_type);
            }
            if ($area){
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('c.area_id', $area_id);
                    $sub_query->orWhere('c.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date){
                $start_date = date_convert($request->start_date);
                $query->where('el_offline_course.start_date', '>=', $start_date);
            }
            if ($request->end_date){
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_offline_course.end_date', '<=', $end_date);
            }

            $query->groupBy(['el_offline_course.id']);
            $list_course = $query->get();
            $course_by_units = $list_course->count();

            $content[] = (int) $course_by_units;

            $result[] = $content;
        }
        //dd($result);
        return $result;
    }

    //Thống kê lượt CBNV Tân tuyển & Hiện hữu
    private function lineChartUserByCourseEmployee($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $year = date('Y');
        $result = [];

        $head = [];
        $head[] = 'Tháng';
        $head[] = 'Tân tuyển';
        $head[] = 'Hiện hữu';
        $head[] = 'Tổng';

        $result[] = $head;

        for ($i = 1; $i <= 12; $i++){
            $content = [];
            $i = ($i < 10) ? '0'.$i : $i;
            $content[] = (string) $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $total = 0;
            for($course_employee = 1; $course_employee <= 2; $course_employee++) {
                $query = CourseView::query();
                $query->join('el_course_register_view as b', function ($sub){
                    $sub->on('b.course_id','=','el_course_view.course_id');
                    $sub->on('b.course_type','=','el_course_view.course_type');
                    $sub->where('b.course_type', '=', 2);
                });
                $query->where('el_course_view.status', '=', 1);
                $query->where('el_course_view.isopen', '=', 1);
                $query->where('el_course_view.course_type', '=', 2);
                $query->where('el_course_view.course_employee', '=', $course_employee);
                $query->where('el_course_view.start_date','<=', $last_month)
                    ->where(function ($sub) use ($first_month, $last_month){
                        $sub->where('el_course_view.end_date','>=', $last_month);
                        $sub->orwhere('el_course_view.end_date', '>=', $first_month);
                    });
                $query->whereExists(function ($sub){
                    $sub->select(['id'])
                        ->from('el_offline_result as result')
                        ->whereColumn('result.user_id', '=', 'b.user_id')
                        ->whereColumn('result.course_id', '=', 'b.course_id');
                });

                if ($unit){
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);

                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('b.unit_id', $unit_id);
                        $sub_query->orWhere('b.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type){
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('b.unit_id', $unit_by_type);
                }
                if ($area){
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('c.area_id', $area_id);
                        $sub_query->orWhere('c.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date){
                    $start_date = date_convert($request->start_date);
                    $query->where('el_course_view.start_date', '>=', $start_date);
                }
                if ($request->end_date){
                    $end_date = date_convert($request->end_date,'23:59:59');
                    $query->where('el_course_view.end_date', '<=', $end_date);
                }

                $list_course = $query->get();
                $course_by_units = $list_course->count();

                $content[] = (int) $course_by_units;
                $total += (int) $course_by_units;
            }
            $content[] = $total;

            $result[] = $content;
        }
        //dd($result);
        return [
            'head' => $head,
            'content' => $result,
        ];
    }
    private function pieChartUserByCourseEmployee($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $result = [];
        $result[] = [
            'Hình thức',
            'Số lượng'
        ];

        for($course_employee = 1; $course_employee <= 2; $course_employee++) {
            $content = [];
            $content[] = $course_employee == 1 ? 'Tân tuyển' : 'Hiện hữu';

            $query = CourseView::query();
            $query->join('el_course_register_view as b', function ($sub){
                $sub->on('b.course_id','=','el_course_view.course_id');
                $sub->on('b.course_type','=','el_course_view.course_type');
                $sub->where('b.course_type', '=', 2);
            });
            $query->where('el_course_view.status', '=', 1);
            $query->where('el_course_view.isopen', '=', 1);
            $query->where('el_course_view.course_type', '=', 2);
            $query->where('el_course_view.course_employee', '=', $course_employee);
            $query->whereExists(function ($sub){
                $sub->select(['result.id'])
                    ->from('el_offline_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.course_id', '=', 'b.course_id');
            });

            if ($unit){
                $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($unit_type){
                $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                $query->whereIn('b.unit_id', $unit_by_type);
            }
            if ($area){
                $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as c','c.id','=','b.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('c.area_id', $area_id);
                    $sub_query->orWhere('c.area_id', '=', $areas->id);
                });
            }
            if ($request->start_date){
                $start_date = date_convert($request->start_date);
                $query->where('el_course_view.start_date', '>=', $start_date);
            }
            if ($request->end_date){
                $end_date = date_convert($request->end_date, '23:59:59');
                $query->where('el_course_view.end_date', '<=', $end_date);
            }

            $list_course = $query->get();
            $course_by_units = $list_course->count();

            $content[] = (int) $course_by_units;

            $result[] = $content;
        }
        //dd($result);
        return $result;
    }

    //Thống kê số ca thi theo loại kỳ thi
    private function lineChartPartByQuizType($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $year = date('Y');
        $result = [];

        $head = [];
        $head[] = 'Tháng';

        $quiz_type_arr = [];
        $quiz_type = QuizType::get(['id', 'name']);
        if ($quiz_type->count() > 0) {
            foreach ($quiz_type as $item){
                $quiz_type_arr[$item->id] = $item->name;
                $head[] = $item->name;
            }
            $head[] = 'Tổng';
        }else{
            $head[] = 'Không có dữ liệu';
        }

        $result[] = $head;

        $prefix = \DB::getTablePrefix();
        for ($i = 1; $i <= 12; $i++){
            $content = [];
            $i = ($i < 10) ? '0'.$i : $i;
            $content[] = (string) $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            if (count($quiz_type_arr) > 0){
                $total = 0;
                foreach ($quiz_type_arr as $key => $item) {
                    $query = QuizPart::query();
                    $query->select(['el_quiz_part.id']);
                    $query->leftjoin('el_quiz_register as b','b.part_id','=','el_quiz_part.id');
                    $query->where('b.type', 1);
                    $query->whereIn('el_quiz_part.quiz_id', function ($sub2) use ($key){
                        $sub2->select(['id'])
                            ->from('el_quiz')
                            ->where('status', '=', 1)
                            ->where('is_open', '=', 1)
                            ->where('type_id', '=', $key)
                            ->pluck('id')->toArray();
                    });
                    $query->where(\DB::raw('month('.$prefix.'el_quiz_part.start_date)'), '<=', $i)
                        ->where(function ($sub) use ($prefix, $i){
                            $sub->orWhereNull('el_quiz_part.end_date');
                            $sub->orWhere(\DB::raw('month('.$prefix.'el_quiz_part.end_date)'), '>=', $i);
                        });
                    /*$query->where('a.start_date','<=', $last_month)
                        ->where(function ($sub) use ($first_month, $last_month){
                            $sub->where('a.end_date','>=', $last_month);
                            $sub->orwhere('a.end_date', '>=', $first_month);
                        });*/
                    if ($unit || $unit_type || $area){
                        $query->leftjoin('el_profile_view as c','c.user_id','=','b.user_id');
                    }

                    if ($unit){
                        $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                        $unit_id = Unit::getArrayChild($units->code);

                        $query->where(function ($sub_query) use ($unit_id, $units) {
                            $sub_query->orWhereIn('c.unit_id', $unit_id);
                            $sub_query->orWhere('c.unit_id', '=', $units->id);
                        });
                    }
                    if ($unit_type){
                        $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                        $query->whereIn('c.unit_id', $unit_by_type);
                    }
                    if ($area){
                        $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                        $area_id = Area::getArrayChild($areas->code);

                        $query->leftjoin('el_unit as d','d.id','=','c.unit_id');
                        $query->where(function ($sub_query) use ($area_id, $areas) {
                            $sub_query->orWhereIn('d.area_id', $area_id);
                            $sub_query->orWhere('d.area_id', '=', $areas->id);
                        });
                    }
                    if ($request->start_date){
                        $start_date = date_convert($request->start_date);
                        $query->where('el_quiz_part.start_date', '>=', $start_date);
                    }
                    if ($request->end_date){
                        $end_date = date_convert($request->end_date, '23:59:59');
                        $query->where('el_quiz_part.end_date', '<=', $end_date);
                    }
                    $query->groupBy(['el_quiz_part.id']);
                    $quiz_part_by_course = $query->get();
                    $part_by_units = $quiz_part_by_course->count();

                    $content[] = (int) $part_by_units;
                    $total += (int) $part_by_units;
                }

                $content[] = $total;
            }else{
                $content[] = 0;
            }

            $result[] = $content;
        }
        return [
            'head' => $head,
            'content' => $result,
        ];
    }
    private function pieChartPartByQuizType($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $result = [];
        $result[] = [
            'Ca thi',
            'Số lượng'
        ];
        $prefix = \DB::getTablePrefix();
        $quiz_type_arr =  QuizType::get(['id', 'name']);
        foreach ($quiz_type_arr as $key => $item) {
            $content = [];
            $content[] = $item->name;

            $part_by_units = 0;
            for ($i = 1; $i <= 12; $i++) {
                $i = ($i < 10) ? '0'.$i : $i;

                $query = QuizPart::query();
                $query->select(['el_quiz_part.id']);
                $query->leftjoin('el_quiz_register as b', 'b.part_id', '=', 'el_quiz_part.id');
                $query->where('b.type', 1);
                $query->whereIn('el_quiz_part.quiz_id', function ($sub2) use ($item) {
                    $sub2->select(['id'])
                        ->from('el_quiz')
                        ->where('status', '=', 1)
                        ->where('is_open', '=', 1)
                        ->where('type_id', '=', $item->id)
                        ->pluck('id')->toArray();
                });
                $query->where(\DB::raw('month('.$prefix.'el_quiz_part.start_date)'), '<=', $i)
                    ->where(function ($sub) use ($prefix, $i){
                        $sub->orWhereNull('el_quiz_part.end_date');
                        $sub->orWhere(\DB::raw('month('.$prefix.'el_quiz_part.end_date)'), '>=', $i);
                    });

                if ($unit || $unit_type || $area){
                    $query->leftjoin('el_profile_view as c', 'c.user_id', '=', 'b.user_id');
                }

                if ($unit) {
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);

                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('c.unit_id', $unit_id);
                        $sub_query->orWhere('c.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type) {
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('c.unit_id', $unit_by_type);
                }
                if ($area) {
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as d', 'd.id', '=', 'c.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('d.area_id', $area_id);
                        $sub_query->orWhere('d.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date) {
                    $start_date = date_convert($request->start_date);
                    $query->where('el_quiz_part.start_date', '>=', $start_date);
                }
                if ($request->end_date) {
                    $end_date = date_convert($request->end_date, '23:59:59');
                    $query->where('el_quiz_part.end_date', '<=', $end_date);
                }
                $query->groupBy(['el_quiz_part.id']);
                $quiz_part_by_course = $query->get();
                $part_by_units += $quiz_part_by_course->count();
            }

            $content[] = (int) $part_by_units;

            $result[] = $content;
        }
        //dd($result);
        return $result;
    }

    private function lineChartUserByQuizType($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $year = date('Y');
        $result = [];

        $head = [];
        $head[] = 'Tháng';

        $quiz_type_arr = [];
        $quiz_type = QuizType::get(['id', 'name']);
        if ($quiz_type->count() > 0) {
            foreach ($quiz_type as $item) {
                $quiz_type_arr[$item->id] = $item->name;
                $head[] = $item->name;
            }
            $head[] = 'Tổng';
        }else{
            $head[] = 'không có dữ liệu';
        }
        $result[] = $head;
        $prefix = \DB::getTablePrefix();

        for ($i = 1; $i <= 12; $i++){
            $content = [];
            $i = ($i < 10) ? '0'.$i : $i;
            $content[] = (string) $i;

            $first_month = date("Y-$i-01 00:00:00");
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            if (count($quiz_type_arr) > 0){
                $total = 0;
                foreach ($quiz_type_arr as $key => $item) {
                    $query = QuizPart::query();
                    $query->leftJoin('el_quiz_register as b', 'b.part_id', '=', 'el_quiz_part.id');
                    $query->where('b.type', '=', 1);
                    $query->whereIn('el_quiz_part.quiz_id', function ($sub2) use ($key){
                        $sub2->select(['id'])
                            ->from('el_quiz')
                            ->where('status', '=', 1)
                            ->where('is_open', '=', 1)
                            ->where('type_id', '=', $key)
                            ->pluck('id')->toArray();
                    });
                    $query->whereExists(function ($sub) use ($prefix, $i) {
                        $sub->select(['id'])
                            ->from('el_quiz_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.quiz_id', '=', 'b.quiz_id')
                            ->where('result.type', '=', 1)
                            ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
                    });
                    /*$query->where(\DB::raw('month('.$prefix.'a.start_date)'), '<=', $i)
                        ->where(function ($sub) use ($prefix, $i){
                            $sub->orWhereNull('a.end_date');
                            $sub->orWhere(\DB::raw('month('.$prefix.'a.end_date)'), '>=', $i);
                        });*/

                    if ($unit || $unit_type || $area){
                        $query->leftjoin('el_profile_view as c', 'c.user_id', '=', 'b.user_id');
                    }

                    if ($unit){
                        $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                        $unit_id = Unit::getArrayChild($units->code);

                        $query->where(function ($sub_query) use ($unit_id, $units) {
                            $sub_query->orWhereIn('c.unit_id', $unit_id);
                            $sub_query->orWhere('c.unit_id', '=', $units->id);
                        });
                    }
                    if ($unit_type){
                        $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                        $query->whereIn('c.unit_id', $unit_by_type);
                    }
                    if ($area){
                        $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                        $area_id = Area::getArrayChild($areas->code);

                        $query->leftjoin('el_unit as d','d.id','=','c.unit_id');
                        $query->where(function ($sub_query) use ($area_id, $areas) {
                            $sub_query->orWhereIn('d.area_id', $area_id);
                            $sub_query->orWhere('d.area_id', '=', $areas->id);
                        });
                    }
                    if ($request->start_date){
                        $start_date = date_convert($request->start_date);
                        $query->where('el_quiz_part.start_date', '>=', $start_date);
                    }
                    if ($request->end_date){
                        $end_date = date_convert($request->end_date, '23:59:59');
                        $query->where('el_quiz_part.end_date', '<=', $end_date);
                    }
                    $user_by_units = $query->get()->count();

                    $content[] = (int) $user_by_units;
                    $total += (int) $user_by_units;
                }

                $content[] = $total;
            }else{
                $content[] = 0;
            }

            $result[] = $content;
        }
        //dd($result);
        return [
            'head' => $head,
            'content' => $result,
        ];
    }
    private function pieChartUserByQuizType($unit_user, $child_arr, Request $request){
        $unit = $request->unit;
        $unit_type = $request->unit_type;
        $area = $request->area;

        $result = [];
        $result[] = [
            'Ca thi',
            'Số lượng'
        ];
        $prefix = \DB::getTablePrefix();

        $quiz_type_arr =  QuizType::get(['id', 'name']);
        foreach ($quiz_type_arr as $key => $item) {
            $content = [];
            $content[] = $item->name;

            $user_by_units = 0;
            for ($i = 1; $i <= 12; $i++) {
                $i = ($i < 10) ? '0' . $i : $i;

                $query = QuizPart::query();
                $query->leftJoin('el_quiz_register as b', 'b.part_id', '=', 'el_quiz_part.id');
                $query->where('b.type', '=', 1);
                $query->whereIn('el_quiz_part.quiz_id', function ($sub2) use ($item) {
                    $sub2->select(['id'])
                        ->from('el_quiz')
                        ->where('status', '=', 1)
                        ->where('is_open', '=', 1)
                        ->where('type_id', '=', $item->id)
                        ->pluck('id')->toArray();
                });
                $query->whereExists(function ($sub) use ($prefix, $i) {
                    $sub->select(['id'])
                        ->from('el_quiz_result as result')
                        ->whereColumn('result.user_id', '=', 'b.user_id')
                        ->whereColumn('result.quiz_id', '=', 'b.quiz_id')
                        ->where('result.type', '=', 1)
                        ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
                });
                /*$query->where(\DB::raw('month('.$prefix.'a.start_date)'), '<=', $i)
                    ->where(function ($sub) use ($prefix, $i){
                        $sub->orWhereNull('a.end_date');
                        $sub->orWhere(\DB::raw('month('.$prefix.'a.end_date)'), '>=', $i);
                    });*/

                if ($unit || $unit_type || $area){
                    $query->leftJoin('el_profile_view AS c', 'c.user_id', '=', 'b.user_id');
                }

                if ($unit) {
                    $units = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                    $unit_id = Unit::getArrayChild($units->code);

                    $query->where(function ($sub_query) use ($unit_id, $units) {
                        $sub_query->orWhereIn('c.unit_id', $unit_id);
                        $sub_query->orWhere('c.unit_id', '=', $units->id);
                    });
                }
                if ($unit_type) {
                    $unit_by_type = Unit::whereType($unit_type)->pluck('id')->toArray();
                    $query->whereIn('c.unit_id', $unit_by_type);
                }
                if ($area) {
                    $areas = Area::whereIn('id', explode(';', $area))->latest('id')->first();
                    $area_id = Area::getArrayChild($areas->code);

                    $query->leftjoin('el_unit as d', 'd.id', '=', 'c.unit_id');
                    $query->where(function ($sub_query) use ($area_id, $areas) {
                        $sub_query->orWhereIn('d.area_id', $area_id);
                        $sub_query->orWhere('d.area_id', '=', $areas->id);
                    });
                }
                if ($request->start_date) {
                    $start_date = date_convert($request->start_date);
                    $query->where('el_quiz_part.start_date', '>=', $start_date);
                }
                if ($request->end_date) {
                    $end_date = date_convert($request->end_date, '23:59:59');
                    $query->where('el_quiz_part.end_date', '<=', $end_date);
                }

                $user_by_units += $query->get()->count();
            }

            $content[] = (int) $user_by_units;

            $result[] = $content;
        }
        //dd($result);
        return $result;
    }

    //EXPORT HÌNH THỨC ĐÀO TẠO
    public function exportDashboardTrainingForm($type, Request $request) {
        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);

        if($type == 0) {
            $area = $request->area_training_form;
            $unit = $request->unit_training_form;
            $unit_type = $request->unit_type_training_form;
            $start_date = $request->start_date_training_form;
            $end_date = $request->end_date_training_form;
        } else {
            $area = $request->area_user_training_form;
            $unit = $request->unit_user_training_form;
            $unit_type = $request->unit_type_user_training_form;
            $start_date = $request->start_user_date_training_form;
            $end_date = $request->end_user_date_training_form;
        }
        // dd($start_date);
        return (new ExportDashboardTrainingForm($type, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date))->download('thong_ke_so_lop_theo_hinh_thuc_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    public function exportDashboardUserTrainingForm(Request $request) {
        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);

        $area = $request->area_user_training_form;
        $unit = $request->unit_user_training_form;
        $unit_type = $request->unit_type_user_training_form;
        $start_date = $request->start_user_date_training_form;
        $end_date = $request->end_user_date_training_form;

        return (new ExportDashboardUserTrainingForm($unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date))->download('thong_ke_luot_CBNV_theo_hinh_thuc_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    //EXPORT Tân tuyển & Hiện hữu
    public function exportDashboardCourseEmployee($type, Request $request) {
        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);

        if($type == 0) {
            $area = $request->area_course_employee;
            $unit = $request->unit_course_employee;
            $unit_type = $request->unit_type_course_employee;
            $start_date = $request->start_date_course_employee;
            $end_date = $request->end_date_course_employee;
        } else {
            $area = $request->area_user_course_employee;
            $unit = $request->unit_user_course_employee;
            $unit_type = $request->unit_type_user_course_employee;
            $start_date = $request->start_date_user_course_employee;
            $end_date = $request->end_date_user_course_employee;
        }
        return (new ExportDashboardCourseEmployee($type, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date))->download('thong_ke_so_lop_theo_theo_Tan_tuyen_Hien_huu_'. date('d_m_Y') .'.xlsx');
    }

    public function exportDashboardUserCourseEmployee(Request $request) {
        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);

        $area = $request->area_user_course_employee;
        $unit = $request->unit_user_course_employee;
        $unit_type = $request->unit_type_user_course_employee;
        $start_date = $request->start_date_user_course_employee;
        $end_date = $request->end_date_user_course_employee;

        return (new ExportDashboardUserCourseEmployee(1, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date))->download('thong_ke_luot_CBNV_theo_Tan_tuyen_Hien_huu_'. date('d_m_Y') .'.xlsx');
    }

    //EXPORT KỲ THI
    public function exportDashboardQuiz($type, Request $request) {
        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);

        if($type == 0) {
            $area = $request->area_quiz;
            $unit = $request->unit_quiz;
            $unit_type = $request->unit_type_quiz;
            $start_date = $request->start_date_quiz;
            $end_date = $request->end_date_quiz;
        } else {
            $area = $request->area_user_quiz;
            $unit = $request->unit_user_quiz;
            $unit_type = $request->unit_type_user_quiz;
            $start_date = $request->start_date_user_quiz;
            $end_date = $request->end_date_user_quiz;
        }
        return (new ExportDashboardQuiz($type, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date))->download('thong_ke_so_ca_thi_theo_loai_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function exportDashboardUserQuiz(Request $request) {
        $unit_user = Unit::find(session('user_unit'));
        $child_arr = Unit::getArrayChild(@$unit_user->code);

        $area = $request->area_user_quiz;
        $unit = $request->unit_user_quiz;
        $unit_type = $request->unit_type_user_quiz;
        $start_date = $request->start_date_user_quiz;
        $end_date = $request->end_date_user_quiz;

        return (new ExportDashboardUserQuiz(1, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date))->download('thong_ke_so_luot_CBNV_thi_theo_loai_ky_thi_'. date('d_m_Y') .'.xlsx');
    }
}
