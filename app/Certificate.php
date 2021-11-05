<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\ProfileLevel;

/**
 * App\Certificate
 *
 * @property int $id
 * @property string $certificate_code
 * @property string $certificate_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereCertificateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereCertificateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Certificate extends BaseModel
{
    protected $table = 'el_cert';
    protected $fillable = [
        'certificate_code',
        'certificate_name',
        'status'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'certificate_code' => 'Mã trình độ',
            'certificate_name' => 'Tên trình độ',
        ];
    }
    public static function syncAPICertificate($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            Certificate::updateOrCreate(['certificate_code'=>$item->td_ma],[
                'certificate_name'=>$item->td_ten,
                'status'=>(int)$item->tinhtrangsd,
            ]);
        }
    }
}
