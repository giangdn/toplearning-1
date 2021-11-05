<?php

namespace Modules\SubjectRegister\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SubjectRegister\Entities\SubjectRegister;

class SubjectRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        /*if ($request->ajax())
        {
            $user_id = \Auth::id();
            $search = $request->input('search');
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
//            Profile::addGlobalScope(new DraftScope('user_id'));
            $prefix = \DB::getTablePrefix();
            $query = SubjectRegister::query();
            $query->select('el_subject_register.*',\DB::raw("concat(".$prefix."b.lastname,' ',".$prefix."b.firstname) as full_name"),'c.name as subject','c.code');
            $query->from('el_subject_register')->join('el_profile as b','el_subject_register.user_id','b.user_id')
            ->join('el_subject as c','el_subject_register.subject_id','c.id')
            ->where('el_subject_register.user_id','=',$user_id);
            if ($search) {
                $query->where(function ($sub_query) use ($search) {
                    $sub_query->orWhere('c.name', 'like', '%' . $search . '%');
                    $sub_query->orWhere('c.code', 'like', '%'. $search .'%');
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
        }*/
        return view('user::frontend.subjectregister.index');
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
