<?php

namespace App\Http\Controllers\FileManager;

class DownloadController extends LfmController
{
    public function getDownload() {
        return response()->download(parent::getCurrentPath(request('file')));
    }
}
