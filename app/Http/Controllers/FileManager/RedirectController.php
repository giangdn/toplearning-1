<?php
namespace App\Http\Controllers\FileManager;

class RedirectController extends LfmController
{
    public function getImage($base_path, $file_name) {
        return redirect(config('app.datafile.wwwfiledata') . '/uploads/'. $file_name);
    }

    public function getFile($base_path, $file_name) {
        return redirect(config('app.datafile.wwwfiledata') . '/uploads/'. $file_name);
    }
}