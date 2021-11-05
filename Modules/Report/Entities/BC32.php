<?php

namespace Modules\Report\Entities;

use App\Models\Categories\Unit;
use Illuminate\Database\Eloquent\Model;

class BC32 extends Model
{
    public static function getQuery() {
        $query = \DB::query();
        $query->select([
            'id',
            'name'
        ]);
    
        $query->from('el_training_form');
        return $query;
    }
    
    public static function getTotalObject($training_form, $type, $from_date, $to_date, $unit) {
        if ($type == 1) {
            $query = \DB::query();
            $query->from('el_profile AS profile');
            $query->join('el_titles AS title', 'title.code', '=', 'profile.title_code');
            $query->join('el_online_object AS object', 'object.title_id', '=', 'title.id');
            $query->join('el_online_course AS online', 'online.id', '=', 'object.course_id');
            
            if ($unit) {
                $unit = Unit::where('id', '=', $unit)->first();
                $query->where('profile.unit_code', '=', $unit->code);
            }
            
            $query->where('online.training_form_id', '=', $training_form);
            $query->where('online.start_date', '>=', $from_date . ' 00:00:00');
            $query->where('online.start_date', '<=', $to_date . ' 23:59:59');
            return $query->count();
        }
        
        if ($type == 2) {
            $query = \DB::query();
            $query->from('el_profile AS profile');
            $query->join('el_titles AS title', 'title.code', '=', 'profile.title_code');
            $query->join('el_offline_object AS object', 'object.title_id', '=', 'title.id');
            $query->join('el_offline_course AS offline', 'offline.id', '=', 'object.course_id');
            $query->where('offline.training_form_id', '=', $training_form);
            $query->where('offline.start_date', '>=', $from_date . ' 00:00:00');
            $query->where('offline.start_date', '<=', $to_date . ' 23:59:59');
            return $query->count();
        }
        
        return 0;
    }
    
    public static function getTotalJoin($training_form, $type, $from_date, $to_date, $unit) {
        if ($type == 1) {
            $query = \DB::query();
            $query->from('el_online_register AS register');
            $query->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id');
            $query->join('el_online_course AS course', 'course.id', '=', 'register.course_id');
            $query->where('register.status', '=', 1);
            $query->where('course.training_form_id', '=', $training_form);
            $query->where('course.start_date', '>=', $from_date . ' 00:00:00');
            $query->where('course.start_date', '<=', $to_date . ' 23:59:59');
    
            if ($unit) {
                $unit = Unit::where('id', '=', $unit)->first();
                $query->where('profile.unit_code', '=', $unit->code);
            }
            
            return $query->count();
        }
        
        if ($type == 2) {
            $query = \DB::query();
            $query->from('el_offline_register AS register');
            $query->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id');
            $query->join('el_offline_course AS course', 'course.id', '=', 'register.course_id');
            $query->where('register.status', '=', 1);
            $query->where('course.training_form_id', '=', $training_form);
            $query->where('course.start_date', '>=', $from_date . ' 00:00:00');
            $query->where('course.start_date', '<=', $to_date . ' 23:59:59');
    
            if ($unit) {
                $unit = Unit::where('id', '=', $unit)->first();
                $query->where('profile.unit_code', '=', $unit->code);
            }
            
            return $query->count();
        }
    }
    
    public static function getTotalCompleted($training_form, $type, $from_date, $to_date, $unit) {
        if ($type == 1) {
            $query = \DB::query();
            $query->from('el_online_register AS register');
            $query->join('el_online_result AS result', 'result.register_id', '=', 'register.id');
            $query->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id');
            $query->join('el_online_course AS course', 'course.id', '=', 'register.course_id');
            $query->where('register.status', '=', 1);
            $query->where('course.training_form_id', '=', $training_form);
            $query->where('course.start_date', '>=', $from_date . ' 00:00:00');
            $query->where('course.start_date', '<=', $to_date . ' 23:59:59');
            $query->where('result.result', '=', 1);
            
            if ($unit) {
                $unit = Unit::where('id', '=', $unit)->first();
                $query->where('profile.unit_code', '=', $unit->code);
            }
        
            return $query->count();
        }
    
        if ($type == 2) {
            $query = \DB::query();
            $query->from('el_offline_register AS register');
            $query->join('el_offline_result AS result', 'result.register_id', '=', 'register.id');
            $query->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id');
            $query->join('el_offline_course AS course', 'course.id', '=', 'register.course_id');
            $query->where('register.status', '=', 1);
            $query->where('course.training_form_id', '=', $training_form);
            $query->where('course.start_date', '>=', $from_date . ' 00:00:00');
            $query->where('course.start_date', '<=', $to_date . ' 23:59:59');
            $query->where('result.result', '=', 1);
        
            if ($unit) {
                $unit = Unit::where('id', '=', $unit)->first();
                $query->where('profile.unit_code', '=', $unit->code);
            }
        
            return $query->count();
        }
        
        return 0;
    }
    
    public static function getTotalAbsent($training_form, $type, $from_date, $to_date, $unit) {
        return 0;
    }
}
