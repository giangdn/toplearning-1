<?php

namespace Modules\Capabilities\Entities;

use App\Permission;
use App\Models\Categories\UnitManager;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineResult;

class CapabilitiesResult extends Model
{
    protected $table = 'el_capabilities_result';
    protected $primaryKey = 'id';

    public static function getAllUserReviewed($start_date=null, $end_date=null) {
        $query = self::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.lastname',
            'a.firstname',
            'a.code',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS parent_name'
        ])
            ->from('el_profile AS a')
            ->leftJoin('el_titles AS b', 'b.code', '=', 'a.title_code')
            ->leftJoin('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code')
            ->whereIn('a.user_id', function ($subquery) use ($start_date, $end_date){
                $subquery->select(['user_id'])
                    ->from('el_capabilities_review')
                    ->where('status', '=', 1);

                if ($start_date) {
                    $subquery->where('created_at', '>=', $start_date);
                }

                if ($end_date) {
                    $subquery->where('created_at', '<=', $end_date);
                }
            });
        if (!Permission::isAdmin()) {
            $query->whereIn('c.id', UnitManager::getArrayUnitManagedByUser());
        }

        return $query->get();
    }

    public static function getLastReviewUser($user_id) {
        $query = self::query();
        $query->from('el_capabilities_review')
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 1)
            ->orderBy('id', 'DESC');
        return $query->first();
    }

    public static function getSubjectMissingUser($user_id, $start_date=null, $end_date=null) {
        $last_review = self::getLastReviewUser($user_id);
        $query = self::query();
        $query->select([
            'review_detail.*',
            'subject.id AS subject_id',
            'subject.code AS subject_code',
            'subject.name AS subject_name',
            'capabilities.id AS capabilities_id',
            'capabilities.code AS capabilities_code',
            'capabilities.name AS capabilities_name',
        ])
            ->from('el_capabilities_review_detail AS review_detail')
            ->join('el_capabilities_review AS review', 'review.id', '=', 'review_detail.review_id')
            ->join('el_capabilities_title_subject AS title_subject', 'title_subject.capabilities_title_id', '=', 'review_detail.captitle_id')
            ->join('el_subject AS subject', 'subject.id', '=', 'title_subject.subject_id')
            ->join('el_capabilities AS capabilities', 'capabilities.id', '=', 'review_detail.capabilities_id')
            ->where('review.id', '=', $last_review->id)
            ->whereColumn('review_detail.practical_level', '<', 'review_detail.standard_level')
            ->whereColumn('review_detail.practical_level', '<', 'title_subject.level')
            ->whereColumn('review_detail.standard_level', '>=', 'title_subject.level');

        return $query->get();
    }

    public static function getCourseBySubject($user_id) {
        $last_review = self::getLastReviewUser($user_id);
        $query = self::query();
        $query->select([
            'review_detail.group_name',
            'review_detail.capabilities_code',
            'review_detail.capabilities_name',
            'review_detail.captitle_id',
            'review_detail.standard_level',
            'title_subject.level',
            'course.id as course_id',
            'course.name as course_name',
            'course.course_type',
        ])
            ->from('el_capabilities_review_detail AS review_detail')
            ->leftJoin('el_capabilities_title_subject AS title_subject', 'title_subject.capabilities_title_id', '=', 'review_detail.captitle_id')
            ->leftJoin('el_course_view as course', 'course.subject_id', '=', 'title_subject.subject_id')
            ->where('review_detail.review_id', '=', $last_review->id)
            ->whereColumn('review_detail.practical_level', '<', 'review_detail.standard_level')
            ->whereColumn('review_detail.practical_level', '<=', 'title_subject.level')
            ->whereColumn('review_detail.standard_level', '>=', 'title_subject.level');

        return $query->get();
    }

    public static function getCourseStandard($user_id)
    {
        $result = [];
        $course_list = self::getCourseBySubject($user_id);
        foreach ($course_list as $course){
            $captitle = CapabilitiesTitle::find($course->captitle_id);
            if ($captitle->level == $course->level){
                $result[] = $course;
            }
        }
        return $result;
    }

    public static function getCourseNeedAdditional($user_id)
    {
        $result = [];
        $course_list = self::getCourseBySubject($user_id);
        foreach ($course_list as $course){
            $captitle = CapabilitiesTitle::find($course->captitle_id);
            if ($captitle->level > $course->level){
                $result[] = $course;
            }
        }
        return $result;
    }

    public static function getPercent($user_id)
    {
        $off = 0;
        $onl = 0;
        $course_list = self::getCourseBySubject($user_id);
        foreach ($course_list as $course) {
            if ($course->course_type == 1){
                $onl = OnlineResult::where('course_id', '=', $course->course_id)
                    ->where('user_id', '=', $user_id)
                    ->where('result', '=', 1)
                    ->count();
            }else{
                $off = OfflineResult::where('course_id', '=', $course->course_id)
                    ->where('user_id', '=', $user_id)
                    ->where('result', '=', 1)
                    ->count();
            }
        }

        return (($onl + $off) / ($course_list->count() > 0 ? $course_list->count() : 1)) * 100;
    }

    public static function getCourseComplete($user_id, $course_id, $course_type)
    {
        if ($course_type == 1){
            $course = OnlineResult::where('course_id', '=', $course_id)
                ->where('user_id', '=', $user_id)
                ->where('result', '=', 1)
                ->first();
        }else{
            $course = OfflineResult::where('course_id', '=', $course_id)
                ->where('user_id', '=', $user_id)
                ->where('result', '=', 1)
                ->first();
        }

        return $course;
    }

    public static function getCourseNowByMonth($user_id, $month)
    {
        $last_review = self::getLastReviewUser($user_id);
        $query = self::query()
            ->from('el_capabilities_review_detail AS review_detail')
            ->leftJoin('el_capabilities_title_subject AS title_subject', 'title_subject.capabilities_title_id', '=', 'review_detail.captitle_id')
            ->leftJoin('el_course_view as course', 'course.subject_id', '=', 'title_subject.subject_id')
            ->leftJoin('el_course_complete as complete', function ($join){
                $join->on('complete.course_id','=','course.id');
                $join->on('complete.course_type','=','course.course_type');
            })
            ->where('complete.user_id', '=', $user_id)
            ->where('review_detail.review_id', '=', $last_review->id)
            ->whereColumn('review_detail.practical_level', '<', 'review_detail.standard_level')
            ->whereColumn('review_detail.practical_level', '<=', 'title_subject.level')
            ->whereColumn('review_detail.standard_level', '>=', 'title_subject.level')
            ->where(\DB::raw('month('.\DB::getTablePrefix().'complete.created_at)'), '=', $month)
            ->count();

        return $query;
    }

    public static function getCourseOldByMonth($user_id, $month)
    {
        $last_review = self::getLastReviewUser($user_id);

        $cap_review = CapabilitiesReview::query()
            ->select(['id'])
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 1)
            ->where('id', '!=', $last_review->id)
            ->orderBy('id', 'DESC')
            ->first();

        $query = self::query()
            ->from('el_capabilities_review_detail AS review_detail')
            ->leftJoin('el_capabilities_title_subject AS title_subject', 'title_subject.capabilities_title_id', '=', 'review_detail.captitle_id')
            ->leftJoin('el_course_view as course', 'course.subject_id', '=', 'title_subject.subject_id')
            ->leftJoin('el_course_complete as complete', function ($join){
                $join->on('complete.course_id','=','course.id');
                $join->on('complete.course_type','=','course.course_type');
            })
            ->where('complete.user_id', '=', $user_id)
            ->where('review_detail.review_id', '=', $cap_review->id)
            ->whereColumn('review_detail.practical_level', '<', 'review_detail.standard_level')
            ->whereColumn('review_detail.practical_level', '<=', 'title_subject.level')
            ->whereColumn('review_detail.standard_level', '>=', 'title_subject.level')
            ->where(\DB::raw('month('.\DB::getTablePrefix().'complete.created_at)'), '=', $month)
            ->count();

        return $query;
    }
}
