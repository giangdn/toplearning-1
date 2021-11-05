<?php

namespace App;

use Carbon\Carbon;
use http\Client\Request;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

/**
 * App\Visits
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $method
 * @property string|null $url
 * @property string|null $referer
 * @property string|null $useragent
 * @property string|null $device
 * @property string|null $device_type
 * @property string|null $device_cate
 * @property string|null $platform
 * @property string|null $browser
 * @property string|null $ip
 * @property int $visitor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Visits newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visits newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visits query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereDeviceCate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereUseragent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visits whereVisitorId($value)
 */
class Visits extends Model
{
    protected $table = 'el_visits';
    protected $fillable = [
        'method',
        'url',
        'referer',
        'useragent',
        'device',
        'platform',
        'browser',
        'ip',
        'visitor_id',
        'device_type',
        'device_cate',
    ];
    
    public static function saveVisits( )
    {
        $agent = new Agent();
        $data = [
            'method' => \Request::getMethod(),
            'url' => \Request::fullUrl(),
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
            'useragent' => \Request::userAgent() ?? '',
            'device' => $agent->device(),
            'device_type' =>$agent->deviceType(),
            'device_cate' => self::getDeviceCate(),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'ip' => \Request::ip(),
            'visitor_id' => \Auth::id() ?? null
        ];
        self::create($data);
    }
    
    private static function getDeviceCate(){
        $agent = new Agent();
        if($agent->isTablet())
            return 'tablet';
        elseif($agent->isMobile())
            return 'mobile';
        elseif($agent->isDesktop())
            return 'desktop';
        return 'desktop';
    }
}
