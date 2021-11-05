<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\DailyTraining\Entities\DailyTrainingVideo;

class BC37 extends Model
{
    public static function countVideoInMonth($unit_id){
        $query = DailyTrainingVideo::query()
            ->select('a.*')
            ->from('el_daily_training_video as a')
            ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.created_by')
            ->leftJoin('el_unit as c', 'c.code', '=', 'b.unit_code')
            ->where('a.approve', '=', 1)
            ->where('a.status', '=', 1)
            ->where('c.id', '=', $unit_id)
            ->where(\DB::raw('month('.\DB::getTablePrefix().'a.created_at)'), '=', date('m'))
            ->where(\DB::raw('year('.\DB::getTablePrefix().'a.created_at)'), '=', date('Y'));

        return $query->count();
    }
}
