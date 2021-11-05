<?php

namespace App;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\WarehouseFolder
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WarehouseFolder whereUserId($value)
 * @mixin \Eloquent
 * @property string $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Warehouse[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseFolder whereType($value)
 */
class WarehouseFolder extends BaseModel
{
    protected $table = 'el_warehouse_folder';
    protected $primaryKey = 'id';

    public function files() {
        return $this->hasMany('App\Warehouse', 'folder_id', 'id');
    }

    public static function getDirectories($path, $type = 'image') {
        $parent_id = (int) $path > 0 ? $path : null;
        WarehouseFolder::addGlobalScope(new CompanyScope());
        $query = WarehouseFolder::where('parent_id', '=', $parent_id)
            ->where('type', '=', $type);

        if ($query->exists()) {
            $rows = $query->get();
            $result = [];

            foreach ($rows as $row) {
                $result[] = (object) [
                    'id' => $row->id,
                    'name' => $row->name,
                    'url' => '',
                    'size' => '',
                    'updated' => strtotime($row->updated_at),
                    'path' => $row->id,
                    'time' => $row->created_at,
                    'type' => 'folder',
                    'icon' => 'fa-folder-o',
                    'thumb' => asset('styles/file-manager/images/folder.png'),
                    'is_file' => false
                ];
            }

            return $result;
        }

        return [];
    }

    public static function checkExists($folder_name, $parent_folder = null) {
        $query = self::query();
        $query->where('name', '=', $folder_name);
        $query->where('parent_id', '=', $parent_folder);
        return $query->exists();
    }

    public static function getParent($folder_id) {
        $query = WarehouseFolder::query();
        $query->where('id', '=', $folder_id);
        if ($query->exists()) {
            $parent_id = $query->first()->parent_id;
            if (empty($parent_id)) {
                return -1;
            }

            return $parent_id;
        }
        return '';
    }
}
