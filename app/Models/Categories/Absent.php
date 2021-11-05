<?php

namespace App\Models\Categories;

use App\BaseModel;
use App\ProfileStatus;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\Absent
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Absent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absent whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Absent extends BaseModel
{
    protected $table="el_absent";
	protected $fillable = [
        'code',
        'name',
        'status'
    ];

	protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã vắng mặt',
            'name' => 'Tên vắng mặt',
            'status' => 'Trạng thái',
        ];
    }
    public static function syncAPIAbsent($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";;
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            Absent::updateOrCreate(['code'=>$item->nghi_ma,'name'=>$item->nghi_ten,'status'=>$item->tinhtrangsd]);
        }
    }
}
