<?php

namespace Modules\RefererHist\Entities;

use Illuminate\Database\Eloquent\Model;

class RefererHist extends Model
{
    public $table = 'el_referer_hist';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'referer',
        'point',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => 'Mã nhân viên',
            'referer' => 'Mã giới thiệu',
            'point' => 'Điểm giới thiệu',
        ];
    }
    public static function existsRefer($referer)
    {
        $user_id = \Auth::id();
        return RefererHist::where('referer','=',$referer)->where('user_id','=',$user_id)->exists();
    }
}
