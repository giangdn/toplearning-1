<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Controllers\Controller;

class LfmController extends Controller
{
    use LfmHelpers;
    protected static $success_response = 'OK';

    public function show() {
        $type = $this->currentLfmType();
        $mimetypes = config('lfm.storage.'. $type .'.mimetypes');
        $max_file_size = config('lfm.storage.'. $type .'.max_file_size');

        return view('file-manager.index', [
            'mimetypes' => $mimetypes,
            'max_file_size' => $max_file_size,
        ]);
    }

    public function getErrors() {
        $arr_errors = [];

        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            array_push($arr_errors, trans('lfm.message-extension_not_found'));
        }

        return $arr_errors;
    }

    protected function currentLfmType() {
        $type = request()->get('type');
        $type = strtolower($type);

        switch ($type) {
            case 'image': return 'image';
            case 'images': return 'image';
            case 'file': return 'file';
            case 'files': return 'file';
            case 'scorm': return 'scorm';
            default: return 'image';
        }
    }

    protected function error($error_type, $variables = [])
    {
        return trans('lfm.error-' . $error_type, $variables);
    }
//    public function translateFromUtf8($input)
//    {
//        if ($this->isRunningOnWindows()) {
//            $input = iconv('UTF-8', mb_detect_encoding($input), $input);
//        }
//
//        return $input;
//    }
//    public function isRunningOnWindows()
//    {
//        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
//    }
}
