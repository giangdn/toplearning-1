<?php

namespace Modules\ConvertTitles\Http\Controllers;

use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use Modules\ConvertTitles\Exports\ExportSubjectByTitle;
use Modules\ConvertTitles\Imports\ConvertTitlesRoadmapImport;
use Modules\ConvertTitles\Entities\ConvertTitlesRoadmap;
use Illuminate\Support\Facades\Auth;

class ConvertTitlesRoadmapController extends Controller
{
    public function listTitle()
    {
        return view('converttitles::backend.convert_titles_roadmap.list_title');
    }

    public function getDataTitle(Request $request) {
        $title = $request->input('title');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = Titles::query();
        if ($title){
            $query->where('id', '=', $title);
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->title_url = route('module.convert_titles.roadmap', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function index($title_id)
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $model = Titles::find($title_id);
        $page_title = $model->name;
        return view('converttitles::backend.convert_titles_roadmap.index',[
            'title_id' => $title_id,
            'errors' => $errors,
            'page_title' => $page_title
        ]);
    }
    public function getData($title_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $subject = $request->input('subject_name');

        $query = ConvertTitlesRoadmap::query();
        $query->select(['a.*' , 'b.code AS subject_code','b.name AS subject_name']);
        $query->from('el_convert_titles_roadmap AS a');
        $query->where('a.title_id', '=', $title_id);
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        if ($subject) {
            $query->where('a.subject_id', '=', $subject);
        }
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.convert_titles.roadmap.edit', ['id' => $title_id,'train_id'=>$row->id]);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function save($title_id,Request $request) {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
        ], $request, ConvertTitlesRoadmap::getAttributeName());
        $training_program_id = $request->input('training_program_id');
        $subject_id = $request->input('subject_id');

        if(ConvertTitlesRoadmap::checkSubjectExits($training_program_id, $subject_id ,$title_id, $request->id)){
            json_message('Học phần đã tồn tại', 'error');
        }

        $model = ConvertTitlesRoadmap::firstOrNew(['id' => $request->id]);
        $model->title_id = $title_id;
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.convert_titles.roadmap',['id' => $title_id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }
    public function form($title_id,$id = 0) {
        if ($id) {
            $model = ConvertTitlesRoadmap::find($id);
            $subject = Subject::find($model->subject_id);
            $page_title = $subject->name;
            $title = Titles::find($title_id);
            $page_title_name = $title->name;
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            return view('converttitles::backend.convert_titles_roadmap.form', [
                'model' => $model,
                'subject' => $subject,
                'page_title' => $page_title,
                'title_id' => $title_id ,
                'title' => $title,
                'page_title_name' => $page_title_name,
                'training_program' => $training_program,
            ]);
        }
        $model =  new ConvertTitlesRoadmap();
        $title = Titles::find($title_id);
        $page_title_name = $title->name;
        $page_title = trans('backend.add_new');

        return view('converttitles::backend.convert_titles_roadmap.form', [
            'model' => $model,
            'page_title' =>$page_title,
            'title_id' => $title_id ,
            'title' => $title,
            'page_title_name' => $page_title_name
        ]);
    }
    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        ConvertTitlesRoadmap::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function import($title_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ConvertTitlesRoadmapImport($title_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.convert_titles.roadmap',['id'=>$title_id])
        ]);
    }
    public function export($title_id, Request $request){
        $subject_name = $request->subject_name;

        return (new ExportSubjectByTitle($title_id, $subject_name))->download('danh_sach_hoc_phan_chuong_trinh_khung_chuyen_doi_chuc_danh_'. date('d_m_Y') .'.xlsx');
    }
}
