<?php

namespace App;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/**
 * App\UnitView
 *
 * @property int $id
 * @property int $unit1_id
 * @property string $unit1_code
 * @property string|null $unit1 Tập đoàn
 * @property int|null $unit2_id
 * @property string|null $unit2_code
 * @property string|null $unit2 Công ty
 * @property int|null $unit3_id
 * @property string|null $unit3_code
 * @property string|null $unit3 Phòng ban level 3
 * @property int|null $unit4_id
 * @property string|null $unit4_code
 * @property string|null $unit4 Kênh quản lý level 4
 * @property int|null $unit5_id
 * @property string|null $unit5_code
 * @property string|null $unit5 Bộ phận gián tiếp level 5
 * @property int|null $unit6_id
 * @property string|null $unit6_code
 * @property string|null $unit6 Bộ phận trực tiếp level 6
 * @property int|null $unit7_id
 * @property string|null $unit7_code
 * @property string|null $unit7 Vùng level 7
 * @property int|null $unit8_id
 * @property string|null $unit8_code
 * @property string|null $unit8 Khu vực level 8
 * @property int|null $unit9_id
 * @property string|null $unit9_code
 * @property string|null $unit9 Chi nhánh tỉnh level 9
 * @property int|null $unit10_id
 * @property string|null $unit10_code
 * @property string|null $unit10 Cửa hàng level 10
 * @property int $object_id
 * @property int $status
 * @property string $unit_code Mã đơn vị
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit10Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit10Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit1Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit3Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit4Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit4Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit5Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit5Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit6Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit6Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit7Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit7Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit8Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit8Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit9Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnit9Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $area_id
 * @property string|null $area_code
 * @property string|null $area
 * @property int|null $area_level
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereAreaLevel($value)
 * @property string|null $area_name
 * @method static \Illuminate\Database\Eloquent\Builder|UnitView whereAreaName($value)
 */
class UnitView extends Model
{
    protected $table = 'el_unit_view';
    protected $fillable=['id','object_id','status',
        'unit_code','unit1_id','unit1_code','unit1','unit0_id','unit0_code','unit0',
        'unit2_id','unit2_code','unit2','unit3_id','unit3_code','unit3','unit4_id','unit4_code','unit4','unit5_id','unit5_code',
        'unit5','unit6_id','unit6_code','unit6','unit7_id','unit7_code','unit7','unit8_id','unit8_code','unit8','unit9_id','unit9_code','unit9','unit10_id','unit10_code','unit10',
        'area_id','area_code','area_name','area_level'];
    public static function updateUnitView($unit_id)
    {
        try {
            $unit = Unit::find($unit_id);

            $data1 = ['status'=>$unit->status,'object_id'=>$unit->level,'unit_code'=>$unit->code];
            $data2 = self::mapField($unit->code);
            $area = Area::find($unit->area_id);
            $data3 = ['area_id'=>$unit->area_id,'area_code'=>$area->code,'area'=>$area->name,'area_level'=>$area->level];
            $data = array_merge($data1,$data2,$data3);
            UnitView::query()->updateOrCreate([
                'id'=>$unit_id
            ],$data);
        } catch (QueryException $e) {
            throw new \Exception($e);
        }
    }
    public static function mapField($unit_code, &$data=[]){
        try {
            $record = Unit::where(['code' => $unit_code])->first();
            $data["unit{$record->level}_id"] = $record->id;
            $data["unit{$record->level}_code"] = $record->code;
            $data["unit{$record->level}"] = $record->name;
            if ($record->parent_code)
                self::mapField($record->parent_code, $data);
        }catch (\Exception $e){
            dd($e->getMessage());
        }
        return $data;
    }
}
