<?php

namespace Modules\PointHist\Entities;

use App\Config;
use Illuminate\Database\Eloquent\Model;
use App\Profile;

class PointHist extends Model
{
    const TYPE_REFERER =1 ;
    const TYPE_COURSE_REFERER =2 ;
    const TYPE_COURSE_REFERER_FINISH =3 ;
    const TYPE_COURSE_COMPLETE= 4 ;
    const TYPE_GIFT_POINT = 5 ;
    const NAME_REGISTER_COURSE = 'Giới thiệu đăng ký khóa học';
    const NAME_COURSE_COMPLETE = 'Học viên hoàn thành khóa học';
    protected $table = 'el_point_hist';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'referer',
        'point'
    ];

    public static function savePointHist($referer)
    {
        $point_config = Config::where('name', '=', 'point_course_referer')->value('value');
        $user_refer = Profile::where('id_code', '=', $referer)->value('user_id');
        $point = new PointHist();
        $point->user_id = \Auth::id();
        $point->name = self::NAME_REGISTER_COURSE;
        $point->type = self::TYPE_COURSE_REFERER;
        $point->referer = $user_refer;
        $point->point = (int)$point_config;
        $point->save();
    }
}
