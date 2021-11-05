<?php

namespace Modules\News\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\User;
use App\Slider;
use Modules\News\Entities\News;
use Modules\News\Entities\NewsCategory;

class DesktopController extends Controller
{
    public function index(Request $request)
    {
        $cate = NewsCategory::get();
        $search = $request->input('search');
        $search_cate = $request->input('search-cate');
        $sliders = Slider::where('location', '=', 'news')
            ->where('status', '=', 1)
            ->get();

        $query = News::query();
        $query->select([
            'a.id',
            'a.title',
            'a.description',
            'a.date_setup_icon',
            'a.image',
            \DB::raw('CONCAT_WS(\' \', lastname, firstname) AS fullname')
        ]);

        $query->from('el_news AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.created_by');

        if($search) {
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('title', 'like', '%' . $search . '%');
            });
        }

        if ($search_cate) {
            $query->where('category_id', '=', $search_cate);
        }

        $query->where('a.status', '=', 1);
        $query->orderByDesc('a.updated_at');
        $news = $query->paginate(10);

        if (url_mobile()){
            $news_hot_laster = News::getNewsHotLaster();
            $news_hot = News::getNewsHot();
            $news_view = News::getViewsMax();
            $news_all_view = News::getAllByViews();
            $news_new = News::getNewsNew();
            return view('themes.mobile.frontend.news.index', [
                'news_hot_laster' => $news_hot_laster,
                'news_hot' => $news_hot,
                'news_view' => $news_view,
                'news_all_view' => $news_all_view,
                'news_new' => $news_new
            ]);
        }

        return view('news::frontend.index', [
            'news' => $news,
            'sliders' => $sliders,
            'cate' => $cate,
        ]);
    }

    public function detail($id)
    {
        $sliders = Slider::where('location', '=', 'news')
            ->where('status', '=', 1)
            ->get();
        News::updateItemViews($id);
        $item = News::findOrFail($id);
        $categories = News::getNewsCategory($item->category_id, $item->id);
        $created_by = User::find($item->created_by);
        $views_max = News::getViewsMax();
        $user = User::getProfileById($item->created_by)->profile;
        $author = $user->lastname." ".$user->firstname;
        $next_post = News::where('id','>',$item->id)->orderBy('id')->first();
        $prev_post = News::where('id','<',$item->id)->orderBy('id','DESC')->first();

        if (url_mobile()){
            return view('themes.mobile.frontend.news.detail', [
                'item' => $item,
                'author' => $author,
                'categories' => $categories,
                'next_post' => $next_post,
                'prev_post' => $prev_post
            ]);
        }
        return view('news::frontend.detail', [
            'item' => $item,
            'categories' => $categories,
            'created_by' => $created_by,
            'views_max' => $views_max,
            'sliders' => $sliders,
            'author' => $author,
            'next_post' => $next_post,
            'prev_post' => $prev_post
        ]);
    }
}
