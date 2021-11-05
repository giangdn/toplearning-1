<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class CourseCategoriesController extends Controller
{
    public function index() {
        return view('backend.category.course_categories.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseCategories::query();
        $query->select(['a.*', 'b.name AS parent_name']);
        $query->from('el_course_categories AS a');
        $query->leftJoin('el_course_categories AS b', 'b.id', '=', 'a.parent_id');

        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.course_categories.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = CourseCategories::findOrFail($id);
            $page_title = $model->name;
        }
        else {
            $model = new CourseCategories();
            $page_title = trans('backend.add_new');
        }
        
        $parents = CourseCategories::getCourseCategoriesParent($model->id);
        return view('backend.category.course_categories.form', [
            'model' => $model,
            'page_title' => $page_title,
            'parents' => $parents
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'parent_id' => 'nullable|exists:el_course_categories,id',
            'status' => 'required|in:0,1',
            'type' => 'required|in:1,2',
        ], $request, CourseCategories::getAttributeName());

        $model = CourseCategories::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            if ($request->id){
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                    'redirect' => route('backend.category.course_categories.edit', [
                        'id' => $model->id
                    ])
                ]);
            }else{
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                    'redirect' => route('backend.category.course_categories.create')
                ]);
            }

        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $off = OfflineCourse::whereIn('category_id', $ids)->first();
        $onl = OnlineCourse::whereIn('category_id', $ids)->first();

        if ($off || $onl){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }

        CourseCategories::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
