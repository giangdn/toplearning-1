<?php

namespace App\Http\Controllers\Frontend;

use App\Guide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::get()->where('type',1);
        if (url_mobile()){
            return view('themes.mobile.frontend.guide.index', [
                'guides' => $guides,
            ]);
        }
        return view('frontend.guide', ['guides' => $guides]);
    }

    public function video()
    {
        $guides = Guide::where('type',2)->paginate(3);
        if (url_mobile()){
            return view('themes.mobile.frontend.guide.index', [
                'guides' => $guides,
            ]);
        }
        return view('frontend.guide_video', ['guides' => $guides]);
    }

    public function posts()
    {
        $guides = Guide::where('type',3)->paginate(5);
        if (url_mobile()){
            return view('themes.mobile.frontend.guide.index', [
                'guides' => $guides,
            ]);
        }
        return view('frontend.guide_posts', ['guides' => $guides]);
    }

    public function postDetail($id)
    {
        $guide = Guide::where('id',$id)->first();
        if (url_mobile()){
            return view('themes.mobile.frontend.guide.index', [
                'guides' => $guides,
            ]);
        }
        return view('frontend.guide_post_detail', ['guide' => $guide]);
    }

    public function viewPDF($id){
        $guide = Guide::find($id);
        $path = upload_file($guide->attach);

        if (url_mobile()){
            $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

            return view('themes.mobile.frontend.guide.view_pdf', [
                'path' => $path,
            ]);
        }
    }
}
