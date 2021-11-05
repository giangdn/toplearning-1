<?php

namespace Modules\Libraries\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Modules\Libraries\Entities\LikeLibraries
 *
 * @property int $id
 * @property int $user_id
 * @property string $libraries_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LikeLibraries extends Model
{
    protected $table = 'el_like_libraries';
    protected $fillable = [
        'user_id',
        'libraries_id',
    ];
    protected $primaryKey = 'id';
    public static function getAttributeName() {
        return [
            'user_id' => 'Người dùng',
            'libraries_id' => 'Thư viện',
        ];
    }
}
