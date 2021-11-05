<?php

namespace App\Models\Categories;

use App\Profile;
use App\Traits\ChangeLogs;
use App\UnitView;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\API\Entities\API;

/**
 * App\Models\Categories\Unit
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $level
 * @property string|null $parent_code
 * @property int $status
 * @property int|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Categories\Unit[] $el_unit
 * @property-read \App\Models\Categories\Unit $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @property-read int|null $el_unit_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Unit whereEmail($value)
 * @property string|null $note1
 * @property string|null $note2
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $profiles
 * @property-read int|null $profiles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereNote1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereNote2($value)
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $area_id
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit active()
 */
class Unit extends Model
{
    use ChangeLogs;
    protected $table = 'el_unit';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'level',
        'parent_code',
        'status',
        'type',
		'email',
        'note1',
        'note2',
        'area_id',
    ];

    public function el_unit()
    {
        return $this->hasMany(Unit::class);
    }

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_code','code');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class,'unit_code','code');
    }

    public static function getMaxUnitLevel() {
        $query = self::query();
        $query->from('el_unit_name');
        $query->select(\DB::raw('MAX(level) AS num_max'));
        return $query->first()->num_max;
    }

    public static function getMaxUnitLevelWithValue() {
        $query = Unit::query();
        $query->from('el_unit as un');
        $query->join('el_unit as u','u.level','=','un.level');
        $query->select(\DB::raw('MAX(mdl_un.level) AS num_max'));
        return $query->first()->num_max;
    }

    public static function getUnitParent($level, $exclude_id = 0, $parent_id = null, $prefix = '', &$result = []) {
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('level', '=', $level);
        $query->where('parent_id', '=', $parent_id);
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->id == $exclude_id) continue;
            $result[] = ['id' => $row->id, 'name' => $prefix.' '. $row->name];

            self::getUnitParent($level, $exclude_id, $row->id, $prefix.'--', $result);
        }

        return $result;
    }

    public static function getAttributeName() {
        return [
            'code' => 'Mã đơn vị',
            'name' => 'Tên đơn vị',
            'level' => 'Cấp bậc',
            'status' => 'Trạng thái',
            'parent_id' => 'Đơn vị quản lý',
            'type' => 'Loại đơn vị'
        ];
    }

    public static function deleteArray($ids) {
        foreach ($ids as $id) {
            $unit = Unit::find($id);
            $childs = self::where('parent_code' ,'=' , $unit->code)->pluck('id')->toArray();

            if ($childs){
                json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
            }else{
                self::deleteArray($childs);
                self::destroy([$id]);
            }
        }
    }

    public static function getLevelName($level) {
        $query = self::query();
        $query->select(['name', 'name_en']);
        $query->from('el_unit_name');
        $query->where('level', '=', $level);
        if ($query->exists()) {
            return $query->first();
        }

        return '';
    }

    public static function getTreeParentUnit($unit_code, &$result = []) {
        $records = self::whereCode($unit_code)->get();
        foreach ($records as $record) {
            $result[$record->level] = $record;
            if ($record->parent_code) {
                self::getTreeParentUnit($record->parent_code, $result);
            }
        }

        return $result;
    }

    public static function getParentUnitCode($unit_code, &$result = []) {
        $records = self::whereCode($unit_code)->get();
        foreach ($records as $record) {
            $result[] = $record->code;
            if ($record->parent_code) {
                self::getParentUnitCode($record->parent_code, $result);
            }
        }

        return $result;
    }

    public static function getArrayChild($code, &$result = []) {
        $query = Unit::query();
        $rows = $query->where('parent_code', '=', $code)->get();

        foreach ($rows as $row) {
            $result[] = $row->id;
            self::getArrayChild($row->code, $result);
        }

        return $result;
    }

    public static function countChild($code){
        $query = Unit::where('parent_code', '=', $code)->where('status', '=', 1)->count();

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public static function getUnitByLevel($level)
    {
        return self::active()->where(['level'=>$level])->select('id','code','name')->get();
    }

    public static function isOverLevel($unit_model, $num)
    {
        $unitByUser = Profile::getUnitManagerByUser();
        $unit = UnitView::find($unit_model);
        $object_id = $unit->object_id;
        $unit_t = $object_id-$num;
        if($unit_t<=0)
            return false;
        if (in_array($unit->{'unit'.$unit_t.'_id'}, $unitByUser))
            return true;
        return false;
    }

    public static function getHierarchyByUnit($unit_id)
    {
        $unitView = UnitView::findOrFail($unit_id);
        $str =[]; $i=0;
        $level = 1+(int)$unitView->object_id;
        while ($level) {
            $str[]=$unitView->{'unit'.$i.'_id'};
            $i += 1;
            $level--;
        }
        return implode('/', $str);
    }

    public static function syncAPIUnit($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";;
        $dataJson = collect( json_decode(file_get_contents($file)));
        for ($i=1;$i<=4;$i++){
            $dataLevel = $dataJson->filter(function ($value) use ($i){
                return ($value->dv_cap==$i and $value->tinhtrangsd==1);
            });
            foreach ($dataLevel as $index => $item) {
                if($i==1){
                    if ($item->dv_ma==$item->dv_ma_parent) {
                        $parent = null;
                        $level=1;
                    }
                    else{
                        $parent = $item->dv_ma_parent;
                        $level = 2;
                    }
                    if ($parent==null){
                        $parent=@Unit::where('code',$item->dv_ma)->value('parent_code');
                    }
                }else{
                    $parent = $item->dv_ma_parent;
                    $cap = \DB::table('el_unit')->where('code',$parent)->value('level');
                    $level = (int)$cap+1;
                }
//                $parent = ($i==1) ? null: $item->dv_ma_parent;
                if ($i>1){
                    $exists = Unit::where(['code'=>$parent])->exists();
                    if (!$exists)
                        continue;
                }
                Unit::updateOrCreate(['code'=>$item->dv_ma],[
                    'name'=>$item->dv_ten,
                    'level'=>$level,
                    'parent_code'=>$parent,
                    'status'=>$item->tinhtrangsd,
                ]);
            }
        }

    }
}
