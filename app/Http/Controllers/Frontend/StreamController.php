<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\VideoStream;
use App\Http\Controllers\Controller;

class StreamController extends Controller
{
    public function stream($path) {
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        if (!file_exists($storage->path($path))) {
            return abort(404);
        }

        return response()->file($storage->path($path), [
            'Content-Type' => $storage->mimeType($path),
        ]);
    }

    public function video($file) {
        $file = decrypt_array($file);
        if (!isset($file['path'])) {
            return abort(404);
        }

        if (!file_exists($file['path'])) {
            return abort(404);
        }

        $stream = new VideoStream($file['path']);
        $stream->start();
    }
}
