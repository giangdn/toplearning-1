<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\DailyTraining\Entities\DailyTrainingUserViewVideo;
use Modules\DailyTraining\Entities\DailyTrainingVideo;

class BC39 extends Model
{
    public static function countViewVideoInMonth($day){
        $query = DailyTrainingUserViewVideo::query()
            ->select('view_video.*')
            ->from('el_daily_training_user_view_video as view_video')
            ->where(\DB::raw('day('.\DB::getTablePrefix().'view_video.time_view)'), '=', $day)
            ->where(\DB::raw('month('.\DB::getTablePrefix().'view_video.time_view)'), '=', date('m'))
            ->where(\DB::raw('year('.\DB::getTablePrefix().'view_video.time_view)'), '=', date('Y'));

        return $query->count();
    }
}
