<?php

namespace Modules\FAQ\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\FAQ\Entities\FAQs;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $faqs = FAQs::query();
        if ($search){
            $faqs->where('name', 'like', '%'.$search.'%');
        }
        $faqs = $faqs->get();

        if (url_mobile()){
            return view('themes.mobile.frontend.faqs.index', [
                'faqs' => $faqs,
            ]);
        }

        return view('faq::frontend.index', [
            'faqs' => $faqs,
        ]);
    }

}
