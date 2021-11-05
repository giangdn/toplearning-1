<?php

namespace App\Http\Controllers\FileManager;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use App\Warehouse;
use App\WarehouseFolder;
use Illuminate\Http\Request;
use App\Profile;
use Modules\Role\Entities\TitleRole;
use App\Permission;
use Illuminate\Support\Facades\Auth;

class ItemsController extends LfmController
{
    public function getItems(Request $request)
    {
        $path = $request->input('working_dir');
        $type = $this->currentLfmType();
        $sort = $request->input('sort_type');
        $files = $this->getFiles($path, $type,$sort);
        $directories = WarehouseFolder::getDirectories($path, $type);
        $previous_dir = WarehouseFolder::getParent($path);

        return [
            'html' => (string)view($this->getView())->with([
                'files' => $files,
                'directories' => $directories,
                'items' => array_merge($directories, $files)
            ]),
            'working_dir' => $path,
            'previous_dir' => $previous_dir
        ];
    }

    public function stream($path) {

        return response()->file($path);
    }

    private function getFiles($path, $type, $sort) {
        $folder_id = (int) $path > 0 ? $path : null;

        $admin = Auth::user()->isAdmin();
        $profile = Profile::where('user_id', \Auth::id())->first();
        $check_user_role = Profile::query()
            ->from('el_profile as a')
            ->join('el_model_has_roles as ur','ur.model_id','=','a.user_id')
            ->where('user_id', \Auth::id())
            ->first();
        $check_title_role = TitleRole::where('title_id',$profile->title_id)->first();
        if(!$admin && empty($check_user_role) && empty($check_title_role)) {
            $query = Warehouse::where('folder_id', '=', $folder_id)
                ->where('created_by', '=', \Auth::id())
                ->where('type', '=', $type)
                ->where(function($sub){
                    $sub->orWhereNull('check_role');
                    $sub->orWhere('check_role',0);
                });
        } else {
            Warehouse::addGlobalScope(new CompanyScope());
            $query = Warehouse::where('folder_id', '=', $folder_id)
                ->where('type', '=', $type)
                ->where('created_by', '=', \Auth::id())
                ->where(function($sub){
                    $sub->orWhereNull('check_role');
                    $sub->orWhere('check_role',0);
                });
        }

        if ($query->exists()) {
            if ($sort=='time')
                $query->orderBy('updated_by');
            elseif ($sort=='alphabetic')
                $query->orderBy('file_name');
            $rows = $query->get();
            $result = [];
            $image_type = config('lfm.storage.image.mimetypes');
            $file_icon_array = config('lfm.file_icon_array');

            foreach ($rows as $row) {
                $file_url = $row->getFileUrl();
                $thumb = in_array($row->file_type, $image_type) ? $file_url : null;

                $result[] = (object) [
                    'id' => $row->id,
                    'name' => $row->file_name,
                    'url' => $file_url,
                    'size' => $row->file_size,
                    'updated' => strtotime($row->updated_at),
                    'path' => $row->folder_id,
                    'time' => $row->created_at,
                    'type' => $row->file_type,
                    'icon' => $file_icon_array[strtolower($row->extension)],
                    'thumb' => $thumb,
                    'is_file' => true
                ];
            }

            return $result;
        }

        return [];
    }

    private function getView()
    {
        $view_type = request('show_list');

        if (null === $view_type) {
            return $this->composeViewName($this->getStartupViewFromConfig());
        }

        $view_mapping = [
            '0' => 'grid',
            '1' => 'list'
        ];

        return $this->composeViewName($view_mapping[$view_type]);
    }

    private function composeViewName($view_type = 'grid')
    {
        return "file-manager.$view_type-view";
    }

    private function getStartupViewFromConfig($default = 'grid')
    {
        $type_key = parent::currentLfmType();
        $startup_view = config('lfm.' . $type_key . 's_startup_view', $default);
        return $startup_view;
    }
}
