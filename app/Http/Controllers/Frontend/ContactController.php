<?php

namespace App\Http\Controllers\Frontend;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::get();
        // if (url_mobile()){
        //     return view('themes.mobile.frontend.guide.index', [
        //         'guides' => $guides,
        //     ]);
        // }
        return view('frontend.contact', ['contacts' => $contacts]);
    }
    public function contactDetail($id)
    {
        $contact = Contact::where('id',$id)->first();
        // if (url_mobile()){
        //     return view('themes.mobile.frontend.guide.index', [
        //         'guides' => $guides,
        //     ]);
        // }
        return view('frontend.contact_detail', ['contact' => $contact]);
    }

}
