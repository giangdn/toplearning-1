<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileStatus
 *
 * @property int $id
 * @property string $name 1: đang làm việc, 2: thử việc, 3:tạm hoãn, 4: nghỉ việc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $code
 * @property int|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileStatus whereStatus($value)
 */
class ProfileStatus extends Model
{
    protected $table = 'el_profile_status';
    protected $fillable =[
        'id',
        'code',
        'name',
        'status'
    ];
    public static function syncAPIProfileStatus($url,$param)
    {
        $client = new Client();
        $data = $client->request('get',$url)->getBody()->getContents();
//        \Storage::disk('public')->put("api/{$param}.json",$data);
        $file = \storage_path('app/public/api/'). "{$param}.json";;
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            ProfileStatus::updateOrCreate(['code'=>$item->nghi_ma,'name'=>$item->nghi_ten,'status'=>$item->tinhtrangsd]);
        }
    }
}
