<?php

namespace Modules\Promotion\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use App\Notifications;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;

class PromotionOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('promotion::backend.orders.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        PromotionOrders::addGlobalScope(new DraftScope('user_id'));
        $query = PromotionOrders::query()
            ->select(['el_promotion_orders.*', 'b.code', 'b.name', 'b.images', 'b.period', 'b.promotion_group'])
            ->leftJoin('el_promotion as b', 'el_promotion_orders.promotion_id', '=', 'b.id')
            ->leftJoin('user as c', 'el_promotion_orders.user_id', '=', 'c.id');

        if ($search) {
            $query->where('b.name', 'like', '%' . $search . '%');
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->images = image_file($row->images);
            $row->user = Profile::fullname($row->user_id);
            $row->edit_url = route('module.promotion.orders.buy.detail', ['id' => $row->id]);
            $row->remove_url = route('module.promotion.orders.buy.remove');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDetail($id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $order = PromotionOrders::query()
        ->select([
            'el_promotion_orders.*',
            'lastname',
            'firstname',
            'quantity',
            'el_promotion.code',
            'el_promotion.name',
            'images',
            'el_promotion_group.name as group_name',
            'el_promotion_group.code as group_code',
        ])
            ->from('el_promotion_orders')
            ->join('el_promotion', 'el_promotion.id', '=', 'promotion_id')
            ->join('user', 'user.id', '=', 'user_id')
            ->join('el_promotion_group', 'el_promotion_group.id','=','el_promotion.promotion_group')
            ->where('el_promotion_orders.id', '=', $id)
            ->firstOrFail();

        return view('promotion::backend.orders.detail',[
            'order' => $order,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function updateStatus(Request $request, $id){
        $array_status = ['Từ chối','Hủy'];
        $status = $request->status;
        $promotion_order = PromotionOrders::find($id);
        $user_point = PromotionUserPoint::where('user_id',$promotion_order->user_id)->first();
        $promotion = Promotion::find($promotion_order->promotion_id);
        if(!in_array($promotion_order->status, $array_status) && in_array($status, $array_status)) {
            $reset_user_point = (int)$user_point->point + (int)$promotion_order->point;
            $user_point->point = $reset_user_point;
            $user_point->save();
            $promotion->amount = (int)$promotion->amount + (int)$promotion_order->quantity;
            $promotion->save();
        } else if (in_array($promotion_order->status, $array_status) && !in_array($status, $array_status)) {
            $reset_user_point = (int)$user_point->point - (int)$promotion_order->point;
            $user_point->point = $reset_user_point;
            $user_point->save();
            $promotion->amount = (int)$promotion->amount - (int)$promotion_order->quantity;
            $promotion->save();
        }

        $query = new Notify();
        $query->user_id = $promotion_order->user_id;
        $query->subject = 'Quà tặng';
        if ($status == 'Hủy' || $status == 'Từ chối') {
            $query->content = 'Quà tặng '. $promotion->name .' bạn đổi đã bị '. $status;
        } else {
            $query->content = 'Quà tặng '. $promotion->name .' bạn đổi đã '. $status;
        }
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
        $notification->add($promotion_order->user_id);
        $notification->save(); 

        $order = PromotionOrders::findOrFail($id)->fill($request->all())->update();
        $redirect = route('module.promotion.orders.buy.detail',$id);
        json_result([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
            'redirect' => $redirect,
        ]);
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $key => $id) {
            $promotion_order = PromotionOrders::find($id);
            $user_point = PromotionUserPoint::where('user_id',$promotion_order->user_id)->first();
            if ($promotion_order->status == 'Đang chờ xử lý') {
                $reset_user_point = (int)$user_point->point + (int)$promotion_order->point;
                $user_point->point = $reset_user_point;
                $user_point->save();
            }
            $promotion = Promotion::find($promotion_order->promotion_id);
            $promotion->amount = (int)$promotion->amount + (int)$promotion_order->quantity;
            $promotion->save();
            $promotion_order->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
