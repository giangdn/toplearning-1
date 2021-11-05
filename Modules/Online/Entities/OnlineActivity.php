<?php

namespace Modules\Online\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineActivity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineCourseActivity[] $online_activities
 * @property-read int|null $online_activities_count
 */
class OnlineActivity extends Model
{
    use ChangeLogs;
    protected $table = 'el_online_activity';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'description',
        'icon'
    ];
    
    public function online_activities() {
        return $this->hasMany('Modules\Online\Entities\OnlineCourseActivity', 'activity_id', 'id');
    }
    
    public function openEmbed() {
        switch ($this->id) {
            // case 1: return true;
            // case 2: return true;
            case 3: return true;
            case 4: return true;
            case 5: return true;
            // case 6: return true;
        }
        return false;
    }
}
