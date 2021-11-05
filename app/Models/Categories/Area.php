<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\Area
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $level
 * @property string|null $parent_code
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Categories\Area[] $el_area
 * @property-read int|null $el_area_count
 * @property-read \App\Models\Categories\Area $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Area whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $unit_id
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereUnitId($value)
 */
class Area extends Model
{
    protected $table = 'el_area';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'level',
        'parent_code',
        'status',
        'unit_id',
    ];

    public function el_area()
    {
        return $this->hasMany(Area::class);
    }

    public function parent()
    {
        return $this->belongsTo(Area::class);
    }

    public static function getMaxAreaLevel() {
        $query = self::query();
        $query->from('el_area_name');
        $query->select(\DB::raw('MAX(level) AS num_max'));
        return $query->first()->num_max;
    }

    public static function getAreaParent($level, $exclude_id = 0, $parent_id = null, $prefix = '', &$result = []) {
        $query = self::query();
        $query->where('id', '!=', $exclude_id);
        $query->where('status', '=', 1);
        $query->where('level', '<', $level);
        $query->where('parent_code', '=', $parent_id);
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->id == $exclude_id) continue;
            $result[] = ['id' => $row->id, 'name' => $prefix.' '. $row->code .' - '. $row->name];

            self::getAreaParent($level, $exclude_id, $row->code, $prefix.'--', $result);
        }

        return $result;
    }

    public static function getAttributeName() {
        return [
            'code' => 'Mã vùng miền',
            'name' => 'Tên vùng miền',
            'level' => 'Cấp bậc',
            'status' => 'Trạng thái',
            'parent_code' => 'Đơn vị quản lý',
        ];
    }

    public static function deleteArray($ids) {
        foreach ($ids as $id) {
            $area = Area::find($id);
            $childs = self::where('parent_code' ,'=' , $area->code)->pluck('id')->toArray();

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
        $query->from('el_area_name');
        $query->where('level', '=', $level);
        if ($query->exists()) {
            return $query->first();
        }

        return '';
    }

    public static function getTreeParentArea($area_code, &$result = []) {
        $records = self::where('code', '=', $area_code)->get();
        foreach ($records as $record) {
            $result[$record->level] = $record;
            if ($record->parent_code) {
                self::getTreeParentArea($record->parent_code, $result);
            }
        }

        return $result;
    }

    public static function getParentArea2($area_code, &$result = []) {
        $records = self::where('code', '=', $area_code)->where('level','!=',1)->get();
        foreach ($records as $record) {
            $result[$record->level] = $record->code;
            if ($record->parent_code) {
                self::getParentArea2($record->parent_code, $result);
            }
        }

        return $result;
    }

    public static function getArrayChild($code, &$result = []) {
        $query = Area::query();
        $rows = $query->where('parent_code', '=', $code)->get();

        foreach ($rows as $row) {
            $result[] = $row->id;
            self::getArrayChild($row->code, $result);
        }

        return $result;
    }
}
