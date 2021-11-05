<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Footer
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $link_youtobe
 * @property string|null $link_fb
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereLinkFb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereLinkYoutobe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Footer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Footer extends Model
{
    protected $table = 'el_footer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'link_youtobe',
        'link_fb',
        'status',
    ];
}
