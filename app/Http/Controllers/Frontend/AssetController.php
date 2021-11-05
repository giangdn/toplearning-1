<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function asset($path) {
        $storage = \Storage::disk('local');
        if ($storage->exists($path)) {
            return response()->file($storage->path($path), [
                'Content-Type' => $storage->mimeType($path),
            ]);
        }

        return abort(404);
    }
}
