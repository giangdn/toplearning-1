<?php

namespace Modules\NewsOutside\Http\Controllers;

use App\Profile;
use App\SliderOutside;
use App\InfomationCompany;
use App\AdvertisingPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\NewsOutside\Entities\NewsOutside;
use Modules\NewsOutside\Entities\NewsOutsideCategory;

class FrontendController extends Controller
{
    public function index($cate_id, $parent_id, $type, Request $request)
    {
        $get_infomation_company = InfomationCompany::first();
        $getAdvertisingPhotos = AdvertisingPhoto::where('status',1)->where('type',0)->get();
        $sliders = SliderOutside::where('status', '=', 1)->get();
        
        $cate_new = '';
        $all_cate_news = NewsOutsideCategory::where('parent_id',$parent_id)->get();
        $cate_new_parent = NewsOutsideCategory::find($parent_id);
        
        $get_news_category_sort_right = NewsOutsideCategory::query()
        ->select('a.*')
        ->from('el_news_outside_category as a')
        ->leftJoin('el_news_outside_category as b','b.id','=','a.parent_id')
        ->where('a.sort',2)
        ->orderBy('b.stt_sort_parent', 'asc')        
        ->orderBy('a.stt_sort', 'asc')->get();

        $get_related_news_hot_outside = '';
        $get_id_cate_parent_related = [];
        if($type == 1){
            $cate_new = NewsOutsideCategory::find($cate_id);
            $get_hot_new_of_category = NewsOutside::where('hot',1)->where('status',1)->where('category_id',$cate_id)->orderByDesc('created_at')->first();
            if (!empty($get_hot_new_of_category)) {
                $get_related_news_hot_outside = NewsOutside::where('category_id',$cate_id)->orderByDesc('created_at')->where('status',1)->where('hot',1)->where('id','!=',$get_hot_new_of_category->id)->get();
            } 
        } else {
            $get_hot_new_of_category = NewsOutside::where('hot',1)->where('category_parent_id', $parent_id)->where('status',1)->orderByDesc('created_at')->first();
            if (!empty($get_hot_new_of_category)) {
                $get_id_cate_parent_related[] = $get_hot_new_of_category->id;
                $get_related_news_hot_outside = NewsOutside::where('hot',1)->where('category_parent_id', $parent_id)->orderByDesc('created_at')->where('status',1)->where('id','!=',$get_hot_new_of_category->id)->take(3)->get();
                foreach($get_related_news_hot_outside as $item) {
                    $get_id_cate_parent_related[] = $item->id;
                }
            } 
        }

        return view('newsoutside::frontend.index', [
            'cate_new' => $cate_new,
            'users_online' => \App\User::countUsersOnline(),
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'get_related_news_hot_outside' => $get_related_news_hot_outside,
            'sliders' => $sliders,
            'get_hot_new_of_category' => $get_hot_new_of_category,
            'cate_new_parent' => $cate_new_parent,
            'all_cate_news' => $all_cate_news,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'get_infomation_company' => $get_infomation_company,
            'type' => $type,
            'cate_id' => $cate_id,
            'parent_id' => $parent_id,
            'get_id_cate_parent_related' => $get_id_cate_parent_related,
        ]);
    }

    public function detail($cate_id, $id, Request $request)
    {
        NewsOutside::updateItemViews($id);
        $item = NewsOutside::findOrFail($id);

        return view('newsoutside::frontend.detail', [
            'item' => $item,
        ]);
    }
}
