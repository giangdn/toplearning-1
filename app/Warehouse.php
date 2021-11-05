<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Warehouse
 *
 * @property int $id
 * @property string $file_name
 * @property string $file_type
 * @property string $file_path
 * @property int $file_size
 * @property string $extension
 * @property string $source
 * @property int|null $folder_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Warehouse whereUserId($value)
 * @mixin \Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereType($value)
 */
class Warehouse extends BaseModel
{
    protected $table = 'el_warehouse';
    protected $primaryKey = 'id';

    public function getFileUrl() {
        return upload_file($this->file_path);
    }

    public static function getLinkPlay($file_path) {
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $file = encrypt_array([
            'file_path' => $file_path,
            'path' => $storage->path($file_path),
        ]);

        return route('stream.video', [$file]);
    }
}
