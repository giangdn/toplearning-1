<?php

namespace Modules\Libraries\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Libraries\Entities\LibrariesCategory
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Libraries\Entities\Libraries[] $library
 * @property-read int|null $library_count
 */
class LibrariesCategory extends BaseModel
{
    protected $table = 'el_libraries_category';
    protected $fillable = [
        'name',
        'parent_id',
        'type',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';
    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
            'type' => 'Loại danh mục',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
        ];
    }

    public static function getLibrariesParent($type, $exclude_id = 0, $parent_id = null, $prefix = '', &$result = []) {
        $query = self::query();
        $query->where('type', '=', $type);
        $query->where('parent_id', '=', $parent_id);
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->id == $exclude_id) continue;
            $result[] = ['id' => $row->id, 'name' => $prefix.' '. $row->name];

            self::getLibrariesParent($type, $exclude_id, $row->id, $prefix.'--', $result);
        }

        return $result;
    }

    public static function getCategory($type = null)
    {
        $query = self::query();
        if ($type){
            $query->where('type', '=', $type);
        }

        return $query->get();
    }

    public function library()
    {
        return $this->hasMany('Modules\Libraries\Entities\Libraries','category_id', 'id');
    }

    public function cateChild($parent_id, $type)
    {
        $query = self::query();
        $query->where('parent_id', '=', $parent_id);
        $query->where('type', '=', $type);
        $rows = $query->get();
        return $rows;
    }

    public static function getTreeParentUnit($id, &$result = []) {
        $records = self::where('id',$id)->get();
        foreach ($records as $key => $record) {
            $result[] = $record;
            if ($record->parent_id) {
                self::getTreeParentUnit($record->parent_id, $result);
            }
        }

        return $result;
    }
}
