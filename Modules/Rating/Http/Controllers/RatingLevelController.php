<?php

namespace Modules\Rating\Http\Controllers;

use App\Automail;
use App\CourseRegisterView;
use App\CourseView;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Profile;
use App\ProfileView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Matrix\Builder;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Offline\Entities\OfflineRatingLevelObjectColleague;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRatingLevelObjectColleague;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Rating\Entities\CourseRatingLevel;
use Modules\Rating\Entities\CourseRatingLevelObject;
use Modules\Rating\Entities\CourseRatingLevelObjectColleague;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\Rating\Entities\RatingLevelCourseAnswer;
use Modules\Rating\Entities\RatingLevelCourseAnswerMatrix;
use Modules\Rating\Entities\RatingLevelCourseCategory;
use Modules\Rating\Entities\RatingLevelCourseExport;
use Modules\Rating\Entities\RatingLevelCourseQuestion;
use Modules\Rating\Entities\RatingLevels;
use Modules\Rating\Entities\RatingLevelsCourses;
use Modules\Rating\Entities\RatingQuestionAnswer2;
use Modules\Rating\Entities\RatingTemplate;
use Illuminate\Support\Facades\Auth;
use Modules\Rating\Entities\RatingTemplate2;

class RatingLevelController extends Controller
{
    public function index(Request $request)
    {
        $is_manager = Permission::isUnitManager();
        if (url_mobile()){
            $list_rating_levels = $this->getData($request);
            return view('rating::mobile.rating_level.index', [
                'is_manager' => $is_manager,
                'list_rating_levels' => $list_rating_levels,
            ]);
        }

        return view('rating::frontend.rating_level', [
            'is_manager' => $is_manager,
        ]);
    }

