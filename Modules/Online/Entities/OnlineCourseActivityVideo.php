<?php

namespace Modules\Online\Entities;

use App\Helpers\VideoStream;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityFile
 *
 * @property int $id
 * @property string $path
 * @property string $extension
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityFile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Warehouse|null $warehouse
 * @property int $course_id
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityVideo whereCourseId($value)
 */
class OnlineCourseActivityVideo extends Model
{
    protected $table = 'el_online_course_activity_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'extension',
        'path',
        'description',
    ];

    public function warehouse() {
        return $this->hasOne('App\Warehouse', 'file_path', 'path');
    }

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('uploads/'.$this->path),
        ]);

        //return route('stream.video', [$file]);
        return route('module.online.view_video', [$file]);
    }
}
