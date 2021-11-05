<?php

namespace Modules\Promotion\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Promotion\Entities\PromotionLevel;

class PromotionLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('promotion::backend.promotion_level.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        PromotionLevel::addGlobalScope(new DraftScope());
        $query = PromotionLevel::query()
            ->select('*')
            ->from('el_promotion_level');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->created_by = Profile::fullname($row->created_by);
            $row->updated_by = $row->updated_by ? Profile::fullname($row->updated_by) : null;
            $row->edit_url = route('module.promotion.level.edit', ['id' => $row->id]);
            $row->images = image_file($row->images);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = PromotionLevel::select(['id','status','images','level','point','code','name'])->where('id', $request->id)->first();
        $path_image = image_file($model->images);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'code' => 'required',
            'name' => 'required',
            'level' => 'required|numeric|min:0|not_in:0',
            'point' => 'required|numeric|min:0|not_in:0',
            'images' => 'string',
            'status' => 'in:0,1',
        ],$request,PromotionLevel::getAttributeName());

        $level = PromotionLevel::firstOrNew(['id' => $request->id]);
        $level->fill($request->all());
        $level->updated_by = Auth::id();
        
        if ($request->images) {
            $sizes = config('image.sizes.medium');
            $level->images = upload_image($sizes, $request->images);
        }

        if ($request->id) {
            $level->created_by = $level->created_by;
        }
        if ($level->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        PromotionLevel::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = PromotionLevel::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = PromotionLevel::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }
}
