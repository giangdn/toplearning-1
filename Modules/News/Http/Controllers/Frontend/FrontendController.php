<?php

namespace Modules\News\Http\Controllers\Frontend;

use App\Scopes\CompanyScope;
use App\Slider;
use App\AdvertisingPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\News\Entities\News;
use App\User;
use Modules\News\Entities\NewsCategory;
use Modules\News\Entities\NewsLink;
use Modules\News\Entities\NewsObject;
use App\Profile;
use App\ProfileView;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());
        AdvertisingPhoto::addGlobalScope(new CompanyScope());

        $cate_id = '';
        $get_unit =  ProfileView::where('user_id', \Auth::id())->first();
        $get_object_news_parent_cate_id = NewsObject::get();
        $object_news_parent_cate_id = [];
        if (!$get_object_news_parent_cate_id->isEmpty()) {
            foreach ($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if ($check_unit == 0) {
                    $object_news_parent_cate_id[] = $get_object_new_parent_cate_id->new_id;
                } else {
                    if (($key = array_search($get_object_new_parent_cate_id->new_id, $object_news_parent_cate_id)) !== false) {
                        unset($object_news_parent_cate_id[$key]);
                        $object_news_parent_cate_id = array_values($object_news_parent_cate_id);
                    }
                }
            }
        }

        $get_main_new_hot = News::where('hot_public', 1)->orderByDesc('created_at')->whereNotIn('id', $object_news_parent_cate_id)->first();
        $get_related_main_hot_news = [];
        if (!empty($get_main_new_hot)) {
            $get_related_main_hot_news = News::select('image', 'title', 'id', 'date_setup_icon', 'description')->where('status', 1)->where('hot_public', 1)->where('id', '!=', $get_main_new_hot->id)->whereNotIn('id', $object_news_parent_cate_id)->get();
        }

        $get_news_parent_cate_left = NewsCategory::whereNull('parent_id')->where('status', 1)->orderBy('stt_sort_parent', 'asc')->get();

        $get_news_category_sort_right = NewsCategory::query()
            ->select('el_news_category.*')
            ->leftJoin('el_news_category as b', 'b.id', '=', 'el_news_category.parent_id')
            ->where('el_news_category.sort', 2)
            ->orderBy('b.stt_sort_parent', 'asc')
            ->orderBy('el_news_category.stt_sort', 'asc')->get();

        $getAdvertisingPhotos = AdvertisingPhoto::where('status', 1)->where('type', 1)->get();

        if (url_mobile()) {
            $news = '';
            if ($request->cate_id) {
                $cate_id = $request->cate_id;
                $get_news_parent_cate_left = NewsCategory::whereNull('parent_id')->where('status', 1)->where('id', $request->cate_id)->get();
            }
            if ($request->search) {
                $news = News::where('title', 'like', '%' . $request->search . '%')->get();
            }
            $parent_cates = NewsCategory::whereNull('parent_id')->where('status', 1)->orderBy('stt_sort_parent', 'asc')->get();
            return view('themes.mobile.frontend.news.index', [
                'parent_cates' => $parent_cates,
                'get_main_new_hot' => $get_main_new_hot,
                'get_hot_news' => $get_related_main_hot_news,
                'get_news_parent_cate_left' => $get_news_parent_cate_left,
                'object_cate_parent' => $object_news_parent_cate_id,
                'cate_id' => $cate_id,
                'news' => $news,
            ]);
        }

        return view('news::frontend.index3', [
            'get_main_new_hot' => $get_main_new_hot,
            'get_hot_news' => $get_related_main_hot_news,
            'get_news_parent_cate_left' => $get_news_parent_cate_left,
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'object_news_parent_cate_id' => $object_news_parent_cate_id,
        ]);
    }

    public function detail($id, Request $request)
    {
        $news_links = NewsLink::where('news_id', $id)->get();

        if (url_mobile()) {
            $item = News::findOrFail($id);
            $categories = News::getNewsCategory($item->category_id, $item->id);
            $user = User::getProfileById($item->created_by)->profile;
            $author = $user->lastname . " " . $user->firstname;
            $next_post = News::where('id', '>', $item->id)->where('status', '=', 1)->where('category_id', '=', $item->category_id)->orderBy('id')->first();
            $prev_post = News::where('id', '<', $item->id)->where('status', '=', 1)->where('category_id', '=', $item->category_id)->orderBy('id', 'DESC')->first();
            return view('themes.mobile.frontend.news.detail', [
                'item' => $item,
                'author' => $author,
                'categories' => $categories,
                'next_post' => $next_post,
                'prev_post' => $prev_post,
                'news_links' => $news_links,
            ]);
        }
        $get_unit =  ProfileView::where('user_id', \Auth::id())->first();
        $get_object_news_parent_cate_id = NewsObject::get();
        $object_news_parent_cate_id = [];
        if (!$get_object_news_parent_cate_id->isEmpty()) {
            foreach ($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if ($check_unit == 0) {
                    $object_news_parent_cate_id[] = $get_object_new_parent_cate_id->new_id;
                } else {
                    if (($key = array_search($get_object_new_parent_cate_id->new_id, $object_news_parent_cate_id)) !== false) {
                        unset($object_news_parent_cate_id[$key]);
                        $object_news_parent_cate_id = array_values($object_news_parent_cate_id);
                    }
                }
            }
        }

        $get_news_category_sort_right = NewsCategory::query()
            ->select('a.*')
            ->from('el_news_category as a')
            ->leftJoin('el_news_category as b', 'b.id', '=', 'a.parent_id')
            ->where('a.sort', 2)
            ->orderBy('b.stt_sort_parent', 'asc')
            ->orderBy('a.stt_sort', 'asc')->get();

        $get_new = News::find($id);
        $get_new_category = NewsCategory::where('id', $get_new->category_id)->first();
        $getAdvertisingPhotos = AdvertisingPhoto::where('status', 1)->where('type', 1)->get();
        News::updateItemViews($id);

        $get_category = NewsCategory::where('id', $get_new->category_id)->first();
        $get_category_parent = NewsCategory::where('id', $get_category->parent_id)->first();

        return view('news::frontend.detail', [
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'get_new' => $get_new,
            'get_new_category' => $get_new_category,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'get_category' => $get_category,
            'get_category_parent' => $get_category_parent,
            'object_news_parent_cate_id' => $object_news_parent_cate_id,
            'news_links' => $news_links,
        ]);
    }

    // chức năng like bài viết
    public function likeNew(Request $request)
    {
        $check_like = 0;
        $id_new = $request->id;
        $new = News::where('id', $id_new)->first();
        $profile = Profile::find(\Auth::id());
        if ($profile->like_new == null || empty($profile->like_new)) {
            $check_like = 1;
            $set_profile_like_new[] = $id_new;
            $profile->like_new = json_encode($set_profile_like_new);
            $profile->save();
            $like_new = $new->like_new + 1;
            $new->like_new = $like_new;
            $new->save();
            return json_result([
                'view_like' => $new->like_new,
                'check_like' => $check_like,
            ]);
        }
        $get_profile_like_new = json_decode($profile->like_new);
        if (($key = array_search($id_new, $get_profile_like_new)) !== false) {
            unset($get_profile_like_new[$key]);
            $newarray = array_values($get_profile_like_new);
            $profile->like_new = json_encode($newarray);
            $like_new = $new->like_new - 1;
        } else {
            array_push($get_profile_like_new, $id_new);
            $profile->like_new = json_encode($get_profile_like_new);
            $like_new = $new->like_new + 1;
            $check_like = 1;
        }
        $profile->save();
        $new->like_new = $like_new;
        $new->save();
        return json_result([
            'view_like' => $new->like_new,
            'check_like' => $check_like,
        ]);
    }

    public function cateNew($parent_id, $cate_id, $type)
    {
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());
        AdvertisingPhoto::addGlobalScope(new CompanyScope());

        if (url_mobile()) {
            $cate_news = NewsCategory::where('parent_id', $parent_id)->orderBy('id', 'asc')->get();
            return view('themes.mobile.frontend.news.cate_child', [
                'cate_news' => $cate_news,
            ]);
        }
        $get_unit =  ProfileView::where('user_id', \Auth::id())->first();
        $getAdvertisingPhotos = AdvertisingPhoto::where('status', 1)->where('type', 1)->get();

        $cate_new = '';
        $all_cate_news_name = NewsCategory::where('parent_id', $parent_id)->get();
        $cate_new_parent = NewsCategory::find($parent_id);

        $get_news_category_sort_right = NewsCategory::query()
            ->select('el_news_category.*')
            ->leftJoin('el_news_category as b', 'b.id', '=', 'el_news_category.parent_id')
            ->where('el_news_category.sort', 2)
            ->orderBy('b.stt_sort_parent', 'asc')
            ->orderBy('el_news_category.stt_sort', 'asc')->get();

        $get_object_news_parent_cate_id = NewsObject::get();
        $object_news_parent_cate_id = [];
        if (!$get_object_news_parent_cate_id->isEmpty()) {
            foreach ($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if ($check_unit == 0) {
                    $object_news_parent_cate_id[] = $get_object_new_parent_cate_id->new_id;
                } else {
                    if (($key = array_search($get_object_new_parent_cate_id->new_id, $object_news_parent_cate_id)) !== false) {
                        unset($object_news_parent_cate_id[$key]);
                        $object_news_parent_cate_id = array_values($object_news_parent_cate_id);
                    }
                }
            }
        }

        $get_related_news_hot_outside = '';
        if ($type == 1) {
            $cate_new = NewsCategory::find($cate_id);
            $get_hot_new_of_category = News::where('hot', 1)->where('status', 1)->whereNotIn('id', $object_news_parent_cate_id)->where('category_id', $cate_id)->orderByDesc('created_at')->first();
            if (!empty($get_hot_new_of_category)) {
                $get_related_news_hot_outside = News::select('image', 'title', 'id', 'date_setup_icon', 'description')->where('category_id', $cate_id)->whereNotIn('id', $object_news_parent_cate_id)->orderByDesc('created_at')->where('status', 1)->where('hot', 1)->where('id', '!=', $get_hot_new_of_category->id)->get();
            }
        } else {
            $get_hot_new_of_category = News::where('hot', 1)->where('category_parent_id', $parent_id)->whereNotIn('id', $object_news_parent_cate_id)->where('status', 1)->orderByDesc('created_at')->first();
            if (!empty($get_hot_new_of_category)) {
                $get_related_news_hot_outside = News::select('image', 'title', 'id', 'date_setup_icon', 'description')->where('hot', 1)->where('category_parent_id', $parent_id)->whereNotIn('id', $object_news_parent_cate_id)->orderByDesc('created_at')->where('status', 1)->where('id', '!=', $get_hot_new_of_category->id)->take(3)->get();
            }
        }
        return view('news::frontend.index', [
            'cate_new' => $cate_new,
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'get_related_news_hot_outside' => $get_related_news_hot_outside,
            'get_hot_new_of_category' => $get_hot_new_of_category,
            'cate_new_parent' => $cate_new_parent,
            'all_cate_news_name' => $all_cate_news_name,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'type' => $type,
            'object_news_parent_cate_id' => $object_news_parent_cate_id,
            'cate_id' => $cate_id,
            'parent_id' => $parent_id,
        ]);
    }

    public function ajaxGetRelatedNews(Request $request)
    {
        $category_id = $request->category_id;
        $date_search = date("Y-m-d", strtotime($request->date_search));
        $new_id = $request->new_id;
        $get_related_news = News::where('category_id', $category_id)
            ->where('status', 1)
            ->where('id', '!=', $new_id)
            ->whereDate('created_at', '=', $date_search)
            ->get();
        // dd($get_related_news);
        $image_related_new = '';
        if (!$get_related_news->isEmpty()) {
            foreach ($get_related_news as $item) {
                $image_related_new[] = array('image' => image_file($item->image), 'id' => $item->id, 'title' => $item->title, 'description' => $item->description);
            }
        }
        return json_result([
            'get_related_news' => $image_related_new,
        ]);
    }

    public function viewPDF(Request $request)
    {
        $path = $request->path;
        if (url_mobile()) {
            $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

            return view('themes.mobile.frontend.news.view_pdf', [
                'path' => $path,
            ]);
        }

        return view('news::frontend.view_pdf', [
            'path' => $path,
        ]);
    }
}