    public function getData(Request $request){
        $search = $request->get('search');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $status = $request->get('status');

        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_id = getUserId();

        $user_manager = UnitManager::query()
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->where('b.user_id', '=', $user_id)
            ->pluck('a.unit_code')->toArray();

        $unit_child = [];
        $unit_child_id = [];
        foreach ($user_manager as $manager) {
            $unit_child_id = UnitManager::getArrayChild($manager);
        }
        $unit_child = Unit::whereIn('id', $unit_child_id)->pluck('code')->toArray();
        $user_manager = array_merge($user_manager, $unit_child);

        $prefix = DB::getTablePrefix();

        $query = OfflineRatingLevel::query();
        $query->select([
            'a.id',
            'a.rating_name',
            'a.course_id',
            'a.object_rating',
            DB::raw('2 as course_type'),
            'b.code as course_code',
            'b.name as course_name',
            'b.start_date',
            'b.end_date',
            'c.user_id',
            'd.id as rating_level_object_id',
        ]);
        $query->from('el_offline_rating_level as a');
        $query->leftJoin('el_offline_course AS b', 'b.id', '=', 'a.course_id');
        $query->leftJoin('el_offline_register_view AS c', 'c.course_id', '=', 'b.id');
        $query->leftJoin('el_offline_rating_level_object AS d', 'd.offline_rating_level_id', '=', 'a.id');
        $query->where('b.status', '=', 1);
        $query->where('c.status', '=', 1);
        $query->where(function ($sub) use ($user_id, $user_manager) {
            $sub->orWhere(function ($sub2) use ($user_id) {
                $sub2->where('d.object_type', '=', 1);
                $sub2->where('c.user_id', '=', $user_id);
            });
            if (Permission::isUnitManager()) {
                $sub->orWhere(function ($sub2) use ($user_manager) {
                    $sub2->where('d.object_type', '=', 2);
                    $sub2->whereIn('c.unit_code', $user_manager);
                });
            }
            $sub->orWhere(function ($sub2) use ($user_id, $user_manager) {
                $sub2->where('d.object_type', '=', 3);
                if (Permission::isUnitManager()){
                    $sub2->whereIn('c.unit_code', $user_manager);
                }
                $sub2->whereExists(function ($sub3) use ($user_id) {
                    $sub3->select(['id'])
                        ->from('el_offline_rating_level_object_colleague as colleague')
                        ->where('colleague.user_id', '=', $user_id)
                        ->whereColumn('colleague.rating_user_id', '=', 'c.user_id');
                });
            });
            $sub->orWhere(function ($sub2) use ($user_id) {
                $sub2->where('d.object_type', '=', 4);
                $sub2->where('d.user_id', 'like', '%' . $user_id . '%');
            });
        });

        if ($search){
            $query->where(function ($sub) use ($search){
                $sub->orWhere('b.code', 'like', '%'.$search.'%');
                $sub->orWhere('b.name', 'like', '%'.$search.'%');
            });
        }
        if ($start_date){
            $query->where(function ($sub) use ($start_date, $prefix){
                $sub->orWhere(function ($sub2) use ($start_date){
                    $sub2->where('d.time_type', '=', 1);
                    $sub2->where('d.start_date', '>=', date_convert($start_date));
                });
                $sub->orWhere(function ($sub2) use ($start_date, $prefix){
                    $sub2->where('d.time_type', '=', 2);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.start_date, 111))"), '>=', date_convert($start_date));
                });
                $sub->orWhere(function ($sub2) use ($start_date, $prefix){
                    $sub2->where('d.time_type', '=', 3);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.end_date, 111))"), '>=', date_convert($start_date));
                });
                $sub->orWhere(function ($sub2) use ($start_date, $prefix){
                    $sub2->where('d.time_type', '=', 4);
                    $sub2->where(\DB::raw("(SELECT DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), created_at, 111)) FROM ".$prefix."el_offline_result WHERE course_id = ".$prefix."b.id AND user_id = ".$prefix."c.user_id AND result = 1)"), '>=', date_convert($start_date));
                });
            });
        }
        if ($end_date){
            $query->where(function ($sub) use ($end_date, $prefix){
                $sub->orWhere(function ($sub2) use ($end_date){
                    $sub2->where('d.time_type', '=', 1);
                    $sub2->where('d.start_date', '<=', date_convert($end_date, '23:59:59'));
                });
                $sub->orWhere(function ($sub2) use ($end_date, $prefix){
                    $sub2->where('d.time_type', '=', 2);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.start_date, 111))"), '<=', date_convert($end_date, '23:59:59'));
                });
                $sub->orWhere(function ($sub2) use ($end_date, $prefix){
                    $sub2->where('d.time_type', '=', 3);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.end_date, 111))"), '<=', date_convert($end_date, '23:59:59'));
                });
                $sub->orWhere(function ($sub2) use ($end_date, $prefix){
                    $sub2->where('d.time_type', '=', 4);
                    $sub2->where(\DB::raw("(SELECT DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), created_at, 111)) FROM ".$prefix."el_online_result WHERE course_id = ".$prefix."b.id AND user_id = ".$prefix."c.user_id AND result = 1)"), '<=', date_convert($end_date, '23:59:59'));
                });
            });
        }
        if ($status){
            if ($status == 0){
                $query->whereNotExists(function ($sub) use ($user_id){
                    $sub->select(['id'])
                        ->from('el_rating_level_course as rlc')
                        ->whereColumn('rlc.course_rating_level_id', '=', 'a.id')
                        ->where('rlc.user_id', '=', $user_id)
                        ->where('rlc.user_type', '=', 1)
                        ->whereColumn('rlc.course_id', '=', 'b.id')
                        ->where('rlc.course_type', '=', 2)
                        ->whereColumn('rlc.rating_user', '=', 'c.user_id');
                });
            }
            if ($status == 1){
                $query->whereExists(function ($sub) use ($user_id){
                    $sub->select(['id'])
                        ->from('el_rating_level_course as rlc')
                        ->whereColumn('rlc.course_rating_level_id', '=', 'a.id')
                        ->where('rlc.user_id', '=', $user_id)
                        ->where('rlc.user_type', '=', 1)
                        ->whereColumn('rlc.course_id', '=', 'b.id')
                        ->where('rlc.course_type', '=', 2)
                        ->whereColumn('rlc.rating_user', '=', 'c.user_id')
                        ->where('rlc.send', '=', 1);
                });
            }
            if ($status == 2){
                $query->where('d.end_date', '<=', now());
            }
        }

        $offline_rating_level = $query;

        $query = OnlineRatingLevel::query();
        $query->select([
            'a.id',
            'a.rating_name',
            'a.course_id',
            'a.object_rating',
            DB::raw('1 as course_type'),
            'b.code as course_code',
            'b.name as course_name',
            'b.start_date',
            'b.end_date',
            'c.user_id',
            'd.id as rating_level_object_id',
        ]);
        $query->from('el_online_rating_level as a');
        $query->leftJoin('el_online_course AS b', 'b.id', '=', 'a.course_id');
        $query->leftJoin('el_online_register_view AS c', 'c.course_id', '=', 'b.id');
        $query->leftJoin('el_online_rating_level_object AS d', 'd.online_rating_level_id', '=', 'a.id');
        $query->where('b.status', '=', 1);
        $query->where('c.status', '=', 1);
        $query->where('c.user_type', '=', 1);
        $query->where(function ($sub) use ($user_id, $user_manager){
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 1);
                $sub2->where('c.user_id', '=', $user_id);
            });
            if (Permission::isUnitManager()){
                $sub->orWhere(function ($sub2) use ($user_manager){
                    $sub2->where('d.object_type', '=', 2);
                    $sub2->whereIn('c.unit_code', $user_manager);
                });
            }
            $sub->orWhere(function ($sub2) use ($user_id, $user_manager){
                $sub2->where('d.object_type', '=', 3);
                if (Permission::isUnitManager()){
                    $sub2->whereIn('c.unit_code', $user_manager);
                }
                $sub2->whereExists(function ($sub3) use ($user_id){
                    $sub3->select(['id'])
                        ->from('el_online_rating_level_object_colleague as colleague')
                        ->where('colleague.user_id', '=', $user_id)
                        ->whereColumn('colleague.rating_user_id', '=', 'c.user_id');
                });
            });
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 4);
                $sub2->where('d.user_id', 'like', '%'.$user_id.'%');
            });
        });

        if ($search){
            $query->where(function ($sub) use ($search){
                $sub->orWhere('b.code', 'like', '%'.$search.'%');
                $sub->orWhere('b.name', 'like', '%'.$search.'%');
            });
        }
        if ($start_date){
            $query->where(function ($sub) use ($start_date, $prefix){
                $sub->orWhere(function ($sub2) use ($start_date){
                    $sub2->where('d.time_type', '=', 1);
                    $sub2->where('d.start_date', '>=', date_convert($start_date));
                });
                $sub->orWhere(function ($sub2) use ($start_date, $prefix){
                    $sub2->where('d.time_type', '=', 2);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.start_date, 111))"), '>=', date_convert($start_date));
                });
                $sub->orWhere(function ($sub2) use ($start_date, $prefix){
                    $sub2->where('d.time_type', '=', 3);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.end_date, 111))"), '>=', date_convert($start_date));
                });
                $sub->orWhere(function ($sub2) use ($start_date, $prefix){
                    $sub2->where('d.time_type', '=', 4);
                    $sub2->where(\DB::raw("(SELECT DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), created_at, 111)) FROM ".$prefix."el_online_result WHERE course_id = ".$prefix."b.id AND user_id = ".$prefix."c.user_id AND result = 1)"), '>=', date_convert($start_date));
                });
            });
        }
        if ($end_date){
            $query->where(function ($sub) use ($end_date, $prefix){
                $sub->orWhere(function ($sub2) use ($end_date){
                    $sub2->where('d.time_type', '=', 1);
                    $sub2->where('d.start_date', '<=', date_convert($end_date, '23:59:59'));
                });
                $sub->orWhere(function ($sub2) use ($end_date, $prefix){
                    $sub2->where('d.time_type', '=', 2);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.start_date, 111))"), '<=', date_convert($end_date, '23:59:59'));
                });
                $sub->orWhere(function ($sub2) use ($end_date, $prefix){
                    $sub2->where('d.time_type', '=', 3);
                    $sub2->where(\DB::raw("DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), ".$prefix."b.end_date, 111))"), '<=', date_convert($end_date, '23:59:59'));
                });
                $sub->orWhere(function ($sub2) use ($end_date, $prefix){
                    $sub2->where('d.time_type', '=', 4);
                    $sub2->where(\DB::raw("(SELECT DATEADD(day, ISNULL(".$prefix."d.num_date, 0), CONVERT(VARCHAR(10), created_at, 111)) FROM ".$prefix."el_online_result WHERE course_id = ".$prefix."b.id AND user_id = ".$prefix."c.user_id AND result = 1)"), '<=', date_convert($end_date, '23:59:59'));
                });
            });
        }
        if ($status){
            if ($status == 0){
                $query->whereNotExists(function ($sub) use ($user_id){
                    $sub->select(['id'])
                        ->from('el_rating_level_course as rlc')
                        ->whereColumn('rlc.course_rating_level_id', '=', 'a.id')
                        ->where('rlc.user_id', '=', $user_id)
                        ->where('rlc.user_type', '=', 1)
                        ->whereColumn('rlc.course_id', '=', 'b.id')
                        ->where('rlc.course_type', '=', 1)
                        ->whereColumn('rlc.rating_user', '=', 'c.user_id');
                });
            }
            if ($status == 1){
                $query->whereExists(function ($sub) use ($user_id){
                    $sub->select(['id'])
                        ->from('el_rating_level_course as rlc')
                        ->whereColumn('rlc.course_rating_level_id', '=', 'a.id')
                        ->where('rlc.user_id', '=', $user_id)
                        ->where('rlc.user_type', '=', 1)
                        ->whereColumn('rlc.course_id', '=', 'b.id')
                        ->where('rlc.course_type', '=', 1)
                        ->whereColumn('rlc.rating_user', '=', 'c.user_id')
                        ->where('rlc.send', '=', 1);
                });
            }
            if ($status == 2){
                $query->where('d.end_date', '<=', now());
            }
        }

        $online_rating_level = $query;

        $query = CourseRatingLevel::query();
        $query->select([
            'a.id',
            'a.rating_name',
            'a.rating_levels_id as course_id',
            'a.object_rating',
            DB::raw('3 as course_type'),
            DB::raw('null as course_code'),
            DB::raw('null as course_name'),
            DB::raw('null as start_date'),
            DB::raw('null as end_date'),
            'c.user_id',
            'd.id as rating_level_object_id',
        ]);
        $query->from('el_course_rating_level as a');
        $query->leftJoin('el_rating_levels AS b', 'b.id', '=', 'a.rating_levels_id');
        $query->leftJoin('el_rating_levels_register AS c', 'c.rating_levels_id', '=', 'b.id');
        $query->leftJoin('el_course_rating_level_object AS d', 'd.course_rating_level_id', '=', 'a.id');
        $query->where(function ($sub) use ($user_id, $user_manager){
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 1);
                $sub2->where('c.user_id', '=', $user_id);
            });
            if (Permission::isUnitManager()){
                $sub->orWhere(function ($sub2) use ($user_manager){
                    $sub2->where('d.object_type', '=', 2);
                    $sub2->whereIn('c.unit_code', $user_manager);
                });
            }
            $sub->orWhere(function ($sub2) use ($user_id, $user_manager){
                $sub2->where('d.object_type', '=', 3);
                if (Permission::isUnitManager()){
                    $sub2->whereIn('c.unit_code', $user_manager);
                }
                $sub2->whereExists(function ($sub3) use ($user_id){
                    $sub3->select(['id'])
                        ->from('el_course_rating_level_object_colleague as colleague')
                        ->where('colleague.user_id', '=', $user_id)
                        ->whereColumn('colleague.rating_user_id', '=', 'c.user_id');
                });
            });
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 4);
                $sub2->where('d.user_id', 'like', '%'.$user_id.'%');
            });
        });

        if ($search){
            $query->leftJoin('el_rating_levels_courses as courses', 'courses.rating_levels_id', '=', 'b.id');
            $query->leftJoin('el_course_view as course_view', function ($sub){
                $sub->on('course_view.course_id', '=', 'courses.course_id');
                $sub->on('course_view.course_type', '=', 'courses.course_type');
            });
            $query->where(function ($sub) use ($search){
                $sub->orWhere('course_view.name', 'like', '%'.$search.'%');
            });
        }
        if ($start_date){
            $query->where(function ($sub) use ($start_date, $prefix){
                $sub->orWhere(function ($sub2) use ($start_date){
                    $sub2->where('d.time_type', '=', 1);
                    $sub2->where('d.start_date', '>=', date_convert($start_date));
                });
            });
        }
        if ($end_date){
            $query->where(function ($sub) use ($end_date, $prefix){
                $sub->orWhere(function ($sub2) use ($end_date){
                    $sub2->where('d.time_type', '=', 1);
                    $sub2->where('d.start_date', '<=', date_convert($end_date, '23:59:59'));
                });
            });
        }
        if ($status){
            if ($status == 0){
                $query->whereNotExists(function ($sub) use ($user_id){
                    $sub->select(['id'])
                        ->from('el_rating_level_course as rlc')
                        ->whereColumn('rlc.course_rating_level_id', '=', 'a.id')
                        ->where('rlc.user_id', '=', $user_id)
                        ->where('rlc.user_type', '=', 1)
                        ->whereColumn('rlc.course_id', '=', 'b.id')
                        ->where('rlc.course_type', '=', 3)
                        ->whereColumn('rlc.rating_user', '=', 'c.user_id');
                });
            }
            if ($status == 1){
                $query->whereExists(function ($sub) use ($user_id){
                    $sub->select(['id'])
                        ->from('el_rating_level_course as rlc')
                        ->whereColumn('rlc.course_rating_level_id', '=', 'a.id')
                        ->where('rlc.user_id', '=', $user_id)
                        ->where('rlc.user_type', '=', 1)
                        ->whereColumn('rlc.course_id', '=', 'b.id')
                        ->where('rlc.course_type', '=', 3)
                        ->whereColumn('rlc.rating_user', '=', 'c.user_id')
                        ->where('rlc.send', '=', 1);
                });
            }
            if ($status == 2){
                $query->where('d.end_date', '<=', now());
            }
        }

        $query->union($offline_rating_level);
        $query->union($online_rating_level);

        $querySql = $query->toSql();
        $query = DB::table(DB::raw("($querySql) as a"))->mergeBindings($query->getQuery());
