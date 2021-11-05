<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LoginHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereUserId($value)
 * @mixin \Eloquent
 * @property string $user_code
 * @property string $user_name
 * @property int $number_hits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereNumberHits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereUserCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LoginHistory whereUserName($value)
 * @property-read \App\User|null $user
 */
class LoginHistory extends Model
{
    protected $table = 'el_login_history';
    protected $fillable = [
        'user_id',
        'user_code',
        'user_name',
        'ip_address',
        'number_hits',
    ];
    
    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function setLoginHistory() {
        $user_id = \Auth::id();
        $profile = Profile::whereUserId($user_id)->first();
        
        $user = LoginHistory::where('user_id', '=', $user_id)
            ->where('user_type',1)
            ->orderBy('created_at', 'DESC')
            ->first(['number_hits']);

        $model = new LoginHistory();
        $model->user_id = $user_id;
        $model->user_code = $profile->code;
        $model->user_name = $profile->lastname . ' ' . $profile->firstname;
        $model->ip_address = request()->ip();
        $model->user_type = 1;
        if ($user){
            $model->number_hits = $user->number_hits + 1;
        }else{
            $model->number_hits = 1;
        }

        $model->save();
    }

    public static function getLoginHistoryByYear($user_id){
        $user_login = LoginHistory::where('user_id', '=', $user_id)
            ->where(\DB::raw('year(created_at)'), '=', date('Y'))
            ->count();

        return $user_login;
    }
}
