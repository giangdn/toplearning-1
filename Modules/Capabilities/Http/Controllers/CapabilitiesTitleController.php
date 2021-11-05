<?php

namespace Modules\Capabilities\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesCategory;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use Modules\Capabilities\Entities\CapabilitiesTitleSubject;
use App\Models\Categories\Titles;
use Modules\Capabilities\Imports\CapabilitiesTitleImport;
use Modules\Capabilities\Exports\CapabilitiesTitleExport;
use Modules\Capabilities\Imports\CapabilitiesTitleSubjectImport;
use Modules\Capabilities\Exports\CapabilitiesTitleSubjectExport;
use Modules\Online\Entities\CourseCategories;

class CapabilitiesTitleController extends Controller
{
    public function index() {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $capabilities = Capabilities::get();
        $titles = Titles::query()
            ->from('el_titles')
            ->whereNotIn('id', function ($sub){
                $sub->select(['title_id'])
                    ->from('el_capabilities_title')
                    ->groupBy('title_id');
            })->get();
        
        return view('capabilities::backend.capabilities_title.index', [
            'capabilities' => $capabilities,
            'titles' => $titles,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        
        $capabilities = $request->input('capabilities');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CapabilitiesTitle::query();
        $query->select(['a.*', 'b.code AS capabilites_code', 'b.name AS capabilities_name', 'c.name AS category_name', 'd.name AS title_name']);
        $query->from('el_capabilities_title AS a');
        $query->leftJoin('el_capabilities AS b', 'b.id', '=', 'a.capabilities_id');
        $query->leftJoin('el_capabilities_category AS c', 'c.id', '=', 'b.category_id');
        $query->leftJoin('el_titles AS d', 'd.id', '=', 'a.title_id');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('d.name', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('d.id', '=', $title);
        }

        if ($capabilities) {
            $query->where('b.id', '=', $capabilities);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        
        $rows = $query->get();
        
        foreach ($rows as $row) {
            $row->edit_url = route('module.capabilities.title.edit', ['id' => $row->id]);
            $row->course_url = route('module.capabilities.title.course', ['id' => $row->id]);
            $row->goal = round($row->goal, 2);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $categories = CapabilitiesCategory::get();
        $capabilities = Capabilities::get();
        $titles = Titles::get();

        if ($id) {
            $model = CapabilitiesTitle::find($id);
            $capability = Capabilities::find($model->capabilities_id);
            $page_title = $capability->name;

            return view('capabilities::backend.capabilities_title.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'capability' => $capability,
                'capabilities' => $capabilities,
                'titles' => $titles,
            ]);
        }

        $model = new CapabilitiesTitle();
        $page_title = trans('backend.add_new');
        
        return view('capabilities::backend.capabilities_title.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
            'capabilities' => $capabilities,
            'titles' => $titles,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'capabilities_id' => 'nullable|exists:el_capabilities,id',
            'title_id' => 'nullable|exists:el_titles,id',
            'weight' => 'numeric|min:1|max:100',
            'critical_level' => 'nullable|min:0',
            'level' => 'nullable|in:1,2,3,4',
            'number_title' => 'numeric|min:1'
        ], $request, CapabilitiesTitle::getAttributeName());

        $capabilities_id = $request->capabilities_id;
        $title_id = $request->title_id;
        $number_title = $request->number_title;
        $weight = $request->weight;

        $title = Titles::find($title_id);

        $total_weight = CapabilitiesTitle::checkWeight($title_id, $request->id);
        if ($total_weight == 100){
            json_message('Chức danh ' . $title->name . ' không thể thêm được nữa.', 'error');
        }

        if (($total_weight + $weight) > 100){
            json_message('Trọng số chức danh ' . $title->name . ' chỉ còn thêm được ' . (100 - $total_weight) . '%', 'error');
        }

        $model = CapabilitiesTitle::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if(CapabilitiesTitle::checkExists($capabilities_id, $title_id, $request->id)){
            $capa = Capabilities::find($capabilities_id);
            json_message('Năng lực '. $capa->name . ' thuộc chức danh '. $title->name .' đã tồn tại', 'error');
        }

        if(CapabilitiesTitle::checkNumber($title_id, $number_title, $request->id)){
            json_message('STT '. $number_title .' thuộc chức danh '. $title->name .' đã tồn tại', 'error');
        }

        $model->goal = CapabilitiesTitle::getGoal($model->level, $model->critical_level, $model->weight);

        if ($model->save()) {

            $capabilites = Capabilities::find($capabilities_id);
            $total_weight_capa_cate = Capabilities::getTotalWeightByTitleGroup($title_id, $capabilites->category_id);

            $capa_title_group = Capabilities::getByTitleGroup($title_id, $capabilites->category_id);
            foreach ($capa_title_group as $item){
                $capa_title = CapabilitiesTitle::where('title_id', '=', $title_id)
                    ->where('capabilities_id', '=', $item->capabilities_id)
                    ->first();

                $capa_title->goal = CapabilitiesTitle::getGoal($capa_title->level, $capa_title->critical_level, $total_weight_capa_cate);
                $capa_title->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.capabilities.title')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function copy(Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Năng lực',
        ]);
        $title_id = $request->title_id;
        $ids = $request->input('ids', null);

        foreach($ids as $id){
            $model = CapabilitiesTitle::find($id);
            $newModel = $model->replicate();
            $newModel->title_id = $title_id;
            if(CapabilitiesTitle::checkExists($model->capabilities_id, $title_id)){
                continue;
            }
            $newModel->save();

            $title_subject = CapabilitiesTitleSubject::where('capabilities_title_id', '=', $id)->get();
            foreach($title_subject as $item){
                $newTitleSubject = $item->replicate();
                $newTitleSubject->capabilities_title_id = $newModel->id;
                $newTitleSubject->save();
            }
        }
        json_result([
            'message' => 'Thêm thành công',
            'status' => 'success'
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $item = CapabilitiesTitleSubject::whereIn('capabilities_title_id', $ids)->first();
        if ($item){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }

        CapabilitiesTitle::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetCapabilities(Request $request)
    {
        $this->validateRequest([
            'category_id' => 'required|exists:el_capabilities_category,id',
        ], $request, [
            'category_id' => 'Danh mục năng lực',
        ]);
        $category_id = $request->category_id;

        $category_id = CapabilitiesCategory::find($category_id);

        $capabilities = Capabilities::where('category_id', '=', $category_id->id)->get();

        json_result($capabilities);
    }

    public function importCapabilitiesTitle(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new CapabilitiesTitleImport(); 
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.capabilities.title')
        ]);
    }

    public function exportCapabilitiesTitle(Request $request)
    {
        $title = $request->input('title');

        return (new CapabilitiesTitleExport($title))->download('danh_sach_khung_nang_luc_theo_danh_muc_'. date('d_m_Y') .'.xlsx');
    }

    public function course($id = 0)
    {
        $errors = session()->get('errors');
        \Session::forget('errors');
        if ($id) {
            $model = CapabilitiesTitle::find($id);

            $title = Titles::find($model->title_id);

            $capabi = Capabilities::find($model->capabilities_id);

            return view('capabilities::backend.capabilities_title.course', [
                'model' => $model,
                'capabi' => $capabi,
                'title' => $title
            ]);
        }
    }

    public function saveCourse($capabilities_title_id, Request $request)
    {
        $this->validateRequest([
            'subject_id' => 'required|exists:el_subject,id',
            'level_subject' => 'required|in:1,2,3,4',
        ], $request, CapabilitiesTitleSubject::getAttributeName());

        $subject_id = $request->input('subject_id');
        $level = $request->input('level_subject');

        $capabilities_title = CapabilitiesTitle::find($capabilities_title_id);
        if ($level > $capabilities_title->level){
            json_message('Cấp độ không cho phép', 'error');
        }

        if(CapabilitiesTitleSubject::checkSubjectExits($capabilities_title_id, $subject_id)){
            json_message('Khóa học đã tồn tại', 'error');
        }

        $model = new CapabilitiesTitleSubject();
        $model->subject_id = $subject_id;
        $model->capabilities_title_id = $capabilities_title_id;
        $model->level = $level;

        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Thêm khóa học thành công',
            ]);
        }
    }

    public function getCourse($capabilities_title_id, Request $request){
        $sort = $request->input('sort', 'level');
        $training_program = $request->input('training_program');
        $subject = $request->input('subject');
        $level_subject = $request->input('level_subject');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CapabilitiesTitleSubject::query();
        $query->select(['a.*', 'b.name AS subject_name', 'c.name AS training_program_name']);
        $query->from('el_capabilities_title_subject AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_training_program AS c', 'c.id', '=', 'b.training_program_id');
        $query->where('a.capabilities_title_id', '=', $capabilities_title_id);

        if ($training_program) {
            $query->where('c.id', '=', $training_program);
        }

        if ($subject) {
            $query->where('b.id', '=', $subject);
        }

        if ($level_subject) {
            $query->where('a.level', '=', $level_subject);
        }
    
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        
        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeCourse($capabilities_title_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Học phần',
        ]); 

        $item = $request->input('ids');
        CapabilitiesTitleSubject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importCapabilitiesTitleSubject($capabilities_title_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new CapabilitiesTitleSubjectImport($capabilities_title_id); 
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.capabilities.title.course', ['id' => $capabilities_title_id])
        ]);
    }
}
