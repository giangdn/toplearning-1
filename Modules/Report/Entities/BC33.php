<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class BC33 extends Model
{
    public static function countCourseActive($training_form, $type, $from_date, $to_date) {
        $today = date('Y-m-d');
        $count1 = 0;
        $count2 = 0;
        
        if ($type == 1) {
            $query = OnlineCourse::where('status', '=', 1)
                ->where('start_date', '>=', $from_date . ' 00:00:00')
                ->where('start_date', '<=', $to_date . ' 23:59:59')
                ->where('start_date', '>=', $today . ' 00:00:00');
            
            if ($training_form) {
                $query->whereIn('training_form_id', $training_form);
            }
            
            $count1 = $query->count();
        }
        
        if ($type == 2) {
            $query = OfflineCourse::where('status', '=', 1)
                ->where('start_date', '>=', $from_date . ' 00:00:00')
                ->where('start_date', '<=', $to_date . ' 23:59:59')
                ->where('start_date', '>=', $today . ' 00:00:00');
    
            if ($training_form) {
                $query->whereIn('training_form_id', $training_form);
            }
    
            $count2 = $query->count();
        }
        
        return ($count1 + $count2);
    }
    
    public static function countCourseUpcoming($training_form, $type, $from_date, $to_date) {
        $today = date('Y-m-d');
        $count1 = 0;
        $count2 = 0;
    
        if ($type == 1) {
            $query = OnlineCourse::where('status', '=', 1)
                ->where('start_date', '>=', $from_date . ' 00:00:00')
                ->where('start_date', '<=', $to_date . ' 23:59:59')
                ->where('start_date', '<', $today . ' 00:00:00');
            
            if ($training_form) {
                $query->whereIn('training_form_id', $training_form);
            }
            
            $count1 = $query->count();
        }
    
        if ($type == 2) {
            $query = OfflineCourse::where('status', '=', 1)
                ->where('start_date', '>=', $from_date . ' 00:00:00')
                ->where('start_date', '<=', $to_date . ' 23:59:59')
                ->where('start_date', '<', $today . ' 00:00:00')
                ->count();
    
            if ($training_form) {
                $query->whereIn('training_form_id', $training_form);
            }
    
            $count2 = $query->count();
        }
    
        return ($count1 + $count2);
    }
    
    public static function countCourseFinished($training_form, $type, $from_date, $to_date) {
        $today = date('Y-m-d');
        $count1 = 0;
        $count2 = 0;
        
        if ($type == 1) {
            $query = OnlineCourse::where('status', '=', 1)
                ->where('start_date', '>=', $from_date . ' 00:00:00')
                ->where('start_date', '<=', $to_date . ' 23:59:59')
                ->where('end_date', '<', $today . ' 00:00:00');
    
            if ($training_form) {
                $query->whereIn('training_form_id', $training_form);
            }
    
            $count1 = $query->count();
        }
        
        if ($type == 2) {
            $query = OfflineCourse::where('status', '=', 1)
                ->where('start_date', '>=', $from_date . ' 00:00:00')
                ->where('start_date', '<=', $to_date . ' 23:59:59')
                ->where('end_date', '<', $today . ' 00:00:00');
    
            if ($training_form) {
                $query->whereIn('training_form_id', $training_form);
            }
    
            $count2 = $query->count();
        }
        
        return ($count1 + $count2);
    }
}
