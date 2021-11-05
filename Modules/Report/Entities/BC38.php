<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\DailyTraining\Entities\DailyTrainingVideo;

class BC38 extends Model
{
    public static function countViewVideoInMonth($unit_id){
        $query = DailyTrainingVideo::query()
            ->select('a.*')
            ->from('el_daily_training_video as a')
            ->leftJoin('el_daily_training_user_view_video as view_video', 'view_video.video_id', '=', 'a.id')
            ->leftJoin('el_profile as b', 'b.user_id', '=', 'view_video.user_id')
            ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
            ->where('a.approve', '=', 1)
            ->where('a.status', '=', 1)
            ->where('c.id', '=', $unit_id)
            ->where(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', date('m'))
            ->where(\DB::raw('year('.\DB::getTablePrefix().'a.created_at)'), '=', date('Y'));

        return $query->count();
    }
}
