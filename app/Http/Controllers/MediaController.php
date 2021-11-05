<?php

namespace App\Http\Controllers;

use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MediaController extends Controller
{
    public function showModal() {
        return view('modal.media');
    }

    public function ajaxLoadMedia(Request $request) {
        $parent = empty($request->parent) ? null: $request->parent;
        $query = Warehouse::query();
        $query->where('parent_id', '=', $parent);
        $query->where('created_by', '=', Auth::id());

        $paginate = $query->paginate(20);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->file_size = filesize_formatted($row->file_size);
        }

        if ($paginate->nextPageUrl()) {
            json_result(['rows' => $rows, 'more' => true]);
        }

        json_result(['rows' => $rows, 'move' => false]);
    }

    public function dataFileDownload($path) {
        $path = Crypt::decryptString($path);
        $storage = \Storage::disk('local');
        if (!$storage->exists($path)) {
            return abort(404);
        }
        
        return $storage->download($path);
    }

    public function getFileName(Request $request) {
        $path = path_upload($request->input('path'));
        $warehouse = Warehouse::where('file_path', '=', $path)->first();
        if ($warehouse) {
            json_message($warehouse->file_name);
        }

        json_message('File không tồn tại', 'error');
    }
}
