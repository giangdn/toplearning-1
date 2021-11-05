<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Feedback
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $position
 * @property int $star
 * @property string $content
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Feedback extends Model
{
    protected $table = 'el_feedback';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'content',
        'position',
        'star',
        'created_by',
        'image',
    ];
    public static function getAttributeName() {
        return [
            'name' => 'Tên',
            'content' => 'Nội dung phản hồi',
            'position' => 'Chức vụ',
            'star' => 'Số sao',
            'created_by' => 'Người tạo',
            'image' => 'Hình ảnh',
        ];
    }
}
