<?php

namespace Modules\TrainingByTitle\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;

class TrainingByTitleDetailController extends Controller
{
    public function index($id){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $training_titles = Titles::find($id);
        $training_titles_categories = TrainingByTitleCategory::where('training_title_id', '=', $id)->get();
        return view('trainingbytitle::backend.training_by_title_detail.index', [
            'training_titles' => $training_titles,
            'training_titles_categories' => $training_titles_categories,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'learning_manager',
        ]);
    }

    public function saveCategory($id, Request $request){
        $this->validateRequest([
            'name' => 'required',
            'num_date_category' => 'required',
        ], $request, [
            'name' => 'Tên danh mục',
            'num_date_category' => 'Số ngày',
        ]);

        $training_title = Titles::find($id);

        $model = TrainingByTitleCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->training_title_id = $training_title->id;
        $model->title_id = $training_title->id;
        $model->name = $request->name;
        $model->num_date_category = $request->num_date_category;
        $model->save();

        $check_training_title_details = TrainingByTitleDetail::where('training_title_category_id',$model->id)->get();
        if( !empty($check_training_title_details) ) {
            foreach($check_training_title_details as $check_training_title_detail) {
                $save_training_title_detail = TrainingByTitleDetail::find($check_training_title_detail->id);
                $save_training_title_detail->num_date = $request->num_date_category;
                $save_training_title_detail->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.training_by_title.detail', ['id' => $id]),
        ]);
    }

    public function save($id, Request $request){
        $this->validateRequest([
            'subject_id' => 'required|exists:el_subject,id',
            'num_time' => 'required|min:1',
        ], $request, [
            'subject_id' => trans('backend.subject'),
            'num_time' => 'Thời lượng',
        ]);

        $training_title = Titles::find($id);
        $training_title_category_id = $request->training_title_category_id;
        $subject_id = $request->subject_id;
        $subject = Subject::find($subject_id);

        $get_num_date = TrainingByTitleCategory::where('id',$request->training_title_category_id)->first();
        $check = TrainingByTitleDetail::where('subject_id', '=', $subject_id)
            ->where('training_title_id', '=', $id)
            ->exists();
        if ($check){
            json_message('Chuyên đề đã được thêm', 'warning');
        }

        $model = TrainingByTitleDetail::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->training_title_id = $training_title->id;
        $model->title_id = $training_title->id;
        $model->training_title_category_id = $training_title_category_id;
        $model->subject_id = $subject->id;
        $model->subject_code = $subject->code;
        $model->subject_name = $subject->name;
        $model->num_date = $get_num_date->num_date_category;
        $model->num_time = $request->num_time;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.training_by_title.detail', ['id' => $id]),
        ]);
    }

    public function removeCategory($id, Request $request){

        TrainingByTitleDetail::where('training_title_category_id', '=', $request->id)->delete();
        TrainingByTitleCategory::find($request->id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function remove($id, Request $request){

        TrainingByTitleDetail::find($request->id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function editDetail($id, Request $request) {
        $this->validateRequest([
            'edit_num_time' => 'required|min:1',
        ], $request, [
            'edit_num_time' => 'Thời lượng',
        ]);
        $get_num_date = TrainingByTitleCategory::where('id', $request->training_title_category_id_edit)->first();
        $model = TrainingByTitleDetail::find($request->id_training_detail);
        $model->num_time = $request->edit_num_time;
        $model->num_date = $get_num_date->num_date_category;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.training_by_title.detail', ['id' => $id]),
        ]);
    }
}
