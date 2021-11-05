<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EmulationProgram
 *
 * @property int $id
 * @property string|null $imgae
 * @property string $code
 * @property string $name
 * @property string $time_start
 * @property string $time_end
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmulationProgram whereDescription($value)
 * @mixin \Eloquent
 */
class EmulationProgram extends BaseModel
{
    protected $table = 'el_emulation_program';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'code',
        'name',
        'time_start',
        'time_end',
        'description',
        'status',
        'approved',
        'isopen',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public static function getAttributeName() {
        return [
            'image' => 'Hình ảnh',
            'code' => 'Mã chương trình',
            'name' => 'Tên chương trình',
            'time_start' => 'Thời gian bắt đầu',
            'time_end' => 'Thời gian kết thúc',
            'description' => 'Mô tả',
        ];
    }

    public function getObject() {
        $query = \DB::query();
        $rows = $query->from('el_emulation_program_object AS a')
            ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->leftjoin('el_unit as c', 'c.id', '=', 'a.unit_id')
            ->where('a.emulation_id', '=', $this->id)
            ->get([
                'b.name as title_name',
                'c.name as unit_name'
            ]);

        $obj = [];
        foreach ($rows as $item){
            if ($item->title_name){
                $obj[] = $item->title_name;
            }
            if ($item->unit_name){
                $obj[] = $item->unit_name;
            }
        }

        return implode(', ', $obj);
    }
}
