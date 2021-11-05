<?php

namespace Modules\Libraries\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Libraries\Entities\LibrariesBookmark
 *
 * @property int $id
 * @property int $libraries_id
 * @property int $type
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereUserId($value)
 * @mixin \Eloquent
 */
class LibrariesBookmark extends Model
{
    protected $table = 'el_libraries_bookmark';
    protected $primaryKey = 'id';
    protected $fillable = [
        'libraries_id',
        'type',
        'user_id',
    ];

    public static function checkExist($libraries_id, $type){
        $check = self::query()
            ->where('libraries_id', '=', $libraries_id)
            ->where('type', '=', $type)
            ->where('user_id', '=', \Auth::id());

        return $check->exists();
    }
}
