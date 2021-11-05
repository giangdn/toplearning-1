<?php

namespace App\Models\Categories;

use App\BaseModel;
use App\Profile;
use App\Traits\ChangeLogs;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\Online\Entities\OnlineObject;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Categories\TitleRank
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $units
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUnits($value)
 * @property int|null $unit_id
 * @property int|null $unit_level
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUnitLevel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $profiles
 * @property-read int|null $profiles_count
 */
class TitleRank extends BaseModel
{
    protected $table = 'el_title_rank';
    protected $fillable = [
        'code',
        'name',
        'status'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã chức danh',
            'name' => 'Tên chức danh',
            'status' => 'Trạng thái'
        ];
    }
    public static function syncAPITitleRank($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";;
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            TitleRank::updateOrCreate(['code'=>$item->cbac_ma,'name'=>$item->cbac_ten,'status'=>$item->tinhtrangsd]);
        }
    }
}
