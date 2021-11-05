<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\PermissionGroup
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionGroup whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionGroup extends Model
{
    protected $table = 'el_permission_group';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    public static function getAttributeName() {
        return [
            'name' => 'Tên nhóm quyền'
        ];
    }
}
