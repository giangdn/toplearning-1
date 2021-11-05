<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingCost;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Offline\Entities\OfflineCourseCost;
use App\TypeCost;

class TrainingCostController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $get_type_costs = TypeCost::get();
        return view('backend.category.training_cost.index',[
            'type_costs' => $get_type_costs,
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

        TrainingCost::addGlobalScope(new DraftScope());
        $query = TrainingCost::query();
        $query->select('a.*','b.name as type_cost_name');
        $query->from('el_training_cost as a');
        $query->leftjoin('el_type_cost as b','b.id','=','a.type');

        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
            $query->orWhere('b.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('type');
        $query->orderBy($sort);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TrainingCost::select(['id','type','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, TrainingCost::getAttributeName());

        $model = TrainingCost::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
            ]);  
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $costOfOnline = OnlineCourseCost::query()->whereIn('cost_id',$ids);
        $costOfOffline = OfflineCourseCost::query()->whereIn('cost_id',$ids);
        if ($costOfOnline->exists() || $costOfOffline->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Không thể xóa vì có khóa học đang sử dụng chi phí này',
            ]);
        } else {
            TrainingCost::destroy($ids);
            json_result([
                'status' => 'success',
                'message' => 'Xóa thành công',
            ]);
        }
    }
}
