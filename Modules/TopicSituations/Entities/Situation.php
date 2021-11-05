<?php

namespace Modules\TopicSituations\Entities;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;

class Situation extends Model
{
    protected $table = 'el_situation';
    protected $fillable = [
        'name',
        'code',
        'description',
        'topic_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên',
            'code' => 'Mã',
            'description' => 'Mô tả',
        ];
    }
}
