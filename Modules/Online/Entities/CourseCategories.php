<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\CourseCategories
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $type
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\CourseCategories whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseCategories extends Model
{
    protected $table = 'el_course_categories';
    protected $fillable = [];
    protected $primaryKey = 'id';
}
