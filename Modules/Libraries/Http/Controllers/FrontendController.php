<?php

namespace Modules\Libraries\Http\Controllers;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use App\Slider;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LikeLibraries;
use Modules\Libraries\Entities\LibrariesRatting;
use App\User;
use Modules\Libraries\Entities\LibrariesBookmark;
use Modules\Libraries\Entities\LibrariesCategory;
use Modules\Libraries\Entities\LibrariesObject;
use Modules\Libraries\Entities\RegisterBook;
use Modules\Libraries\Entities\LibrariesMoreVideo;
use Modules\Libraries\Entities\LibrariesMoreAudiobook;
use App\Notifications;
use Modules\AppNotification\Helpers\AppNotification;
use Illuminate\Support\Str;
use App\Permission;
use App\Profile;
use App\ProfileView;
use Modules\Notify\Entities\Notify;
use App\Models\Categories\Unit;
use App\Models\Categories\Titles;
use App\LibrariesStatistic;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        Libraries::addGlobalScope(new CompanyScope());
        $books = Libraries::getNewBook();
        $ebooks = Libraries::getNewEbook();
        $documents = Libraries::getNewDocument();
        $video = Libraries::getNewVideo();

        $libraries_obj = function ($user_id, $lib_id, $type){
          return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };

        $check_bookmarks = function ($lib_id, $type){
            return LibrariesBookmark::checkExist($lib_id, $type);
        };

        $register = function ($id){
            return RegisterBook::where('book_id', '=', $id)
                ->where('user_id', '=', \Auth::id())
                ->where('approved', '=', 1)
                ->orderBy('created_at', 'DESC')
                ->first();
        };

        if (url_mobile()){
            $get_news_libraries = Libraries::select(['id','image','type'])->where('status',1)->orderByDesc('created_at')->take(6)->get();
            $get_news_libraries_array = Libraries::where('status',1)->orderByDesc('created_at')->take(6)->pluck('id')->toArray();

            $get_news_book = Libraries::select(['id','image'])->where('type',1)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
            $get_news_ebook = Libraries::select(['id','image'])->where('type',2)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
            $get_news_document = Libraries::select(['id','image'])->where('type',3)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
            $get_news_video = Libraries::select(['id','image'])->where('type',4)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
            $get_news_audiobook = Libraries::select(['id','image'])->where('type',5)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();

            return view('themes.mobile.frontend.libraries.index',[
                'get_news_libraries' => $get_news_libraries,
                'get_news_book' => $get_news_book,
                'get_news_document' => $get_news_document,
                'get_news_ebook' => $get_news_ebook,
                'get_news_video' => $get_news_video,
                'get_news_audiobook' => $get_news_audiobook,
            ]);
        }
        return view('libraries::frontend.index', [
            'books' => $books,
            'ebooks' => $ebooks,
            'documents' => $documents,
            'sliders' => $sliders,
            'libraries_obj' => $libraries_obj,
            'check_bookmarks' => $check_bookmarks,
            'register' => $register,
            'video' => $video
        ]);
    }

    public function book($id, Request $request)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();

        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();

        LibrariesCategory::addGlobalScope(new CompanyScope());
        $cate = LibrariesCategory::where('type', '=', 1)->get();

        $search_cate = $request->input('search_cate');
        $search_author = $request->input('search_author');
        $search = $request->input('search');
        $type = $request->input('type');
        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        Libraries::addGlobalScope(new CompanyScope());
        $query = Libraries::query();
        $query->select(['el_libraries.*']);
        $query->where('el_libraries.type', '=', 1);
        $query->where('el_libraries.status', '=', 1);
        $query->orderByDesc('el_libraries.id');

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($search_author){
            $query->where(function($sub_query) use ($search_author){
                $sub_query->orWhere('name_author','like','%' . $search_author . '%');
            });
        }

        $all_name_cate_book = [];

        if ($search_cate){
            return redirect()->route('module.frontend.libraries.book',['id' => $search_cate]);
        } else {
            $get_category_books = LibrariesCategory::whereNull('parent_id')->where('type',1)->get();
        }

        if($id > 0){
            $cate_with_id = LibrariesCategory::find($id);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
            $all_name_cate_book = LibrariesCategory::getTreeParentUnit($id);
            $all_name_cate_book = array_reverse($all_name_cate_book, true);
            $get_category_books = LibrariesCategory::where('type',1)->where('parent_id',$id)->get();
        }

        if ($type) {
            $query->Join('el_register_book as b','b.book_id','=','el_libraries.id');
            $query->where('b.user_id', \Auth::id());
            if ($type == 1) {
                $query->where('b.approved','=',2);
            } else if ($type == 2) {
                $query->where('b.approved',1);
                $query->where('b.status',1);
            } else {
                $query->where('b.approved',1);
                $query->where('b.status',2);
            }
        }
        $set_paginate = 0;
        if($search_author || $search || $type) {
            $books = $query->get();
            $set_paginate = 1;
        } else {
            $books = $query->paginate(8);
        }

        $check_bookmarks = function ($lib_id, $type){
            return LibrariesBookmark::checkExist($lib_id, $type);
        };

        $register = function ($id){
            return RegisterBook::where('book_id', '=', $id)
                ->where('user_id', '=', \Auth::id())
                ->where('approved', '=', 1)
                ->orderBy('created_at', 'DESC')
                ->first();
        };

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($books);
            return $data;
        }

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.book.index', [
                'books' => $books,
            ]);
        }
        return view('libraries::frontend.book.index', [
            'books' => $books,
            'sliders' => $sliders,
            'cate' => $cate,
            'check_bookmarks' => $check_bookmarks,
            'register' => $register,
            'notifications' => $notifications,
            'search_cate' => $search_cate,
            'get_category_books' => $get_category_books,
            'all_name_cate_book' => $all_name_cate_book,
            'get_unit' => $get_unit,
            'set_paginate' => $set_paginate,
        ]);
    }

    public function ebook($id, Request $request)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();

        LibrariesCategory::addGlobalScope(new CompanyScope());
        $cate = LibrariesCategory::where('type', '=', 2)->get();
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');
        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        Libraries::addGlobalScope(new CompanyScope());
        $ebooks = Libraries::getListEbook($id, $search_cate, $search, $search_author);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };
        $check_bookmarks = function ($lib_id, $type){
            return LibrariesBookmark::checkExist($lib_id, $type);
        };

        $all_name_cate_ebook = [];
        if ($search_cate){
            return redirect()->route('module.frontend.libraries.ebook',['id' => $search_cate]);
        } else {
            $get_categorys_ebooks = LibrariesCategory::whereNull('parent_id')->where('type',2)->get();
        }

        if($id > 0){
            $all_name_cate_ebook = LibrariesCategory::getTreeParentUnit($id);
            $all_name_cate_ebook = array_reverse($all_name_cate_ebook, true);
            $get_categorys_ebooks = LibrariesCategory::where('type',2)->where('parent_id',$id)->get();
        }

        $set_paginate = 0;
        if($search_author || $search) {
            $set_paginate = 1;
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($ebooks);
            return $data;
        }

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.ebook.index', [
                'ebooks' => $ebooks,
            ]);
        }
        return view('libraries::frontend.ebook.index', [
            'ebooks' => $ebooks,
            'sliders' => $sliders,
            'cate' => $cate,
            'libraries_obj' => $libraries_obj,
            'check_bookmarks' => $check_bookmarks,
            'get_categorys_ebooks' => $get_categorys_ebooks,
            'all_name_cate_ebook' => $all_name_cate_ebook,
            'get_unit' => $get_unit,
            'set_paginate' => $set_paginate
        ]);
    }

    public function document($id, Request $request)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();

        LibrariesCategory::addGlobalScope(new CompanyScope());
        $cate = LibrariesCategory::where('type', '=', 3)->get();
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');
        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        Libraries::addGlobalScope(new CompanyScope());
        $documents = Libraries::getListDocument($id, $search_cate, $search, $search_author);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };
        $check_bookmarks = function ($lib_id, $type){
            return LibrariesBookmark::checkExist($lib_id, $type);
        };

        $all_name_cate_document = [];
        if ($search_cate){
            return redirect()->route('module.frontend.libraries.document',['id' => $search_cate]);
        } else {
            $get_categorys_document = LibrariesCategory::whereNull('parent_id')->where('type',3)->get();
        }

        if($id > 0){
            $all_name_cate_document = LibrariesCategory::getTreeParentUnit($id);
            $all_name_cate_document = array_reverse($all_name_cate_document, true);
            $get_categorys_document = LibrariesCategory::where('type',3)->where('parent_id',$id)->get();
        }

        $set_paginate = 0;
        if($search_author || $search) {
            $set_paginate = 1;
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($documents);
            return $data;
        }
        
        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.doc.index', [
                'documents' => $documents,
            ]);
        }
        return view('libraries::frontend.document.index', [
            'documents' => $documents,
            'sliders' => $sliders,
            'cate' => $cate,
            'libraries_obj' => $libraries_obj,
            'check_bookmarks' => $check_bookmarks,
            'get_categorys_document' => $get_categorys_document,
            'all_name_cate_document' => $all_name_cate_document,
            'get_unit' => $get_unit,
            'set_paginate' => $set_paginate
        ]);
    }

    public function video($id, Request $request)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();

        LibrariesCategory::addGlobalScope(new CompanyScope());
        $cate = LibrariesCategory::where('type', '=', 4)->get();
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');
        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        Libraries::addGlobalScope(new CompanyScope());
        $video = Libraries::getListVideo($id, $search_cate, $search, $search_author);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };
        $check_bookmarks = function ($lib_id, $type){
            return LibrariesBookmark::checkExist($lib_id, $type);
        };

        $all_name_cate_video = [];
        if ($search_cate){
            return redirect()->route('module.frontend.libraries.video',['id' => $search_cate]);
        } else {
            $get_categorys_video = LibrariesCategory::whereNull('parent_id')->where('type',4)->get();
        }

        if($id > 0){
            $all_name_cate_video = LibrariesCategory::getTreeParentUnit($id);
            $all_name_cate_video = array_reverse($all_name_cate_video, true);
            $get_categorys_video = LibrariesCategory::where('type',4)->where('parent_id',$id)->get();
        }

        $set_paginate = 0;
        if($search_author || $search) {
            $set_paginate = 1;
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($video);
            return $data;
        }

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.video.index', [
                'video' => $video,
            ]);
        }
        return view('libraries::frontend.video.index', [
            'video' => $video,
            'sliders' => $sliders,
            'cate' => $cate,
            'libraries_obj' => $libraries_obj,
            'check_bookmarks' => $check_bookmarks,
            'get_categorys_video' => $get_categorys_video,
            'all_name_cate_video' => $all_name_cate_video,
            'get_unit' => $get_unit,
            'set_paginate' => $set_paginate
        ]);
    }
    // sách nói
    public function audiobook($id, Request $request)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();

        LibrariesCategory::addGlobalScope(new CompanyScope());
        $cate = LibrariesCategory::where('type', '=', 5)->get();
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');
        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        Libraries::addGlobalScope(new CompanyScope());
        $audiobook = Libraries::getListAudiobook($id, $search_cate, $search, $search_author);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };
        $check_bookmarks = function ($lib_id, $type){
            return LibrariesBookmark::checkExist($lib_id, $type);
        };

        $all_name_cate_audiobook = [];
        if ($search_cate){
            return redirect()->route('module.frontend.libraries.audiobook',['id' => $search_cate]);
        } else {
            $get_categorys_audiobook = LibrariesCategory::whereNull('parent_id')->where('type',5)->get();
        }

        if($id > 0){
            $all_name_cate_audiobook = LibrariesCategory::getTreeParentUnit($id);
            $all_name_cate_audiobook = array_reverse($all_name_cate_audiobook, true);
            $get_categorys_audiobook = LibrariesCategory::where('type',5)->where('parent_id',$id)->get();
        }

        $set_paginate = 0;
        if($search_author || $search) {
            $set_paginate = 1;
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($audiobook);
            return $data;
        }

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.audiobook.index', [
                'audiobook' => $audiobook,
            ]);
        }
        return view('libraries::frontend.audiobook.index', [
            'audiobook' => $audiobook,
            'sliders' => $sliders,
            'cate' => $cate,
            'libraries_obj' => $libraries_obj,
            'check_bookmarks' => $check_bookmarks,
            'all_name_cate_audiobook' => $all_name_cate_audiobook,
            'get_categorys_audiobook' => $get_categorys_audiobook,
            'get_unit' => $get_unit,
            'set_paginate' => $set_paginate
        ]);
    }
    // end sách nói

    public function bookDetail($id)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();
        $categories = Libraries::getLibrariesCategory($item->category_id, $item->id);

        $get_related_libraries = Libraries::where('category_id',$item->category_id)->where('id','!=',$item->id)->where('type',1)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.book.detail', [
                'item' => $item,
            ]);
        }
        return view('libraries::frontend.book.detail', [
            'item' => $item,
            'categories' => $categories,
            'related_libraries' => $related_libraries
        ]);
    }

    public function ebookDetail($id)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();
        $check_status_libraries_obj = 3;

        $sliders = Slider::where('location', '=', 'libraries')
            ->where('status', '=', 1)
            ->get();

        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();
        $categories = Libraries::getLibrariesCategory($item->category_id, $item->id);
        $created_by = User::find($item->created_by);

        $profile = ProfileView::where('user_id',\Auth::id())->first();
        if ($profile->title_id !== null || $profile->unit_id !== null) {
            $libraries_object_title = LibrariesObject::where('title_id',$profile->title_id)->where('libraries_id',$id)->first();
            $libraries_object_unit = LibrariesObject::where('unit_id',$profile->unit_id)->where('libraries_id',$id)->first();
            if ($libraries_object_title !== null && $libraries_object_unit == null) {
                if ($libraries_object_title->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_title->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            } else if ($libraries_object_title !== null && $libraries_object_unit !== null) {
                if($libraries_object_title->status == 1 && $libraries_object_unit->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_title->status == 2 && $libraries_object_unit->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            } else if($libraries_object_unit !== null && $libraries_object_title == null) {
                if ($libraries_object_unit->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_unit->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            }
        }

        $get_related_libraries = Libraries::where('category_id',$item->category_id)->where('type',2)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };

        LibrariesStatistic::update_libraries_insert_statistic(0);

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.ebook.detail', [
                'item' => $item,
            ]);
        }
        return view('libraries::frontend.ebook.detail', [
            'item' => $item,
            'categories' => $categories,
            'created_by' => $created_by,
            'sliders' => $sliders,
            'libraries_obj' => $libraries_obj,
            'check_status_libraries_obj' => $check_status_libraries_obj,
            'related_libraries' => $related_libraries,
        ]);
    }

    public function documentDetail($id)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();
        $check_status_libraries_obj = 3;
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        $created_by = User::find($item->created_by);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };

        $profile = ProfileView::where('user_id',\Auth::id())->first();
        if ($profile->title_id !== null || $profile->unit_id !== null) {
            $libraries_object_title = LibrariesObject::where('title_id',$profile->title_id)->where('libraries_id',$id)->first();
            $libraries_object_unit = LibrariesObject::where('unit_id',$profile->unit_id)->where('libraries_id',$id)->first();
            if ($libraries_object_title !== null && $libraries_object_unit == null) {
                if ($libraries_object_title->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_title->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            } else if ($libraries_object_title !== null && $libraries_object_unit !== null) {
                if($libraries_object_title->status == 1 && $libraries_object_unit->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_title->status == 2 && $libraries_object_unit->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            } else if($libraries_object_unit !== null && $libraries_object_title == null) {
                if ($libraries_object_unit->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_unit->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            }
        }

        $get_related_libraries = Libraries::where('category_id',$item->category_id)->where('type',3)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.doc.detail', [
                'item' => $item,
            ]);
        }
        return view('libraries::frontend.document.detail', [
            'item' => $item,
            'created_by' => $created_by,
            'libraries_obj' => $libraries_obj,
            'check_status_libraries_obj' => $check_status_libraries_obj,
            'related_libraries' => $related_libraries,
        ]);
    }

    public function videoDetail($id)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        $libraries_videos = LibrariesMoreVideo::where('libraries_id',$id)->get();

        $created_by = User::find($item->created_by);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };

        $get_related_libraries = Libraries::where('category_id',$item->category_id)->where('type',4)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);


        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.video.detail', [
                'item' => $item,
            ]);
        }
        return view('libraries::frontend.video.detail', [
            'item' => $item,
            'created_by' => $created_by,
            'libraries_obj' => $libraries_obj,
            'libraries_videos' => $libraries_videos,
            'related_libraries' => $related_libraries,
            'get_unit' => $get_unit,
        ]);
    }

    public function audiobookDetail($id)
    {
        $get_unit =  \App\ProfileView::where('user_id', \Auth::id())->first();
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        $libraries_audiobooks = LibrariesMoreAudiobook::where('libraries_id',$id)->get();

        $created_by = User::find($item->created_by);

        $libraries_obj = function ($user_id, $lib_id, $type){
            return LibrariesObject::getStatus($user_id, $lib_id, $type);
        };

        $get_related_libraries = Libraries::where('category_id',$item->category_id)->where('type',5)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);

        if (url_mobile()){
            return view('themes.mobile.frontend.libraries.audiobook.detail', [
                'item' => $item,
            ]);
        }
        return view('libraries::frontend.audiobook.detail', [
            'item' => $item,
            'created_by' => $created_by,
            'libraries_obj' => $libraries_obj,
            'libraries_audiobooks' => $libraries_audiobooks,
            'related_libraries' => $related_libraries,
        ]);
    }

    public function registerBook($book_id, Request $request){
        $this->validateRequest([
            'quantity'=>'required'
        ], $request);

        $book = Libraries::find($book_id);
        $quantily = $request->input('quantity');

        $model = new RegisterBook();
        $model->user_id = Auth::id();
        $model->book_id = $book_id;
        $model->quantity = $quantily;
        $model->register_date = Carbon::now();
        $model->approved = 2;
        $model->status = 1;

        if ($quantily > $book->current_number){
            json_message('Số lượng còn lại không đủ', 'error');
        }

        $exists = RegisterBook::where('user_id', Auth::id())
            ->where('book_id', '=', $book_id)
            ->where('approved', '=', 1)
            //->orWhere('approved', '=', 0)
            ->where(function ($sub) {
                $sub->where('status',1);
                $sub->orWhere('status',2);
            })
            ->exists();
        if ($exists){
            json_message('Sách còn đang mượn', 'error');
        }
        $save = $model->save();
        $library = Libraries::find($book_id);
        $library->current_number -= $quantily;
        $library->save();
        if ($save) {
            $unit_id = [];
            $unit = Unit::getTreeParentUnit(Profile::getUnitCode());
            foreach ($unit as $item){
                $unit_id[] = $item->id;
            }
            $query = \DB::query()
                ->from('el_user_permission_type as a')
                ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
                ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
                ->where(function ($sub) use ($unit_id){
                    $sub->orWhere(function ($sub1) use ($unit_id){
                        $sub1->where('b.type', '=', 'group-child')
                            ->whereIn('b.unit_id', $unit_id);
                    });
                    $sub->orWhere(function ($sub2){
                        $sub2->where('b.type', '=', 'owner')
                            ->where('b.unit_id', '=', Profile::getUnitId());
                    });
                })
                ->whereIn('c.name', function ($sub2){
                    $sub2->select(['per.parent'])
                        ->from('el_model_has_permissions as model')
                        ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                        ->whereColumn('model.model_id', '=', 'a.user_id')
                        ->where('per.name', '=', 'libraries-book-register-approve')
                        ->orWhere('per.name', '=', 'libraries-book-register');
                })
                ->where('c.name', '=', 'user')
                ->pluck('a.user_id')->toArray();

            $user_managers = $query;
            if (count($user_managers) > 0){
                foreach ($user_managers as $user) {
                    $model = new Notify();
                    $model->user_id = $user;
                    $model->subject = 'Duyệt đăng ký mượn sách';
                    $model->content = 'Nhân viên '. Profile::fullname(\Auth::id()) .' vừa đăng ký mượn sách. Vui lòng vào quản trị để duyệt thay đổi';
                    $model->url = '';
                    $model->created_by = 0;
                    $model->save();

                    $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
                    $redirect_url = route('module.notify.view', [
                        'id' => $model->id,
                        'type' => 1
                    ]);

                    $notification = new AppNotification();
                    $notification->setTitle($model->subject);
                    $notification->setMessage($content);
                    $notification->setUrl($redirect_url);
                    $notification->add($user);
                }
                $notification->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('app.register_success'),
            'redirect' => route('module.libraries.book.detail', ['id' => $book_id])
        ]);
    }

    public function updateItemViews(Request $request){
        $model = Libraries::find($request->id);
        $model->views = $model->views + 1;
        $model->save();
    }

    public function saveLibrariesBookmark($id, $type){
        $model = new LibrariesBookmark();
        $model->libraries_id = $id;
        $model->type = $type;
        $model->user_id = Auth::id();
        $model->save();

        if ($type == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã đánh dấu',
                'redirect' => route('module.frontend.libraries.book',['id' => 0])
            ]);
        }
        if ($type == 2){
            json_result([
                'status' => 'success',
                'message' => 'Đã đánh dấu',
                'redirect' => route('module.frontend.libraries.ebook',['id' => 0])
            ]);
        }
        if ($type == 3){
            json_result([
                'status' => 'success',
                'message' => 'Đã đánh dấu',
                'redirect' => route('module.frontend.libraries.document',['id' => 0])
            ]);
        }
    }

    public function removeLibrariesBookmark($id, $type){
        LibrariesBookmark::query()
            ->where('libraries_id', '=', $id)
            ->where('type', '=', $type)
            ->where('user_id', '=', Auth::id())
            ->delete();

        if ($type == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã bỏ đánh dấu',
                'redirect' => route('module.frontend.libraries.book',['id' => 0])
            ]);
        }
        if ($type == 2){
            json_result([
                'status' => 'success',
                'message' => 'Đã bỏ đánh dấu',
                'redirect' => route('module.frontend.libraries.ebook',['id' => 0])
            ]);
        }
        if ($type == 3){
            json_result([
                'status' => 'success',
                'message' => 'Đã bỏ đánh dấu',
                'redirect' => route('module.frontend.libraries.document',['id' => 0])
            ]);
        }
    }

    public function viewPDF($id){
        $path = Libraries::find($id)->getLinkView();
        if (url_mobile()){
            $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

            return view('themes.mobile.frontend.libraries.view_pdf', [
                'path' => $path,
            ]);
        }
        return view('libraries::frontend.view_pdf', [
            'path' => $path,
        ]);
    }

    // tăng số lần tải
    public function download($id) {
        $item = Libraries::findOrFail($id);
        $item->download = $item->download + 1;
        $item->save();
        return $count_download = $item->download;
    }

    // chức năng like
    public function like(Request $request) {
        $check_like = 0;
        $id_new = $request->id;
        $libraries = Libraries::where('id',$id_new)->first();
        $profile = LikeLibraries::where('user_id',\Auth::id())->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeLibraries;
            $set_profile_like_libraries = [$id_new];
            $profile->libraries_id = json_encode($set_profile_like_libraries);
            $profile->user_id = \Auth::id();
            $profile->save();
            $like_libraries = $libraries->like_libraries + 1;
            $libraries->like_libraries = $like_libraries;
            $libraries->save();
            return json_result([
                    'view_like'=>$libraries->like_libraries,
                    'check_like'=>$check_like,
                ]);
        }
        $get_profile_like_libraries = json_decode($profile->libraries_id);
        if (($key = array_search($id_new, $get_profile_like_libraries)) !== false) {
            unset($get_profile_like_libraries[$key]);
            $newarray = array_values($get_profile_like_libraries);
            $profile->libraries_id = json_encode($newarray);
            $like_libraries = $libraries->like_libraries - 1;
        } else {
            array_push($get_profile_like_libraries, $id_new);
            $profile->libraries_id = json_encode($get_profile_like_libraries);
            $like_libraries = $libraries->like_libraries + 1;
            $check_like = 1;
        }
        $profile->save();
        $libraries->like_libraries = $like_libraries;
        $libraries->save();
        return json_result([
                'view_like'=>$libraries->like_libraries,
                'check_like'=>$check_like,
            ]);
    }

    // AJAX LẤY TÊN TÁC GIẢ
    public function getAuthor(Request $request) {
		$search = $request->search_author;
        $type = $request->type;
        $get_authors = Libraries::select('name_author')->where('name_author','like','%' . $search . '%')->where('type', $type)->groupBy('name_author')->get();
        json_result($get_authors);
	}

    // AJAX LẤY TÊN THƯ VIỆN
    public function getNameLibraries(Request $request) {
		$search = $request->search_libraries;
        $type = $request->type;
        $get_name_libraries = Libraries::where('name','like','%' . $search . '%')->where('type', $type)->get();
        json_result($get_name_libraries);
	}

    // ĐÁNH GIÁ SAO
    public function ratting(Request $request) {
        $check_ratting = LibrariesRatting::where('user_id', '=', \Auth::id())->where('libraries_id', $request->id)->first();
        if ($check_ratting === null) {
            $model = new LibrariesRatting();
            $model->user_id = \Auth::id();
            $model->ratting = $request->star;
            $model->libraries_id = $request->id;
            $model->save();
            json_result([
                'status' => 'success',
                'message' => 'Cảm ơn bạn đã đánh giá',
            ]);
        } else {
            json_result([
                'status' => 'warning',
                'message' => 'Bạn đã đánh giá',
            ]);
        }
    }

    public function cateLibraries($cate_id, $type) {
        if (url_mobile()){
            $check_cate_parent = LibrariesCategory::where('id',$cate_id)->where('type',$type)->first();
            // dd($check_cate_parent);
            if(empty($check_cate_parent->parent_id)) {
                $cate_libraries = LibrariesCategory::where('type', $type)->whereNull('parent_id')->get();
            } else {
                $cate_libraries = LibrariesCategory::where('type', $type)->where('parent_id',$check_cate_parent->parent_id)->get();
            }
            return view('themes.mobile.frontend.libraries.cate_libraries',[
                'cate_libraries' => $cate_libraries,
                'cate_id' => $cate_id,
                'type' => $type,
            ]);
        }
    }

    public function search(Request $request) {
        $type = $request->type;
        $search = $request->search;
        $cate_id = $request->cate_id;
        $model = LibrariesCategory::query();
        $model->where('type',$type);

        $cates_item = '';
        $get_cate_with_id = '';
        $get_libraries = '';
        if($cate_id > 0) {
            $model->where('parent_id',$cate_id);
            $get_cate_with_id = LibrariesCategory::find($cate_id);
            $cates_search = $model->get();

            $query = Libraries::query();
            $query->where('type',$type);
            $query->where(function($sub) use ($get_cate_with_id){
                $sub->where('category_id',$get_cate_with_id->id);
                $sub->orWhere('category_parent','like','%'. $get_cate_with_id->name .'%');
            });
            $query->where('status',1);
            if($search) {
                $query->where(function($sub) use ($search) {
                    $sub->where('name','like','%'. $search .'%');
                    $sub->orwhere('name_author','like','%'. $search .'%');
                });
            }
            $cates_item = $query->get();
        } else {
            $model->whereNull('parent_id');
            $cates_search = $model->get();
            $query = Libraries::query();
            $query->where('type',$type);
            $query->where(function($sub) use ($search) {
                $sub->where('name','like','%'. $search .'%');
                $sub->orwhere('name_author','like','%'. $search .'%');
            });
            $get_libraries = $query->get();
        }

        if($search && !$type) {
            $query = Libraries::query();
            $query->where(function($sub) use ($search) {
                $sub->where('name','like','%'. $search .'%');
                $sub->orwhere('name_author','like','%'. $search .'%');
            });
            $get_libraries = $query->get();
        }

        $get_news_libraries = Libraries::where('status',1)->where('type',$type)->orderByDesc('created_at')->get()->take(6);

        return view('themes.mobile.frontend.libraries.search',[
            'type' => $type,
            'search' => $search,
            'get_libraries' => $get_libraries,
            'cates_search' => $cates_search,
            'cates_item' => $cates_item,
            'cate_id' => $cate_id,
            'get_cate_with_id' => $get_cate_with_id,
            'get_news_libraries' => $get_news_libraries
        ]);
    }

    public function loadData($items) {
        $data = '';
        foreach ($items as $item) {
            $get_object_libraries = LibrariesObject::where('libraries_id',$item->id)->whereNotNull('unit_id')->where('type', $item->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }  
            }  
            $isRating = LibrariesRatting::where('libraries_id',$item->id)->where('user_id',auth()->id())->first();
            if ($item->type == 1){
                $url = route('module.libraries.book.detail', ['id' => $item->id]);
            }elseif ($item->type == 2){
                $url = route('module.libraries.ebook.detail', ['id' => $item->id]);
            }elseif($item->type == 3){
                $url = route('module.libraries.document.detail', ['id' => $item->id]);
            }elseif($item->type == 5){
                $url = route('module.libraries.audiobook.detail', ['id' => $item->id]);
            }else{
                $url = route('module.libraries.video.detail', ['id' => $item->id]);
            }
            if ((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $data.='<div class="col-lg-3 col-md-4 p-1">';
                $data.='    <div class="fcrse_1 library">';
                $data.='        <a href="'.$url.'" class="fcrse_img">';
                $data.='            <img alt="'.$item->name.'" class="lazy" src="'.image_library($item->image).'">';
                $data.='        </a>';
                $data.='        <div class="crse_reviews count_ratting">';
                $data.='            <i class="uil uil-star">'.(!empty($isRating) ? $isRating->ratting : 0) .'</i>';
                $data.='        </div>';
                $data.='        <div class="fcrse_content">';
                $data.='            <div class="vdtodt">';
                $data.='                <span class="vdt14">'.$item->views.'<i class="fa fa-eye"></i></span>';
                $data.='                <span class="vdt14">'. Carbon::parse($item->created_at)->diffForHumans() .'</span>';
                $data.='            </div>';
                $data.='            <div class="div_name">';
                $data.='                <a href="'.$url.'" class="crse14s"><span>'.$item->name.'</span></a>';
                $data.='                <div class="full_name">';
                $data.='                    <span>'.$item->name.'</span>';
                $data.='                </div>';
                $data.='            </div>';
                $data.='            <div class="author">';
                $data.='                <span>Tác giả: '.$item->name_author.' </span>';
                $data.='            </div>';
                $data.='            <div class="ratting_start_libraries">';
                $data.='                <span class="mr-1">Đánh giá: </span>';
                for ($i = 1; $i < 6; $i++){
                    if (!$isRating) {
                        $text = 'empty-star rating';
                    } elseif (!empty($isRating) && $isRating->ratting >= $i) {
                        $text = 'full-star';
                    } 
                    $data.='<span class="rating-star rating_star_item_'.$item->id.' ratting_'.$item->id.'_'.$i.' rating_libraries '. $text .'"';
                    $data.=' onclick="ratting('.$item->id .','. $i .','.(!empty($isRating) ? $isRating->ratting : 0).')"' ;
                    $data.=' onmouseover="hoverRatting('.$item->id.','.$i.','.(!empty($isRating) ? $isRating->ratting : 0).')"';
                    $data.=' onmouseout="outRatting('.$item->id.','. $i .','.(!empty($isRating) ? $isRating->ratting : 0).')"';
                    $data.='>';
                    $data.='</span>';
                }
                $data.='                <div class="ratting_start">';
                $data.='                </div>';
                $data.='            </div>';
                $data.='        </div>';
                $data.='    </div>';
                $data.='</div>';
            }
        }
        return $data;
    }
}