//dd($query->toSql());
        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $rating_user = $row->user_id;
            $check = [];
            $start_date_rating = '';
            $end_date_rating = '';
            $rating_level_url = '';
            $rating_status = 0;
            $user_completed = 0;
            $user_result = 0;

            if ($row->course_type == 1){
                $rating_level_object = OnlineRatingLevelObject::find($row->rating_level_object_id);

                $check_object_type_3 = OnlineRatingLevelObject::query()
                    ->where('course_id', '=', $row->course_id)
                    ->where('online_rating_level_id', '=', $row->id)
                    ->where('object_type', '=', 3);
            }
            if ($row->course_type == 2){
                $rating_level_object = OfflineRatingLevelObject::find($row->rating_level_object_id);

                $check_object_type_3 = OfflineRatingLevelObject::query()
                    ->where('course_id', '=', $row->course_id)
                    ->where('offline_rating_level_id', '=', $row->id)
                    ->where('object_type', '=', 3);
            }
            if ($row->course_type == 3){
                $rating_level_object = CourseRatingLevelObject::find($row->rating_level_object_id);

                $check_object_type_3 = CourseRatingLevelObject::query()
                    ->where('rating_levels_id', '=', $row->course_id)
                    ->where('course_rating_level_id', '=', $row->id)
                    ->where('object_type', '=', 3);
            }


            if ($check_object_type_3->exists()){
                $row->colleague = 1;
                $row->modal_object_colleague_url = route('module.rating_level.modal_add_object_colleague', [$row->course_id, $row->course_type, $row->id, $row->user_id]);
            }else{
                $row->colleague = 0;
            }

            if ($rating_level_object){
                if ($row->course_type == 1){
                    $result = OnlineResult::query()
                        ->where('course_id', '=', $row->course_id)
                        ->where('user_id', '=', $row->user_id)
                        ->where('result', '=', 1)
                        ->first();
                }
                if($row->course_type == 2){
                    $result = OfflineResult::query()
                        ->where('course_id', '=', $row->course_id)
                        ->where('user_id', '=', $row->user_id)
                        ->where('result', '=', 1)
                        ->first();
                }
                if($row->course_type == 3){
                    $list_courses = RatingLevelsCourses::where('rating_levels_id', '=', $row->course_id)->get();
                    $num_result = 0;
                    foreach ($list_courses as $course){
                        if ($course->course_type == 1){
                            $course_result = OnlineResult::query()
                                ->where('course_id', '=', $course->course_id)
                                ->where('user_id', '=', $row->user_id)
                                ->where('result', '=', 1);
                        }
                        if($course->course_type == 2){
                            $course_result = OfflineResult::query()
                                ->where('course_id', '=', $course->course_id)
                                ->where('user_id', '=', $row->user_id)
                                ->where('result', '=', 1);
                        }

                        if ($course_result->exists()){
                            $num_result += 1;
                        }
                    }
                    if ($num_result == $list_courses->count()){
                        $result = true;
                    }else{
                        $result = false;
                    }
                }

                if ($result) {
                    $user_result = 1;
                }

                if ($rating_level_object->time_type == 1){
                    $start_date_rating = $rating_level_object->start_date;
                    $end_date_rating = $rating_level_object->end_date;
                }
                if ($rating_level_object->time_type == 2 && $row->course_type != 3){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($row->start_date)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating= $row->start_date;
                    }
                }
                if ($rating_level_object->time_type == 3 && $row->course_type != 3){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($row->end_date)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $row->end_date;
                    }
                }
                if ($rating_level_object->time_type == 4 && $row->course_type != 3){
                    if ($result){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating = $result->created_at;
                        }
                    }
                }
                if($rating_level_object->user_completed == 1){
                    $user_completed = 1;
                }

                if (empty($start_date_rating) && empty($end_date_rating) && $user_completed == 0){
                    $rating_level_url = route('module.rating_level.course', [$row->course_id, $row->course_type, $row->id, $rating_user]).'?rating_level_object_id='.$row->rating_level_object_id.'&view_type=rating_level';
                }else{
                    if ($start_date_rating){
                        if ($start_date_rating <= now()){
                            $check[] = true;
                        }else{
                            $check[] = false;
                        }
                    }

                    if ($end_date_rating){
                        if ($end_date_rating >= now()){
                            $check[] = true;
                        }else{
                            $check[] = false;
                        }
                    }

                    if ($user_completed == 1){
                        if ($user_result == 1){
                            $check[] = true;
                        }else{
                            $check[] = false;
                        }
                    }

                    if (!in_array(false, $check)){
                        $rating_level_url = route('module.rating_level.course', [$row->course_id, $row->course_type, $row->id, $rating_user]).'?rating_level_object_id='.$row->rating_level_object_id.'&view_type=rating_level';
                    }
                }
            }

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $row->id)
                ->where('user_id', '=', $user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $row->course_id)
                ->where('course_type', '=', $row->course_type)
                ->where('rating_user', '=', $rating_user)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
                $rating_level_url = route('module.rating_level.edit_course', [$row->course_id, $row->course_type, $row->id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.$row->rating_level_object_id.'&view_type=rating_level';
            }

            $row->course_time = get_date($row->start_date) . ($row->end_date ? ' đến '. get_date($row->end_date) : '');
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            $row->rating_level_url = $rating_level_url;
            $row->object_rating = $row->object_rating == 1 ? 'Lớp học' : Profile::usercode($row->user_id) . ' - ' . Profile::fullname($row->user_id);
//            $row->text_course_type = $row->course_type == 1 ? 'Online' : 'Tập trung';

            if ($row->course_type == 3){
                $rating_levels_courses = RatingLevelsCourses::query()
                    ->select(['b.code', 'b.name', 'b.start_date', 'b.end_date'])
                    ->from('el_rating_levels_courses as a')
                    ->leftJoin('el_course_view as b', function ($sub){
                        $sub->on('b.course_id', '=', 'a.course_id');
                        $sub->on('b.course_type', '=', 'a.course_type');
                    })
                    ->where('rating_levels_id', '=', $row->course_id)
                    ->get();
                $list_courrse = '';
                $list_courrse_info = '';
                foreach ($rating_levels_courses as $course){
                    $list_courrse .= $course->name .'; ';

                    $list_courrse_info .= '('. $course->code .') '. $course->name .PHP_EOL. get_date($course->start_date) . ($course->end_date ? ' - '. get_date($course->end_date) : '') . PHP_EOL.PHP_EOL;
                }

                $row->course_name = $list_courrse;
                $row->course_info = $list_courrse_info;
            }else{
                $row->course_info = '('. $row->course_code .') '.PHP_EOL. $row->course_name .PHP_EOL. $row->course_time;
            }

        }

        if (url_mobile()){
            return $rows;
        }else{
            json_result(['total' => $count, 'rows' => $rows]);
        }
    }

    public function modalAddObjectColleague($course_id, $course_type, $course_rating_level, $rating_user){
        $unit_manager = UnitManager::query()
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->where('b.user_id', '=', Auth::id())
            ->pluck('a.unit_code')->toArray();

        $unit_child = [];
        $unit_child_id = [];
        foreach ($unit_manager as $manager) {
            $unit_child_id = UnitManager::getArrayChild($manager);
        }
        $unit_child = Unit::whereIn('id', $unit_child_id)->pluck('code')->toArray();
        $unit_manager = array_merge($unit_manager, $unit_child);

        $profile_unit = Profile::query()
            ->whereIn('unit_code', $unit_manager)
            ->whereNotIn('user_id', [$rating_user, Auth::id()])->get();

        return view('rating::modal.modal_object_colleague', [
            'profile_unit' => $profile_unit,
            'course_id' => $course_id,
            'course_type' => $course_type,
            'course_rating_level' => $course_rating_level,
            'rating_user' => $rating_user
        ]);
    }

    public function addObjectColleague($course_id, $course_type, $course_rating_level, $rating_user, Request $request){
        $user_id = $request->user_id;

        if ($course_type == 1){
            $rating_level = OnlineRatingLevel::find($course_rating_level);
            $object_type = 'action_plan_reminder_online_01';

            $check_object_type_3 = OnlineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('online_rating_level_id', '=', $course_rating_level)
                ->where('object_type', '=', 3)
                ->first();

            $check = OnlineRatingLevelObjectColleague::query()
                ->where('online_rating_level_id', '=', $course_rating_level)
                ->where('rating_user_id','=', $rating_user)
                ->count();

            if ($check_object_type_3->num_user == $check){
                json_message('Đã thêm đủ số nhân viên', 'error');
            }

            $model = new OnlineRatingLevelObjectColleague();
            $model->online_rating_level_id = $course_rating_level;
            $model->rating_user_id = $rating_user;
            $model->user_id = $user_id;
            $model->save();
        }
        if ($course_type == 2){
            $rating_level = OfflineRatingLevel::find($course_rating_level);
            $object_type = 'action_plan_reminder_offline_01';

            $check_object_type_3 = OfflineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('offline_rating_level_id', '=', $course_rating_level)
                ->where('object_type', '=', 3)
                ->first();

            $check = OfflineRatingLevelObjectColleague::query()
                ->where('offline_rating_level_id', '=', $course_rating_level)
                ->where('rating_user_id','=', $rating_user)
                ->count();

            if ($check_object_type_3->num_user == $check){
                json_message('Đã thêm đủ số nhân viên', 'error');
            }

            $model = new OfflineRatingLevelObjectColleague();
            $model->offline_rating_level_id = $course_rating_level;
            $model->rating_user_id = $rating_user;
            $model->user_id = $user_id;
            $model->save();
        }

        if ($course_type == 3){
            $rating_level = CourseRatingLevel::find($course_rating_level);
            $object_type = 'action_plan_reminder_course_01';

            $check_object_type_3 = CourseRatingLevelObject::query()
                ->where('rating_levels_id', '=', $course_id)
                ->where('course_rating_level_id', '=', $course_rating_level)
                ->where('object_type', '=', 3)
                ->first();

            $check = CourseRatingLevelObjectColleague::query()
                ->where('course_rating_level_id', '=', $course_rating_level)
                ->where('rating_user_id','=', $rating_user)
                ->count();

            if ($check_object_type_3->num_user == $check){
                json_message('Đã thêm đủ số nhân viên', 'error');
            }

            $model = new CourseRatingLevelObjectColleague();
            $model->course_rating_level_id = $course_rating_level;
            $model->rating_user_id = $rating_user;
            $model->user_id = $user_id;
            $model->rating_template_id = @$check_object_type_3->rating_template_id;
            $model->save();
        }

        $user = Profile::whereUserId($user_id)->first();
        $signature = getMailSignature($user_id);
        $params = [
            'signature' => $signature,
            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
            'full_name' => $user->lastname.' '.$user->firstname,
            'rating_name' => $rating_level->rating_name,
            'url' => route('module.rating_level'),
        ];

        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id, $object_type);
        $this->updateNotifyUserRatingLevel($params, [$user->user_id]);

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function getDataObjectColleague($course_type, $course_rating_level, $rating_user, Request $request){
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        if ($course_type == 1){
            $query = OnlineRatingLevelObjectColleague::query();
            $query->where('online_rating_level_id', '=', $course_rating_level);
            $query->where('rating_user_id', '=', $rating_user);
        }
        if ($course_type == 2){
            $query = OfflineRatingLevelObjectColleague::query();
            $query->where('offline_rating_level_id', '=', $course_rating_level);
            $query->where('rating_user_id', '=', $rating_user);
        }
        if ($course_type == 3){
            $query = CourseRatingLevelObjectColleague::query();
            $query->where('course_rating_level_id', '=', $course_rating_level);
            $query->where('rating_user_id', '=', $rating_user);
        }

        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->full_name = Profile::fullname($row->user_id);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObjectColleague($course_type, Request $request){
        $ids = $request->ids;

        if ($course_type == 1){
            OnlineRatingLevelObjectColleague::destroy($ids);
        }
        if ($course_type == 2){
            OfflineRatingLevelObjectColleague::destroy($ids);
        }
        if ($course_type == 3){
            CourseRatingLevelObjectColleague::destroy($ids);
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getCourse($course_id, $course_type, $course_rating_level, $rating_user, Request $request) {
        $course_rating_level_object_id = 0;

        if($course_type == 1){
            $item = OnlineCourse::find($course_id);
            $rating_level = OnlineRatingLevel::find($course_rating_level);
            $rating_level_object = OnlineRatingLevelObject::find($request->rating_level_object_id);
            $result = OnlineResult::query()
                ->where('course_id', '=', $course_id)
                ->where('user_id', '=', $rating_user)
                ->where('result', '=', 1)
                ->first();
        }
        if($course_type == 2){
            $item = OfflineCourse::find($course_id);
            $rating_level = OfflineRatingLevel::find($course_rating_level);
            $rating_level_object = OfflineRatingLevelObject::find($request->rating_level_object_id);
            $result = OfflineResult::query()
                ->where('course_id', '=', $course_id)
                ->where('user_id', '=', $rating_user)
                ->where('result', '=', 1)
                ->first();
        }
        if($course_type == 3){
            $item = RatingLevels::find($course_id);
            $rating_level = CourseRatingLevel::find($course_rating_level);
            $rating_level_object = CourseRatingLevelObject::find($request->rating_level_object_id);
            $course_rating_level_object_id = $request->rating_level_object_id;
        }

        $start_date_rating = '';
        $end_date_rating = '';
        if ($rating_level_object){
            if ($rating_level_object->time_type == 1){
                $start_date_rating = $rating_level_object->start_date;
                $end_date_rating = $rating_level_object->end_date;
            }
            if ($rating_level_object->time_type == 2 && $course_type != 3){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->start_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating= $item->start_date;
                }
            }
            if ($rating_level_object->time_type == 3 && $course_type != 3){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->end_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating = $item->end_date;
                }
            }
            if ($rating_level_object->time_type == 4 && $course_type != 3){
                if ($result){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $result->created_at;
                    }
                }
            }
        }

        $profile = ProfileView::whereUserId(Auth::id())->first();
        $object_rating = ProfileView::whereUserId($rating_user)->first();
        $template = RatingTemplate2::where('course_rating_level_id', $rating_level->id)
            ->where('course_id', $course_id)
            ->where('course_type', $course_type)
            ->where('course_rating_level_object_id', $course_rating_level_object_id)
            ->first();

        $view_type = $request->view_type;
        //dd($item, $rating_level, $rating_level_object, $course_rating_level_object_id, $template);
        if (url_mobile()){
            return view('rating::mobile.rating_level.rating_level', [
                'item' => $item,
                'course_type' => $course_type,
                'template' => $template,
                'rating_level' => $rating_level,
                'profile' => $profile,
                'rating_user' => $rating_user,
                'object_rating' => $object_rating,
                'start_date_rating' => $start_date_rating,
                'end_date_rating' => $end_date_rating,
                'view_type' => $view_type,
                'rating_level_object_id' => $request->rating_level_object_id,
                'course_rating_level_object_id' => $course_rating_level_object_id,
            ]);
        }
        return view('rating::modal.rating_level', [
            'item' => $item,
            'course_type' => $course_type,
            'template' => $template,
            'rating_level' => $rating_level,
            'profile' => $profile,
            'rating_user' => $rating_user,
            'object_rating' => $object_rating,
            'start_date_rating' => $start_date_rating,
            'end_date_rating' => $end_date_rating,
            'view_type' => $view_type,
            'rating_level_object_id' => $request->rating_level_object_id,
            'course_rating_level_object_id' => $course_rating_level_object_id,
        ]);
    }

    public function editCourse($course_id, $course_type, $course_rating_level, $rating_user, Request $request) {
        if($course_type == 1){
            $item = OnlineCourse::find($course_id);
            $rating_level = OnlineRatingLevel::find($course_rating_level);
            $rating_level_object = OnlineRatingLevelObject::find($request->rating_level_object_id);
            $result = OnlineResult::query()
                ->where('course_id', '=', $course_id)
                ->where('user_id', '=', $rating_user)
                ->where('result', '=', 1)
                ->first();
        }
        if($course_type == 2){
            $item = OfflineCourse::find($course_id);
            $rating_level = OfflineRatingLevel::find($course_rating_level);
            $rating_level_object = OfflineRatingLevelObject::find($request->rating_level_object_id);
            $result = OfflineResult::query()
                ->where('course_id', '=', $course_id)
                ->where('user_id', '=', $rating_user)
                ->where('result', '=', 1)
                ->first();
        }
        if($course_type == 3){
            $item = RatingLevels::find($course_id);
            $rating_level = CourseRatingLevel::find($course_rating_level);
            $rating_level_object = CourseRatingLevelObject::find($request->rating_level_object_id);
        }

        $start_date_rating = '';
        $end_date_rating = '';
        if ($rating_level_object){
            if ($rating_level_object->time_type == 1){
                $start_date_rating = $rating_level_object->start_date;
                $end_date_rating = $rating_level_object->end_date;
            }
            if ($rating_level_object->time_type == 2 && $course_type != 3){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->start_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating= $item->start_date;
                }
            }
            if ($rating_level_object->time_type == 3 && $course_type != 3){
                if (isset($rating_level_object->num_date)){
                    $start_date_rating = date("Y-m-d", strtotime($item->end_date)) . " +{$rating_level_object->num_date} day";
                }else{
                    $start_date_rating = $item->end_date;
                }
            }
            if ($rating_level_object->time_type == 4 && $course_type != 3){
                if ($result){
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $result->created_at;
                    }
                }
            }
        }

        $user_id = getUserId();
        $user_type = getUserType();
        $rating_level_course = RatingLevelCourse::query()
            ->where('course_rating_level_id', '=', $course_rating_level)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->first();

        $rating_course_categories = RatingLevelCourseCategory::where('rating_level_course_id', '=', $rating_level_course->id)->get();

        $profile = ProfileView::whereUserId(Auth::id())->first();
        $object_rating = ProfileView::whereUserId($rating_user)->first();
        $view_type = $request->view_type;
        if (url_mobile()){
            return view('rating::mobile.rating_level.edit_rating_level', [
                'item' => $item,
                'course_type' => $course_type,
                'rating_course_categories' => $rating_course_categories,
                'rating_level' => $rating_level,
                'profile' => $profile,
                'rating_level_course' => $rating_level_course,
                'rating_user' => $rating_user,
                'object_rating' => $object_rating,
                'start_date_rating' => $start_date_rating,
                'end_date_rating' => $end_date_rating,
                'view_type' => $view_type,
                'rating_level_object_id' => $request->rating_level_object_id,
            ]);
        }
        return view('rating::modal.edit_rating_level', [
            'item' => $item,
            'course_type' => $course_type,
            'rating_course_categories' => $rating_course_categories,
            'rating_level' => $rating_level,
            'profile' => $profile,
            'rating_level_course' => $rating_level_course,
            'rating_user' => $rating_user,
            'object_rating' => $object_rating,
            'start_date_rating' => $start_date_rating,
            'end_date_rating' => $end_date_rating,
            'view_type' => $view_type,
            'rating_level_object_id' => $request->rating_level_object_id,
        ]);
    }

    public function saveRatingCourse($course_id, $course_type, $course_rating_level_id, $rating_user, Request $request){
        $user_id = getUserId();
        $user_type = getUserType();

        $errors = [];
        $title_report = [];
        $content_report = [];

        $rating_user_id = $request->rating_user_id;
        $level = $request->level;

        $user_category_id = $request->user_category_id;
        $category_id = $request->category_id;
        $category_name = $request->category_name;

        $user_question_id = $request->user_question_id;
        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_essay = $request->answer_essay;

        $user_answer_id = $request->user_answer_id;
        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $text_answer = $request->text_answer;
        $is_check = $request->is_check;
        $is_row = $request->is_row;
        $answer_matrix = $request->answer_matrix;
        $check_answer_matrix = $request->check_answer_matrix;

        $send = $request->send;

        $answer_matrix_code = $request->answer_matrix_code;

        $view_type = $request->view_type;
        $rating_level_object_id = $request->rating_level_object_id;
        $course_rating_level_object_id = $request->course_rating_level_object_id;

        $model = RatingLevelCourse::firstOrNew(['id' => $rating_user_id]);
        $model->course_rating_level_id = $course_rating_level_id;
        $model->course_rating_level_object_id = $course_rating_level_object_id;
        $model->level = $level;
        $model->user_id = $user_id;
        $model->user_type = $user_type;
        $model->course_id = $course_id;
        $model->course_type = $course_type;
        $model->send = $send;
        $model->rating_user = $rating_user;
        $model->user_update = $user_id;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = RatingLevelCourseCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->rating_level_course_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    $user_ques_id = $user_question_id[$cate_id][$ques_key];
                    $ques_code = $question_code[$cate_id][$ques_id];
                    $ques_name = $question_name[$cate_id][$ques_id];

                    $course_question = RatingLevelCourseQuestion::firstOrNew(['id' => $user_ques_id]);
                    $course_question->course_category_id = $categories->id;
                    $course_question->question_id = $ques_id;
                    $course_question->question_code = isset($ques_code) ? $ques_code : null;
                    $course_question->question_name = $ques_name;
                    $course_question->type = $type[$cate_id][$ques_id];
                    $course_question->multiple = $multiple[$cate_id][$ques_id];
                    $course_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                    $course_question->save();

                    if ($course_question->type == 'choice' && $course_question->multiple == 0){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                    }
                    if ($course_question->type == 'essay' || $course_question->type == 'time'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($course_question->answer_essay) ? $course_question->answer_essay : 'null';
                    }
                    if ($course_question->type == 'dropdown'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($answer_code[$cate_id][$ques_id][$course_question->answer_essay]) ? $answer_code[$cate_id][$ques_id][$course_question->answer_essay] : 'null';
                    }

                    if(isset($answer_id[$cate_id][$ques_id])){
                        if($course_question->type == 'percent'){
                            $total = 0;
                            $arr_answer_percent = $text_answer[$cate_id][$ques_id];
                            foreach ($arr_answer_percent as $percent){
                                $total += preg_replace("/[^0-9]/", '', $percent);
                            }

                            if ($total > 100){
                                $errors[] = 'Tổng phần trăm câu hỏi: "'. $ques_name . '" vượt quá 100';
                            }
                        }

                        foreach($answer_id[$cate_id][$ques_id] as $ans_key => $ans_id){
                            $user_ans_id = $user_answer_id[$cate_id][$ques_id][$ans_key];
                            $ans_code = $answer_code[$cate_id][$ques_id][$ans_id];
                            $ans_name = $answer_name[$cate_id][$ques_id][$ans_id];
                            $text = $is_text[$cate_id][$ques_id][$ans_id];
                            $row = $is_row[$cate_id][$ques_id][$ans_id];

                            $course_answer = RatingLevelCourseAnswer::firstOrNew(['id' => $user_ans_id]);
                            $course_answer->course_question_id = $course_question->id;
                            $course_answer->answer_id = $ans_id;
                            $course_answer->answer_code = isset($ans_code) ? $ans_code : '';
                            $course_answer->answer_name = isset($ans_name) ? $ans_name : '';
                            $course_answer->is_text = $text;
                            $course_answer->is_row = $row;

                            if ($course_question->multiple == 1){
                                $course_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;

                                if ($course_question->type == 'choice'){
                                    $title_report[] = isset($ans_code) ? $ans_code : 'null';
                                    $content_report[] = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : (isset($is_check[$cate_id][$ques_id][$ans_id]) ? 1 : 0);
                                }
                            }else{
                                if (isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id])){
                                    $course_answer->is_check = $ans_id;

                                    $content_report[] = (isset($ans_code) ? $ans_code : 'null') . (isset($text_answer[$cate_id][$ques_id][$ans_id]) ? ' - '.$text_answer[$cate_id][$ques_id][$ans_id] : '');
                                }else{
                                    $course_answer->is_check = 0;
                                }
                            }

                            if($course_question->type == 'percent'){
                                $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) && array_sum($text_answer[$cate_id][$ques_id]) <= 100 ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }else{
                                $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }

                            $course_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $course_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $course_answer->save();

                            if ($course_question->type == 'matrix' && $course_question->multiple == 0 && $course_answer->is_row == 1){
                                $title_report[] = isset($ans_code) ? $ans_code : 'null';

                                $arr_col_answer = RatingQuestionAnswer2::where('course_rating_level_id', $course_rating_level_id)
                                    ->where('course_id', $course_id)
                                    ->where('course_type', $course_type)
                                    ->where('question_id', '=', $course_question->question_id)
                                    ->where('is_row', '=', 0)
                                    ->pluck('id')->toArray();

                                $item_check = $check_answer_matrix[$cate_id][$ques_id][$ans_id][0];
                                foreach ($arr_col_answer as $key => $item){
                                    if (isset($item_check) && $item == $item_check){
                                        $content_report[] = ($key + 1);
                                    }
                                }
                            }
                        }
                    }

                    if (in_array($course_question->type, ['text', 'sort', 'percent', 'number'])){
                        $arr_export = RatingLevelCourseAnswer::where('course_question_id', $course_question->id)->get();
                        foreach ($arr_export as $export) {
                            $title_report[] = isset($export->answer_code) ? $export->answer_code : 'null';
                            $content_report[] = isset($export->text_answer) ? $export->text_answer : 'null';
                        }
                    }

                    if (($course_question->type == 'matrix' && $course_question->multiple == 1) || $course_question->type == 'matrix_text'){
                        if(isset($answer_matrix_code[$cate_id][$ques_id])) {
                            foreach ($answer_matrix_code[$cate_id][$ques_id] as $ans_key => $matrix) {

                                $answer_matrix_text = isset($answer_matrix[$cate_id][$ques_id][$ans_key]) ? $answer_matrix[$cate_id][$ques_id][$ans_key] : '';
                                $i = 0;

                                foreach ($matrix as $matrix_key => $matrix_code){
                                    RatingLevelCourseAnswerMatrix::query()
                                        ->updateOrCreate([
                                            'course_question_id' => $course_question->id,
                                            'answer_row_id' => $ans_key,
                                            'answer_col_id' => $matrix_key
                                        ],[
                                            'answer_code' => $matrix_code
                                        ]);

                                    $title_report[] = isset($matrix_code) ? $matrix_code : 'null';

                                    $check = isset($check_answer_matrix[$cate_id][$ques_id][$ans_key]) ? $check_answer_matrix[$cate_id][$ques_id][$ans_key] : [];

                                    if(($course_question->type == 'matrix' && $course_question->multiple == 1)){
                                        $content_report[] = in_array($matrix_key, $check) ? 1 : 0;
                                    }

                                    if($course_question->type == 'matrix_text'){
                                        $content_report[] = $answer_matrix_text ? $answer_matrix_text[$i] : 'null';
                                    }

                                    $i += 1;
                                }
                            }
                        }
                    }

                }
            }
        }

        if ($send == 1){
            if (count($title_report) > 0){
                foreach ($title_report as $key => $title){
                    $export = new RatingLevelCourseExport();
                    $export->course_rating_level_id = $course_rating_level_id;
                    $export->level = $level;
                    $export->user_id = $user_id;
                    $export->user_type = $user_type;
                    $export->course_id = $course_id;
                    $export->course_type = $course_type;
                    $export->title = $title;
                    $export->content = isset($content_report[$key]) ? $content_report[$key] : '';
                    $export->save();
                }
            }
        }

        if (url_mobile()){
            if ($view_type == 'rating_level'){
                $redirect = route('module.rating_level');
            }else {
                $redirect = $course_type == 1 ? route('themes.mobile.frontend.online.detail', ['course_id' => $course_id]) : route('themes.mobile.frontend.offline.detail', ['course_id' => $course_id]);
            }
        }else{
            if ($view_type == 'rating_level'){
                $redirect = route('module.rating_level');
            }else{
                $redirect = $course_type == 1 ? route('module.online.detail_online', ['id' => $course_id]) : route('module.offline.detail', ['id' => $course_id]);
            }
        }

        if ($send == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã gửi thành công',
                'redirect' => $redirect,
            ]);
        }else{
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.rating_level.edit_course', [$course_id, $course_type, $course_rating_level_id, $rating_user]).'?rating_level_object_id='.$rating_level_object_id.'&view_type='.$view_type,
            ]);
        }
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
        $user_type = getUserType();

        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->user_type = $user_type;
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

    protected function updateMailUserRatingLevel(array $params, array $user_id, int $object_id, $object_type){
        $automail = new Automail();
        $automail->template_code = 'action_plan_reminder_01';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $object_id;
        $automail->object_type = $object_type;
        $automail->addToAutomail();
    }

    protected function updateNotifyUserRatingLevel(array $params, array $user_id){
        $nottify_template = NotifyTemplate::query()->where('code', '=', 'action_plan_reminder_01')->first();
        $subject_notify = $this->mapParams($nottify_template->title, $params);
        $content_notify = $this->mapParams($nottify_template->content, $params);
        $url = $this->getParams($params, 'url');

        $notify = new Notify();
        $notify->subject = $subject_notify;
        $notify->content = $content_notify;
        $notify->url = $url;
        $notify->users = $user_id;
        $notify->addMultiNotify();
    }

    protected function mapParams($content, $params) {
        foreach ($params as $key => $param) {
            if ($key == 'url') {
                $content = str_replace('{'. $key .'}', '<a target="_blank" href="'. $param .'">liên kết này</a>', $content);
            }
            else {
                $content = str_replace('{'. $key .'}', $param, $content);
            }
        }
        return $content;
    }

    protected function getParams($params, $key) {
        if (isset($params->{$key})) {
            return $params->{$key};
        }

        return null;
    }
}
