<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class BC23 extends Model
{
    public static function sql($title_id)
    {
        $query = \DB::table('el_titles');
        $query->where(['id'=>$title_id])->select('id','code','name','employees')->orderBy('name');
        return $query;
    }
    public static function getRateComplete($title_id){
        $data = \DB::table('el_level_subject as a')
            ->join('el_training_roadmap_finish as b','a.id','=','b.level_subject_id')
            ->where('b.title_id',$title_id)
            ->select('a.id','a.code','a.name','b.user_finish')
            ->orderBy('a.name')
            ->get();

        return $data;
    }
}
