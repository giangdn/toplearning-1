<?php

namespace Modules\Suggest\Http\Controllers;

use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Suggest\Entities\Suggest;
use Modules\Suggest\Entities\SuggestComment;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;


class SuggestController extends Controller
{
    public function index() {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('suggest::backend.suggest.index',[
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function form($id) {
        $model = Suggest::find($id);
        $page_title = $model->name;
        $profile = Profile::find($model->user_id);
        $comments = SuggestComment::where('suggest_id', '=', $model->id)->get();

        return view('suggest::backend.suggest.form', [
            'model' => $model,
            'page_title' => $page_title,
            'profile' => $profile,
            'comments' => $comments,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $search_code_name = $request->input('search_code_name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        Suggest::addGlobalScope(new DraftScope('user_id'));
        $query = Suggest::query();
        $query->select([
            'el_suggest.*',
            'b.code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS unit_name',
            'd.name AS title_name',
            'e.name AS parent_name',
            'f.name AS unit_manager',
        ]);
        $query->from('el_suggest');
        $query->join('el_profile AS b', 'b.user_id', '=', 'el_suggest.user_id');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'c.parent_code');
        $query->leftJoin('el_titles AS d', 'd.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'c.parent_code');

        if ($search) {
            $query->where('el_suggest.name', 'like', '%'. $search .'%');
        }
        if ($search_code_name) {
            $query->where(function ($sub_query) use ($search_code_name) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search_code_name . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search_code_name .'%');
            });
        }
        if ($start_date && $end_date) {
            $query->where('el_suggest.created_at', '>=', date_convert($start_date));
            $query->where('el_suggest.created_at', '<=', date_convert($end_date, '23:59:59'));
        }
        if (!is_null($status)) {
            $query->where('b.status', '=', $status);
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.unit_code', $unit_id);
                $sub_query->orWhere('c.id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('b.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at);
            $row->profile = $row->code . ' - ' . $row->lastname . ' ' . $row->firstname;
            $row->edit_url = route('module.suggest.edit', ['id' => $row->id]);

            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
