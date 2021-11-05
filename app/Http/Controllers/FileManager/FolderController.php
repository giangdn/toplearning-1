<?php

namespace App\Http\Controllers\FileManager;

use App\Warehouse;
use App\WarehouseFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends LfmController
{
    public function getFolders()
    {
        $root_folders = [];
        $type = $this->currentLfmType();
        $folders = WarehouseFolder::getDirectories(0, $type);

        $root_folders[] = (object) [
            'name' => 'Thư mục gốc',
            'path' => 0,
            'children' => $folders,
            'has_next' => false,
        ];

        return view('file-manager.tree')
            ->with(compact('root_folders'));
    }

    public function getAddfolder(Request $request)
    {
        $folder_name = $request->post('name');
        $type = $this->currentLfmType();
        $current_folder = trim($request->post('parent'));

        if ($current_folder <= 0) {
            $current_folder = null;
        }

        if (empty($folder_name)) {
            return $this->error('folder-name');
        }

        $folder = new WarehouseFolder();
        $folder->name = $folder_name;
//        $folder->user_id = Auth::id();
        $folder->type = $type;
        $folder->parent_id = $current_folder;
        $folder->save();

        return parent::$success_response;
    }

    public function delete(Request $request){
        $id = $request->post('id');
        $is_file = $request->post('is_file');

        if ($is_file){
            $folder = Warehouse::find($id);
            $folder->delete();
        }else{
            $folder = WarehouseFolder::find($id);
            $folder->delete();
        }

        return parent::$success_response;
    }
}
