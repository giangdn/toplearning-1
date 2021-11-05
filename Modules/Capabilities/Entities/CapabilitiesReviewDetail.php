<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesReviewDetail extends Model
{
    protected $table = 'el_capabilities_review_detail';
    protected $primaryKey = 'id';
    protected $fillable = [];
    public $timestamps = false;

    public static function getGroup($review_id) {
        $query = self::query();
        return $query->select(['group_id AS id', 'group_name AS name'])
            ->where('review_id', '=', $review_id)
            ->groupBy(['group_id', 'group_name'])
            ->get();
    }

    public static function getByGroup($group_id, $review_id) {
        $query = self::query();
        return $query->select([
            'capabilities_id AS id',
            'capabilities_code AS code',
            'capabilities_name AS name',
            'standard_weight AS weight',
            'standard_critical_level AS critical_level',
            'standard_level AS level',
            'standard_goal AS goal',
            'practical_level',
            'practical_goal'
        ])
            ->where('review_id', '=', $review_id)
            ->where('group_id', '=', $group_id)
            ->get();
    }

    public static function getByCapabilitiesTitle($captitle_id, $review_id) {
        $query = self::query();
        return $query->where('captitle_id', '=', $captitle_id)
            ->where('review_id', '=', $review_id)
            ->first();
    }
}
