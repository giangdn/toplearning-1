<?php

namespace Modules\ConvertTitles\Http\Controllers;

use App\Models\Categories\Titles;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ConvertTitles\Entities\ConvertTitlesReviews;

class ConvertTitlesReviewsController extends Controller
{
    public function index()
    {
        return view('converttitles::backend.convert_titles_reviews.index');
    }

    public function getData(Request $request) {
        $title = $request->input('title');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = ConvertTitlesReviews::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
        ]);
        $query->from('el_convert_titles_reviews AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');

        if ($title) {
            $query->where('b.id', '=', $title);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.convert_titles.reviews.edit', ['id' => $row->id]);

            $warequery = Warehouse::where('file_path', '=', $row->file_reviews);
            if ($warequery->exists()) {
                $row->file_reviews = $warequery->first()->file_name;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'title_id' => 'required|exists:el_titles,id',
            'file_reviews' => 'required|string',
        ], $request, ConvertTitlesReviews::getAttributeName());

        $exists = ConvertTitlesReviews::where('title_id', '=', $request->input('title_id'))->exists();

        if ($exists){
            json_message('Chức danh đã có mẫu đánh giá', 'error');
        }

        $model = ConvertTitlesReviews::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->file_reviews = path_upload($model->file_reviews);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.convert_titles.reviews')
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form($id = 0) {
        if ($id) {
            $model = ConvertTitlesReviews::find($id);
            $title = Titles::findOrFail($model->title_id);
            $page_title = $title->name;
            return view('converttitles::backend.convert_titles_reviews.form', [
                'model' => $model,
                'page_title' => $page_title,
                'title' => $title,
            ]);
        }
        $model =  new ConvertTitlesReviews();
        $page_title = trans('backend.add_new');

        return view('converttitles::backend.convert_titles_reviews.form', [
            'model' => $model,
            'page_title' =>$page_title,
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        ConvertTitlesReviews::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
