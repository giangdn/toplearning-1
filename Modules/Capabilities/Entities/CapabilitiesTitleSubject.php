<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesTitleSubject extends Model
{
    protected $table = 'el_capabilities_title_subject';
    protected $fillable = [
        'capabilities_title_id',
        'subject_id',
        'level'
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'capabilities_title_id' => 'Khung năng lực theo chức danh',
            'subject_id' => 'Học phần',
            'level' => 'Cấp độ'
        ];
    }

    public static function checkSubjectExits($capabilities_title_id, $subject_id)
    {
        $query = self::query();
        $query->where('capabilities_title_id', '=', $capabilities_title_id);
        $query->where('subject_id', '=', $subject_id);
        return $query->exists();
    }
}
