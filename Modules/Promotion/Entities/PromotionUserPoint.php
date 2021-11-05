<?php

namespace Modules\Promotion\Entities;

use App\Config;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Modules\RefererHist\Entities\RefererHist;

/**
 * Modules\Promotion\Entities\PromotionUserPoint
 *
 * @property int $id
 * @property int $userid
 * @property int $point
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $level_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint whereLevelId($value)
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserPoint whereUserId($value)
 */
class PromotionUserPoint extends Model
{
    protected $table = 'el_promotion_user_point';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'user_type',
        'point',
        'level_id',
        'created_at',
        'updated_at'
    ];

    public static function getMaxPoint(){
        $max_point = self::max('point');

        return $max_point;
    }

    public static function updatePointReferer($referer)
    {
        $user_id = \Auth::id();
        $point_refer = Config::where('name','=','grade_refer')->value('value');
        $user_refer = Profile::where('id_code','=',$referer)->value('user_id');
        PromotionUserPoint::updateOrCreate(['user_id'=>$user_refer],['point'=>\DB::raw('point+'. (int)$point_refer)]);
        //update người được giới thiệu
        $point_refered = Config::where('name','=','grade_refered')->value('value');
        PromotionUserPoint::updateOrCreate(['user_id'=>$user_id],['point'=>\DB::raw('point+'. (int)$point_refered)]);
        // lưu hist refer
        $refer_hist = new RefererHist();
        $refer_hist->referer = $referer;
        $refer_hist->user_id = $user_id;
        $refer_hist->point = $point_refer;
        $refer_hist->save();
    }
    public static function updatePointRegisterCourse($referer)
    {
        $point_course_refer = Config::where('name','=','point_course_referer')->value('value');
        $user_refer = Profile::where('id_code','=',$referer)->value('user_id');
        PromotionUserPoint::updateOrCreate(['user_id'=>$user_refer],['point'=>\DB::raw('point+'. (int)$point_course_refer)]);

    }
}
