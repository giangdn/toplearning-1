<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuizType;

class QuizTypeController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('quiz::backend.type.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function form(Request $request) {
        $model = QuizType::select(['id','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required'
        ], $request, [
            'name' => trans('backend.name')
        ]);

        $model = QuizType::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->save();
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = \Auth::id();
        return \response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        QuizType::addGlobalScope(new CompanyScope());
        $query = QuizType::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
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

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        QuizType::destroy($ids);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
