<?php

namespace Modules\Rating\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RatingTemplate2 extends BaseModel
{
    protected $table = 'el_rating_template2';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'course_id',
        'course_type',
        'code',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã mẫu',
            'name' => 'Tên mẫu',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
        ];
    }

    public function category()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingCategory2','template_id');
    }
}
