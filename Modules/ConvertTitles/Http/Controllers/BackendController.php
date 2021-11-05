<?php

namespace Modules\ConvertTitles\Http\Controllers;

use App\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\ConvertTitles\Entities\ConvertTitlesReviews;
use Modules\ConvertTitles\Exports\ExportCourse;
use Modules\ConvertTitles\Exports\ResultConvertTitlesExport;
use Modules\ConvertTitles\Imports\ConvertTitlesImports;
use Modules\ConvertTitles\Exports\ConvertTitlesExport;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Quiz\Entities\QuizResult;
use App\Models\Categories\Area;

class BackendController extends Controller
{
    public function index()
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        return view('converttitles::backend.convert_titles.index');
    }

    public function course($user_id){

        $profile = Profile::find($user_id);
        $title = ConvertTitles::where('user_id', '=', $user_id)->first();
        $subject = ConvertTitles::getCourse($title->title_id);

        $course = function ($training_program_id, $subject_id, $cours_type, $user_id){
            $query = \DB::query();
            $query->select('a.id', 'a.course_type', 'a.start_date', 'a.end_date')
                ->from('el_course_view as a')
                ->leftJoin('el_course_register_view as b', function ($sub){
                    $sub->on('b.course_id', '=', 'a.course_id');
                    $sub->on('b.course_type', '=', 'a.course_type');
                })
                ->where('b.user_id', '=', $user_id)
                ->where('a.training_program_id', '=', $training_program_id)
                ->where('a.subject_id', '=', $subject_id)
                ->where('a.course_type', '=', $cours_type)
                ->where('a.status', '=', 1)
                ->where('b.status', '=', 1);

            return $query->first();
        };

        $result_course = function($user_id, $course_id, $course_type){
            if ($course_type == 1){
                $onl = OnlineCourseActivity::where('course_id', '=', $course_id)
                    ->where('activity_id', '=', 2)->first();

                $result = null;
                if ($onl->subject_id){
                    $result = QuizResult::where('quiz_id', '=', $onl->subject_id)
                        ->where('user_id', '=', $user_id)->first();
                }

                return $result;
            }
            else{
                $off = OfflineCourse::find($course_id);
                $result = null;
                if ($off->quiz_id){
                    $result = QuizResult::where('quiz_id', '=', $off->quiz_id)
                        ->where('user_id', '=', $user_id)->first();
                }
                return $result;
            }
        };

        return view('converttitles::backend.convert_titles.course', [
            'course' => $course,
            'subject' => $subject,
            'result_course' => $result_course,
            'profile' => $profile,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->input('unit');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = ConvertTitles::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'b.dob',
            'b.gender',
            'c.name AS title_name_1',
            'd.name AS unit_name_1',
            'e.name AS title_name_2',
            'f.name AS unit_name_2',
            'g.name AS unit_receive_name',
        ]);
        $query->from('el_convert_titles AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_titles AS e', 'e.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS f', 'f.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS g', 'g.id', '=', 'a.unit_receive_id');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }
        if ($title) {
            $query->where(function ($sub_query) use ($title) {
                $sub_query->orWhere('c.id', '=', $title);
                $sub_query->orWhere('e.id', '=', $title);
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $query->where(function ($sub_query) use ($unit) {
                $sub_query->orWhere('d.id', '=', $unit);
                $sub_query->orWhere('f.id', '=', $unit);
                $sub_query->orWhere('g.id', '=', $unit);
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.convert_titles.edit', ['id' => $row->id]);
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->send_date = $row->send_date ? get_date($row->send_date, 'd/m/Y') : '';
            $row->dob = get_date($row->dob, 'Y');
            $row->name = $row->lastname . ' ' . $row->firstname;

            $row->course = route('module.convert_titles.course', ['user_id' => $row->user_id]);
            $row->export = route('module.convert_titles.export_course', ['user_id' => $row->user_id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'user_id' => 'required|exists:el_profile,user_id',
            'unit_id' => 'required|exists:el_unit,id',
            'unit_receive_id' => 'required|exists:el_unit,id',
            'title_id' => 'required|exists:el_titles,id',
            'start_date' => 'required',
            'end_date' => 'required',
        ], $request, ConvertTitles::getAttributeName());

        $start_date = date_convert($request->input('start_date'));
        $end_date = date_convert($request->input('end_date'), '23:59:59');

        $user_id = $request->input('user_id');
        $profile = Profile::where('user_id', '=', $user_id)->first();

        $exists1 = ConvertTitles::where('user_id', '=', $user_id)
            ->where('start_date', '<=', $start_date)
            ->where('end_date' , '>=', $start_date)
            ->where('id' , '!=', $request->id)
            ->exists();

        $exists2 = ConvertTitles::where('user_id', '=', $user_id)
            ->where('start_date', '<=', $end_date)
            ->where('end_date' , '>=', $end_date)
            ->where('id' , '!=', $request->id)
            ->exists();

        if($exists1 || $exists2){
            json_message('Thời gian chuyển đổi chức danh của ' . $profile->lastname . ' ' . $profile->firstname . ' đã tồn tại', 'error');
        }

        $model = ConvertTitles::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = date_convert($request->input('end_date'), '23:59:59');
        $model->send_date = $request->input('send_date') ? date_convert($request->input('send_date')) : null;

        if($model->start_date >= $model->end_date){
            json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.convert_titles')
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form($id = 0) {
        if ($id) {
            $model = ConvertTitles::find($id);
            $profile = Profile::findOrFail($model->user_id);
            $unit = Unit::findOrFail($model->unit_id);
            $unit_receive = Unit::findOrFail($model->unit_receive_id);
            $title = Titles::findOrFail($model->title_id);
            $page_title = $profile->lastname .' '. $profile->firstname;
            return view('converttitles::backend.convert_titles.form', [
                'model' => $model,
                'page_title' => $page_title,
                'profile' => $profile,
                'unit' => $unit,
                'title' => $title,
                'unit_receive' => $unit_receive
            ]);
        }
        $model =  new ConvertTitles();
        $page_title = trans('backend.add_new');

        return view('converttitles::backend.convert_titles.form', [
            'model' => $model,
            'page_title' =>$page_title,
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        ConvertTitles::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ConvertTitlesImports();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.convert_titles'),
        ]);
    }

    public function export()
    {
        return (new ConvertTitlesExport())->download('danh_sach_nhan_su_chuyen_doi_chuc_danh_'. date('d_m_Y') .'.xlsx');
    }

    public function exportCourse($user_id)
    {
        return (new ExportCourse($user_id))->download('danh_sach_khoa_hoc_nhan_su_chuyen_doi_chuc_danh_'. date('d_m_Y') .'.xlsx');
    }

    public function listUnit()
    {
        return view('converttitles::backend.convert_titles.list_unit');
    }

    public function getDataListUnit(Request $request) {
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $title = $request->input('title');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $profile = Profile::find(\Auth::id());
        $unit_manager = UnitManager::where('user_code', '=', $profile->code)->pluck('unit_code')->toArray();

        $query = ConvertTitles::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'b.dob',
            'b.gender',
            'b.user_id',
            'c.name AS title_name_1',
            'd.name AS unit_name_1',
            'e.name AS title_name_2',
            'f.name AS unit_name_2',
            'g.name AS unit_receive_name',
        ]);
        $query->from('el_convert_titles AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_titles AS e', 'e.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS f', 'f.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS g', 'g.id', '=', 'a.unit_receive_id');
        if ($unit_manager){
            $query->whereIn('f.code', $unit_manager);
        }
        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('b.code', 'like', '%'. $search .'%');
                $subquery->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }
        if ($start_date && $end_date) {
            $query->where('a.start_date', '>=', date_convert($start_date));
            $query->where('a.start_date', '<=', date_convert($end_date, '23:59:59'));
        }
        if ($title){
            $query->where('a.title_id', '=', $title);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->name = $row->lastname . ' ' . $row->firstname;
            $convert_titles_reviews = ConvertTitlesReviews::where('title_id', '=', $row->title_id)->first();
            if ($convert_titles_reviews){
                $warequery = Warehouse::where('file_path', '=', $convert_titles_reviews->file_reviews);
                if ($warequery->exists()) {
                    $row->file_name = $warequery->first()->file_name;
                }
                $row->link_download = ($convert_titles_reviews->file_reviews) ? \link_download($convert_titles_reviews->file_reviews) : '';

                $row->download_file_review = ($row->file_reviews_unit) ? \link_download($row->file_reviews_unit) : '';
            }
            $row->file = $row->file_reviews_unit;
            $row->convert_titles_id = $row->id;
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->send_date = $row->send_date ? get_date($row->send_date, 'd/m/Y') : '';
            $row->dob = get_date($row->dob, 'Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveFile(Request $request) {
        $this->validateRequest([
            'convert_titles_id' => '',
            'path' => 'required',
        ], $request);

        $convert_titles_id = $request->input('convert_titles_id');
        $file = $request->input('path');

        $convert_titles = ConvertTitles::find($convert_titles_id);
        $convert_titles->file_reviews_unit = path_upload($file);
        $convert_titles->save();

        json_message('ok');
    }

    public function exportEmployees(Request $request){
        $user_id = $request->user_id;
        return (new ResultConvertTitlesExport($user_id))->download('ket_qua_chuyen_doi_chuc_danh_'. date('d_m_Y') .'.xlsx');
    }
}
