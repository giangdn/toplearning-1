<?php

namespace Modules\Promotion\Http\Controllers\frontend;

use App\Scopes\CompanyScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Promotion\Entities\PromotionUserHistory;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $sort_score = $request->sort_score;

        PromotionGroup::addGlobalScope(new CompanyScope());
        $promotion_group = PromotionGroup::all();

        if (url_mobile()){
            Promotion::addGlobalScope(new CompanyScope());
            $promotions = Promotion::where('el_promotion.status',1)
                ->select('el_promotion.*','el_promotion_group.name as group_name')
                ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
                ->orderBy('period')
                ->paginate(8);

            $promotion_user = PromotionUserPoint::whereUserId(auth()->id())
                ->select('el_promotion_user_point.*','el_promotion_level.name')
                ->join('el_promotion_level', 'el_promotion_user_point.level_id','level')
                ->first();
            return view('themes.mobile.frontend.promotion.index', compact('promotion_group', 'promotions', 'promotion_user'));
        }

        $promotions = Promotion::where('el_promotion.status',1)
            ->select('el_promotion.*','el_promotion_group.name as group_name')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group');
            if($search){
                $promotions->where(function ($sub) use ($search){
                    $sub->orWhere('el_promotion.name', 'like', '%'.$search.'%');
                    $sub->orWhere('el_promotion_group.name', 'like', '%'.$search.'%');
                });
            }
            if ($sort_score && $sort_score == 1){
                $promotions->orderBy('point');
            }
            if ($sort_score && $sort_score == 2){
                $promotions->orderByDesc('point');
            }

        $set_paginate = 0;
        if($sort_score || $search) {
            $promotions = $promotions->get();
            $set_paginate = 1;
        } else {
            $promotions = $promotions->paginate(8);
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($promotions);
            return $data;
        }
        return view('promotion::frontend.promotion.index', compact('promotion_group', 'promotions','set_paginate'));
    }

    public function getPromotion($id)
    {

    }

    public function get($id){
        $userPointInfo = PromotionUserPoint::where('user_id', Auth::id())->first();
        if (!$userPointInfo){
            return response()->json([
                'message' => 'Điểm của bạn không đủ',
                'status' => 'warning'
            ]);
        }
        $promotionInfo = Promotion::findOrFail($id);
        if ($userPointInfo->point < $promotionInfo->point) {
            return response()->json([
                'message' => 'Điểm của bạn không đủ',
                'status' => 'warning'
            ]);
        }elseif ($promotionInfo->amount == 0) {
            return response()->json([
                'message' => 'Hết hàng',
                'status' => 'warning'
            ]);
        }elseif ($promotionInfo->period < Carbon::now()) {
            return response()->json([
                'message' => 'Hết hạn sử dụng',
                'status' => 'warning'
            ]);
        } else {
            $orders = new PromotionOrders();
            $latestOrder = PromotionOrders::orderBy('created_at','DESC')->first();
            $latestId = $latestOrder ? $latestOrder->id : 0;
            $orders->user_id = Auth::id();
            $orders->orders_id = '#'.str_pad($latestId + 1, 8, "0", STR_PAD_LEFT);
            $orders->point = $promotionInfo->point;
            $orders->promotion_id = $promotionInfo->id;
            $orders->quantity = 1;
            $orders->status = @trans('app.pending');
            if ($orders->save()) {
                $userPointInfo->point -= $promotionInfo->point;
                $userPointInfo->save();
                $promotionInfo->amount -= 1;
                $promotionInfo->save();

                $donate_points_user_history = new PromotionUserHistory();
                $donate_points_user_history->user_id = Auth::id();
                $donate_points_user_history->point = $promotionInfo->point;
                $donate_points_user_history->promotion = $promotionInfo->id;
                $donate_points_user_history->save();

                $query = new Notify();
                $query->user_id = Auth::id();
                $query->subject = 'Quy đổi quà tặng';
                $query->content = 'Quà tặng bạn quy đổi mã '. $promotionInfo->code .' - '. $promotionInfo->name .'. đang xử lý';
                $query->url = '';
                $query->created_by = \Auth::id();
                $query->save();

                $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $query->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($query->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add(Auth::id());
                $notification->save();

                $redirect = route('module.front.promotion');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đổi thành công',
                    'redirect' => $redirect,
                ]);
            }
        }
    }

    public function loadData($items) {
        $data = '';
        foreach ($items as $promotion) {
            $data.='<div class="col-xl-3 col-lg-4 col-md-6 p-1">
                        <div class="fcrse_1 mt-2">
                            <div class="promotion-images">
                                <a href="#"><img src="'.image_file($promotion->images) .'" alt=""></a>
                            </div>
                            <div class="tutor_content_dt">
                                <div class="tutor150">
                                    <a href="#" class="tutor_name">'. $promotion->name .'</a>
                                    <div class="mef78" title="Verify">
                                        <i class="uil uil-check-circle"></i>
                                    </div>
                                </div>
                                <div class="tutor_cate"><a href="#">'.$promotion->group_name .'</a></div>
                                <form action="'.route('module.front.promotion.get', ['id' => $promotion->id]) .'" method="post" class="form-ajax">
                                    <input type="hidden" name="_token" value="'.csrf_token() .'" />
                                    <button type="submit" class="btn btn_adcart btn-promotion">
                                        '. $promotion->point .'
                                        <img class="point w-5" src="'.asset('images/level/point.png') .'" alt="">
                                    </button>
                                </form>

                                <div class="tut1250">
                                    <span class="vdt15"><strong>'.trans('app.quantity').' : '.$promotion->amount .'</strong></span>
                                    <span class="vdt15"><strong>'.trans('app.period').' : '.Carbon::parse($promotion->period)->format('d/m/Y') .'</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        return $data;
    }
}
