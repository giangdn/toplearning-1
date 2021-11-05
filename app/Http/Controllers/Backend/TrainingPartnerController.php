<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingPartner;
use App\Exports\TrainingPartnerExport;

class TrainingPartnerController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('backend.category.training_partner.index',[
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
        TrainingPartner::addGlobalScope(new DraftScope());
        $query = TrainingPartner::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
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
        $model = TrainingPartner::select(['id','email','code','name','phone','address','people'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_partner,code,'. $request->id,
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
        ], $request, TrainingPartner::getAttributeName());

        $model = TrainingPartner::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = \Auth::id();
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
        TrainingPartner::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function exportTrainingPartner()
    {
        return (new TrainingPartnerExport())->download('danh_sach_doi_tac_'. date('d_m_Y') .'.xlsx');
    }
}
