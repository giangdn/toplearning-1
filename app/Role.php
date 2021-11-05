<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Role
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $guard_name
 * @property string $description
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedBy($value)
 */
class Role extends BaseModel
{
    protected $table = 'el_roles';
    protected $fillable = [
        'code',
        'name',
        'type',
        'guard_name',
        'description',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã vai trò',
            'name' => 'Tên vai trò',
            'type' => 'Loại vai trò',
            'description' => 'Miêu tả vai trò',
        ];
    }
    /*public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $user = \Auth::user();
            $model->created_by = $user->id;
            $model->updated_by = $user->id;
        });
        static::updating(function($model)
        {
            $user = \Auth::user();
            $model->updated_by = $user->id;
        });
    }*/
}
