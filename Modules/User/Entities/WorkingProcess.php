<?php

namespace Modules\User\Entities;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\ManagerLevel
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_manager_id
 * @property int $level
 * @property string $start_date
 * @property string|null $end_date
 * @property int $approve
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserManagerId($value)
 * @mixin \Eloquent
 * @property string|null $title_code
 * @property string|null $unit_code
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereUnitCode($value)
 * @property string|null $user_code
 * @property string|null $title_name
 * @property string|null $unit_name
 * @property int|null $api 1 api
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereTitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingProcess whereUserCode($value)
 */
class WorkingProcess extends Model
{
    protected $table = 'el_working_process';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_code',
        'user_id',
        'start_date',
        'end_date',
        'title_code',
        'title_name',
        'unit_code',
        'unit_name',
        'note',
        'api',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
        ];
    }
    public static function syncAPIWorkingProcess($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";;
        $dataJson = collect( json_decode(file_get_contents($file)));
        foreach ($dataJson as $index => $item) {
            WorkingProcess::updateOrCreate(['user_code'=>$item->nv_ma,'title_code'=>$item->cd_ma,'unit_code'=>$item->dv_ma],[
                'unit_name'=>$item->dv_ten,
                'title_name'=>$item->cd_ten,
                'start_date'=>date_convert($item->ngayhl),
                'end_date'=>date_convert($item->ngayhethan),
                'api'=>1,
            ]);
        }
        $prefix = \DB::getTablePrefix();
        \DB::table('el_working_process')->join('el_profile','el_working_process.user_code','=','el_profile.code')
            ->whereNull('el_working_process.user_id')->update(['el_working_process.user_id'=> \DB::raw($prefix.'el_profile.id')]);
    }
}
