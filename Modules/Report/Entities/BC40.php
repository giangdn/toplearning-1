<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class BC40 extends Model
{
    public static function countRegister($unit_code, $area_code, $start_date, $end_date, $course_type){
        $prefix = \DB::getTablePrefix();

        $query = \DB::query();
        $query->from('el_course_register_view as a')
            ->join('el_course_view as b',function ($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->join('el_profile as c', 'a.user_id','=','c.user_id')
            ->where('a.status','=',1)
            ->where('c.status','=',1)
            ->whereNotExists(function ($subquery) use ($start_date, $end_date){
                $subquery->select(['id'])
                    ->from('el_course_complete')
                    ->whereColumn('course_id', '=', 'b.id')
                    ->whereColumn('course_type','=','b.course_type')
                    ->whereColumn('user_id','=','a.user_id')
                    ->where(function ($sub) use ($start_date, $end_date){
                        $sub->orWhere('created_at', '<', $start_date)
                            ->orWhere('created_at', '>', $end_date);
                    });
            })
            ->where(function ($sub) use ($start_date, $end_date){
                $sub->orWhere(function ($sub1) use ($end_date){
                    $sub1->whereNull('b.end_date')
                        ->where('b.start_date', '<=', $end_date);
                });
                $sub->orWhere(function ($sub2) use ($start_date, $end_date){
                    $sub2->whereNotNull('b.end_date')
                        ->where('b.start_date', '<=', $end_date)
                        ->where('b.end_date', '>=', $start_date);
                });
            });
        if ($course_type){
            $query->where('b.course_type', '=', $course_type);
        }
        if ($unit_code){
            $query->where('c.unit_code', '=', $unit_code);
        }
        if ($area_code){
            $query->where('c.area_code', '=', $area_code);
        }

        return $query->count();
    }

    public static function countCompleted($unit_code, $area_code, $start_date, $end_date, $course_type)
    {
        $prefix = \DB::getTablePrefix();

        $query = \DB::query();
        $query->from('el_course_register_view as a')
            ->join('el_course_view as b',function ($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->join('el_profile as c', 'a.user_id','=','c.user_id')
            ->where('a.status','=',1)
            ->where('c.status','=',1)
            ->whereExists(function ($subquery) use ($start_date, $end_date){
                $subquery->select(['id'])
                    ->from('el_course_complete')
                    ->whereColumn('course_id', '=', 'b.id')
                    ->whereColumn('course_type','=','b.course_type')
                    ->whereColumn('user_id','=','a.user_id')
                    ->where(function ($sub) use ($start_date, $end_date){
                        $sub->where('created_at', '>=', $start_date)
                            ->where('created_at', '<=', $end_date);
                    });
            })
            ->where(function ($sub) use ($start_date, $end_date){
                $sub->orWhere(function ($sub1) use ($end_date){
                    $sub1->whereNull('b.end_date')
                        ->where('b.start_date', '<=', $end_date);
                });
                $sub->orWhere(function ($sub2) use ($start_date, $end_date){
                    $sub2->whereNotNull('b.end_date')
                        ->where('b.start_date', '<=', $end_date)
                        ->where('b.end_date', '>=', $start_date);
                });
            });
        if ($course_type){
            $query->where('b.course_type', '=', $course_type);
        }
        if ($unit_code){
            $query->where('c.unit_code', '=', $unit_code);
        }
        if ($area_code){
            $query->where('c.area_code', '=', $area_code);
        }
        return $query->count();
    }
}
