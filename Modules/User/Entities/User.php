<?php

namespace Modules\User\Entities;

use App\Console\Commands\Title;
use App\Models\Categories\Absent;
use App\Models\Categories\Province;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Profile;
use App\UserMeta;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\API\Entities\API;
use phpDocumentor\Reflection\Types\Self_;
use Shetabit\Visitor\Traits\Visitor;
use Shetabit\Visitor\Traits\Visitable;
/**
 * Class User
 *
 * @package Modules\User\Entities
 * @mixin \Eloquent
 * @property int $id
 * @property string $auth
 * @property int $confirmed
 * @property int $policyagreed
 * @property int $deleted
 * @property int $suspended
 * @property int $mnethostid
 * @property string $username
 * @property string $password
 * @property string $idnumber
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property int $emailstop
 * @property string $icq
 * @property string $skype
 * @property string $yahoo
 * @property string $aim
 * @property string $msn
 * @property string $phone1
 * @property string $phone2
 * @property string $institution
 * @property string $department
 * @property string $address
 * @property string $city
 * @property string $country
 * @property string $lang
 * @property string $calendartype
 * @property string $theme
 * @property string $timezone
 * @property int $firstaccess
 * @property int $lastaccess
 * @property int $lastlogin
 * @property int $currentlogin
 * @property string $lastip
 * @property string $secret
 * @property int $picture
 * @property string $url
 * @property string|null $description
 * @property int $descriptionformat
 * @property int $mailformat
 * @property int $maildigest
 * @property int $maildisplay
 * @property int $autosubscribe
 * @property int $trackforums
 * @property int $timecreated
 * @property int $timemodified
 * @property int $trustbitmask
 * @property string|null $imagealt
 * @property string|null $lastnamephonetic
 * @property string|null $firstnamephonetic
 * @property string|null $middlename
 * @property string|null $alternatename
 * @property string|null $last_online
 * @property-read \Illuminate\Database\Eloquent\Collection|\Shetabit\Visitor\Models\Visit[] $visitLogs
 * @property-read int|null $visit_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Shetabit\Visitor\Models\Visit[] $visits
 * @property-read int|null $visits_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User online($seconds = 180)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAlternatename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAutosubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCalendartype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescriptionformat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailstop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstaccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstnamephonetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIcq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImagealt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastaccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastnamephonetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaildigest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaildisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMailformat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMnethostid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePolicyagreed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSkype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimecreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimemodified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrackforums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrustbitmask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereYahoo($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
class User extends Model
{
    use Visitor, Visitable;
    protected $fillable = [
        'username',
        'auth',
        'password',
        'firstname',
        'lastname',
        'email',
        'api',
        'code'
    ];
    public $table = 'user';
    public static function countProfile()
    {
        $query = self::where('status','=',1);
        return $query->count();
    }

    public static function getRoleAndManagerUnitUser()
    {
        $user = \Auth::user();
        if (Permission::isAdmin()){
            $unit = \DB::table('el_profile_view')->where('user_id',$user->id)->select('unit_id','unit_code','unit_name')->first();
            $data[] = (object)['id'=>$unit->unit_id,'code'=>$unit->unit_code,'name'=>$unit->unit_name];
            return $data;
        }

        $role =$user->roles()->value('id');
        $units = \DB::table('el_role_has_permission_type as a')->join('el_permission_type_unit as b','a.permission_type_id','=','b.permission_type_id')
        ->join('el_unit as c','c.id','=','b.unit_id')->where('a.role_id',$role)->get(['id','code','name']);
        $data =[];
        foreach ($units as $index => $unit) {
            $data[] = (object)['id' =>$unit->id,'code'=>$unit->code,'name'=>$unit->name];
        }
        $unitmanagers=UnitManager::getUnitManagedByUser();
        foreach ($unitmanagers as $index => $unitmanager) {
            $data[] = (object)['id'=>$unitmanager->id,'code'=>$unitmanager->code, 'name'=>$unitmanager->name.' (TĐV)'];
        }
        return $data;
    }

    public static function syncAPIUser($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            if (!$item->nv_ma)
                continue;
            if ($item->tinhtrang==1 && !$item->nv_email)
                continue;
//            if ($item->nv_ma !='7779')
//                continue;
            $title = Titles::where(['code'=>$item->cd_ma])->first('id');
            $unit = Unit::where(['code'=>$item->dv_ma])->first('id');
            $leave_type= Absent::where(['code'=>$item->nghi_loai])->first('id');
            $title_id = $title?$title->id:null;
            $unit_id = $unit?$unit->id:null;
            $leave_type_id = $item->nghi_loai ? $leave_type->id:null;
            if ($item->tinhtrang!=1) {
                if ($item->nv_email)
                    $email = 'nghiviec_'.$item->nv_ma."_". time() . $item->nv_email;
                else
                    $email = 'nghiviec_'.$item->nv_ma."_" . time() . '@vietabank.com.vn';
            }
            else{
                $email = $item->nv_email;
            }

            $user_name = $email;
            $user = User::updateOrCreate(['code'=>$item->nv_ma],[
                'username'=>$user_name,
                'auth'=>'microsoft',
                'password'=> \Hash::make($item->nv_ma),
                'firstname'=>$item->nv_ten,
                'lastname'=>$item->nv_ho,
                'email'=>$email,
                'api'=>1
            ]);

            $noicap = @Province::where(['code'=> $item->nv_noicap])->first()->name;
            Profile::updateOrCreate(['id'=>$user->id],[
                'id'=>$user->id,
                'user_id'=>$user->id,
                'code'=>$item->nv_ma,
                'firstname'=>$item->nv_ten,
                'lastname'=>$item->nv_ho,
                'email'=>$email,
                'join_company'=> $item->nv_ngayvao?date_convert($item->nv_ngayvao):null,
                'date_off'=> $item->nv_ngaynghi?date_convert($item->nv_ngaynghi):null,
                'address'=>$item->nv_thuongtru,
                'current_address'=>$item->nv_diachi,
                'gender'=>$item->nv_gioitinh=='Nam' ?1:0,
                'dob'=>$item->nv_ngaysinh?date_convert($item->nv_ngaysinh):null,
                'identity_card'=>$item->nv_socmnd,
                'date_range'=>$item->nv_ngaycap?date_convert($item->nv_ngaycap):null,
                'issued_by'=>$noicap,
                'phone'=>$item->nv_dienthoai,
                'majors'=>$item->nv_chuyenmon,
                'title_id'=>$title_id,
                'title_code'=>$item->cd_ma,
                'certificate_code'=>$item->td_ma,
                'unit_id'=>$unit_id,
                'unit_code'=>$item->dv_ma,
                'leave_type_id'=>$leave_type_id,
                'status'=>$item->tinhtrang,
                'marriage'=>($item->nv_honnhan=='Kết hôn'?1:0),
            ]);
        }
    }
}
