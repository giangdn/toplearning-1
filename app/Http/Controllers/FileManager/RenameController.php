<?php

namespace App\Http\Controllers\FileManager;

use App\Warehouse;
use App\WarehouseFolder;

class RenameController extends LfmController
{
    public function getRename()
    {
        $id = request('id');
        $old_name = $this->translateFromUtf8(request('file'));
        $new_name = $this->translateFromUtf8(trim(request('new_name')));
        $type = request('type');
        if ($type=='folder') {
            return $this->renameDirectory($id, $old_name, $new_name);
        } else {
            return $this->renameFile($id, $old_name, $new_name);
        }
    }

    protected function renameDirectory($id, $old_name, $new_name)
    {
        if (empty($new_name)) {
            return $this->error('folder-name');
        }

        $old_file = parent::getCurrentPath($old_name);
        $new_file = parent::getCurrentPath($new_name);
        $folder = WarehouseFolder::findOrFail($id);
        $folder->name= $new_name;
        $folder->save();
        return parent::$success_response;
    }

    protected function renameFile($id, $old_name, $new_name)
    {
        if (empty($new_name)) {
            return parent::error('file-name');
        }

        $old_file = parent::getCurrentPath($old_name);
        $extension = \File::extension($old_file);
        $new_filename = \Str::slug(basename(substr($new_name, 0, 50), "." . $extension)) .'-'. time() .'-'. \Str::random(10) .'.' . $extension;
        $file = Warehouse::findOrFail($id);
        $file_path = $file->file_path;
        $split = explode('/', $file_path);
        array_pop($split);
        $path = implode('/', $split);
        $storage =  \Storage::disk('upload');
        $old_file = $storage->path('').$file_path;
        $new_file = $storage->path('').$path.'/'.$new_filename;

        rename($old_file,$new_file);

        $file->file_name=$new_name;
        $file->file_path=$path.'/'.$new_filename;
        $file->save();
        return parent::$success_response;
    }
}
