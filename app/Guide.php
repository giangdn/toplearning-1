<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Slider
 *
 * @property int $id
 * @property string $image
 * @property string|null $description
 * @property string $location
 * @property int $status
 * @property int $display_order
 * @property string|null $url
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUrl($value)
 * @mixin \Eloquent
 * @property string $name
 * @property string $attach
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Guide whereAttach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Guide whereName($value)
 */
class Guide extends BaseModel
{
    protected $table = 'el_guide';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'attach',
    ];
}
