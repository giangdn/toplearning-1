<?php

namespace Modules\Libraries\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Modules\Libraries\Entities\LibrariesObject
 *
 * @property int $id
 * @property int $libraries_id
 * @property int $type
 * @property int|null $status
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereUserId($value)
 * @mixin \Eloquent
 */
class LibrariesRatting extends Model
{
    protected $table = 'el_libraries_ratting';
    protected $fillable = [
        'libraries_id',
        'user_id',
        'ratting',
    ];
    protected $primaryKey = 'id';
}
