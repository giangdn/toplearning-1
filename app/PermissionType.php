<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PermissionType
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property int|null $sort
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionType extends BaseModel
{
    protected $table = 'el_permission_type';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'sort',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public static function getAttributeName() {
        return [
            'name' => 'Tên',
            'type' => 'Loại',
            'description' => 'Miêu tả',
        ];
    }

    public static function getPermissionType($type=2)
    {
        return PermissionType::select(['id','name','type','description'])->where('type', '=', $type)->get();
    }

}
