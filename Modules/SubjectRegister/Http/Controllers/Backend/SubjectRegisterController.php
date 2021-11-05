<?php

namespace Modules\SubjectRegister\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SubjectRegister\Entities\SubjectRegister;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;

class SubjectRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $search = $request->input('search');
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
//            Profile::addGlobalScope(new DraftScope('user_id'));
            $prefix = \DB::getTablePrefix();
            $query = SubjectRegister::query();
            $query->select('el_subject_register.*','b.full_name','b.code as user_code','b.title_name','b.unit_name','b.parent_unit_name','c.name as subject','c.code');
            $query->from('el_subject_register')
            ->join('el_profile_view as b','el_subject_register.user_id','b.user_id')
            ->join('el_subject as c','el_subject_register.subject_id','c.id');
            if ($search) {
                $query->where(function ($sub_query) use ($search) {
                    $sub_query->orWhere('c.name', 'like', '%' . $search . '%');
                    $sub_query->orWhere('c.code', 'like', '%'. $search .'%');
                    $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                    $sub_query->orWhere('b.full_name', 'like', '%'. $search .'%');
                });
            }
            if($request->unit) {
                $units = Unit::whereIn('id', explode(';', $request->unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('b.unit_id', $unit_id);
                    $sub_query->orWhere('b.unit_id', '=', $units->id);
                });
            }
            if ($request->area) {
                $query->leftJoin('el_unit AS u', 'u.code', '=', 'b.unit_code');
                $query->leftJoin('el_area AS area', 'area.id', '=', 'u.area_id');
                $area = Area::find($request->area);
                $area_id = Area::getArrayChild($area->code);
                $query->where(function ($sub_query) use ($area, $area_id) {
                    $sub_query->WhereIn('area.id', $area_id);
                    $sub_query->orWhere('area.id', '=', $area->id);
                });
            }
            $count = $query->count();
            $query->orderBy( $sort, $order);
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query->get();
            foreach ($rows as $row) {
                $row->created_date = get_date($row->created_at,'d/m/Y H:i:s');
                $row->status = $row->status==1?'Đã đăng ký':'Hủy đăng ký';
            }

            json_result(['total' => $count, 'rows' => $rows]);
        }

        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('subjectregister::backend.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('subjectregister::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('subjectregister::frontend.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('subjectregister::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
