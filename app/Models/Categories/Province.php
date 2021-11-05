<?php

namespace App\Models\Categories;

use App\BaseModel;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\Province
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Province whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Province extends BaseModel
{
    protected $table = 'el_province';
    protected $primaryKey = 'id';
    protected $fillable = ['id','code','name'];
    public static function getAttributeName() {
        return [
            'code'    =>'Mã tỉnh thành',
            'name' => 'Tên tỉnh thành'
        ];
    }
    public static function syncAPIProvince($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";;
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            Province::updateOrCreate(['code'=>$item->tt_ma],[
                'name'=>$item->tt_ten,
            ]);
        }
    }
}
