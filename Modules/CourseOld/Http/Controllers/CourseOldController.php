<?php

namespace Modules\CourseOld\Http\Controllers;

use App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\CourseOld\Entities\CourseOld;
use Modules\CourseOld\Exports\CourseOldExport;
use Modules\CourseOld\Imports\CourseOldImport;
use Modules\CourseOld\Jobs\Import;

class CourseOldController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $sort = $request->input('sort','id');
            $order = $request->input('order','desc');
            $offset = $request->input('offset',0);
            $limit = $request->input('limit',20);
            $search_user = $request->input('search_user','');
            $search_course = $request->input('search_course','');
            $start_date = $request->input('start_date','');
            $end_date = $request->input('end_date','');
            $search_unit = $request->input('search_unit','');
            $query = CourseOld::select('id','course_code','course_name','user_code','full_name','unit','title','course_type','start_date', 'end_date');

            if($search_user){
                $query->where(function($sub_query) use ($search_user){
                    $sub_query->orWhere('user_code','like','%' . $search_user . '%');
                    $sub_query->orWhere('full_name','like','%' . $search_user . '%');
                });
            }
            if($search_unit){
                $query->where(function($sub_query) use ($search_unit){
                    $sub_query->orWhere('unit','like','%' . $search_unit . '%');
                });
            }
            if($search_course){
                $query->where(function($sub_query) use ($search_course){
                    $sub_query->orWhere('course_code','like','%' . $search_course . '%');
                    $sub_query->orWhere('course_name','like','%' . $search_course . '%');
                });
            }
            if ($start_date){
                $start_date = date_convert($start_date);
                $query->where('start_date','>=', $start_date);
            }
            if ($end_date){
                $end_date = date_convert($end_date);
                $query->where('end_date','<=', $end_date);
            }
            $count = $query->count();
            $query->orderBy($sort,$order);
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            foreach ($rows as $index => $row) {
                $row->start_date = get_date($row->start_date);
                $row->end_date = get_date($row->end_date);
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }

        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();

        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        // return view('courseold::index', [
        //     'notifications' => $notifications
        // ]);
        return view('backend.training.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
            'notifications' => $notifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('courseold::create');
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
        $courseOld = CourseOld::find($id);
        $data = json_decode($courseOld->data,true);
        return view('courseold::modal.detail',['data'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

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
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        CourseOld::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function import(Request $request)
    {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $file = $request->file('import_file');

//        $import = new CourseOldImport(\Auth::user());
//        \Excel::import($import, $file);
        (new CourseOldImport(\Auth::user()))->queue($file)->chain([
            new Import(\Auth::user())
        ]);
//            (new MergeSubjectImport(\Auth::user()))->queue($file)->chain([
//                new Import(\Auth::user()),
//            ]);
//        if ($import->errors) {
//            session()->put('errors', $import->errors);
//            session()->save();
//        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.courseold'),
        ]);
    }
    public function export(Request $request)
    {
        $search_user = $request->input('search_user','');
        $search_course = $request->input('search_course','');
        $start_date = $request->input('start_date','');
        $end_date = $request->input('end_date','');
        $search_unit = $request->input('search_unit','');

        return (new CourseOldExport($search_user,$search_unit,$search_course,$start_date,$end_date))->download('khoa_hoc_cu_'. date('d_m_Y') .'.xlsx');
    }
}
