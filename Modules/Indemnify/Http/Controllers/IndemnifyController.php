<?php

namespace Modules\Indemnify\Http\Controllers;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Indemnify\Exports\IndemnifyExport;
use App\Models\Categories\Area;

class IndemnifyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        return view('indemnify::backend.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    public function getData(Request $request) {
        $unit = $request->input('unit');
        $title = $request->input('title');
        $search = $request->input('search');
        $sort = $request->input('sort', 'full_name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Indemnify::addGlobalScope(new DraftScope());
        $student_indemnify = Indemnify::query()
            ->join('el_offline_course','el_indemnify.course_id','=','el_offline_course.id')
            ->select('user_id', \DB::raw('COUNT(course_id) AS num_course'))
            ->groupBy('user_id');

        $query=\DB::table('el_profile as a')
            ->joinSub($student_indemnify,'b',function ($join){
                $join->on('a.user_id','=','b.user_id');
            })
            ->leftJoin('el_unit as c','a.unit_code','=','c.code')
            ->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id')
            ->leftJoin('el_unit as e','e.code','=','c.parent_code')
            ->leftJoin('el_titles as d','a.title_code','=','d.code')
            ->select("a.user_id", "a.code", "a.firstname","a.lastname","a.email",
                "c.name AS unit_name","d.name AS title_name","b.num_course", "e.name AS parent_name");
        $query->get();

        if ($search) {
            $query->where(function ($sub_query) use ($search){
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }
        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $query->where('a.unit_code', '=', $unit->code);
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('a.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->detail_url = route('module.indemnify.user', ['id' => $row->user_id]);
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

    public function export(Request $request){
        $unit = $request->unit;
        $title = $request->title;
        return (new IndemnifyExport($unit, $title))->download('theo_doi_boi_hoan_'. date('d_m_Y') .'.xlsx');
    }
}
