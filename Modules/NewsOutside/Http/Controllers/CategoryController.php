<?php

namespace Modules\NewsOutside\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\NewsOutside\Entities\NewsOutsideCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('newsoutside::backend.news_category.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);

        $query = DB::query();
        $query->from('el_news_outside_category');

        if($search){
            $query->where('name', 'like', '%' . $search . '%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->icon = image_file($row->icon);
            $row->edit_url = route('module.news_outside.category.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = NewsOutsideCategory::findOrFail($request->id);
        $parent_cate = NewsOutsideCategory::where('id',$model->parent_id)->first();
        json_result([
            'model' => $model,
            'parent_cate' => $parent_cate,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'parent_id' => 'nullable|exists:el_news_outside_category,id',
            // 'sort' => 'required_if:parent_id,true|nullable|string',
            // 'stt_sort' => 'required_if:parent_id,true|nullable|string',
        ], $request, NewsOutsideCategory::getAttributeName());

        if($request->parent_id && empty($request->stt_sort)) {
            json_message('Vui lòng chọn số thứ tự sắp xếp', 'error');
        } elseif(!$request->parent_id && empty($request->stt_sort_parent)) {
            json_message('Vui lòng chọn số thứ tự sắp xếp cấp cha', 'error');
        }

        if (empty($request->stt_sort)) {
            if($request->id) {
                $getSttParentNewOutsideCategoryWithId = NewsOutsideCategory::find($request->id);

                $checkSttParentNewOutsideCategory = NewsOutsideCategory::where('stt_sort_parent', $request->stt_sort_parent)->first();
                if($checkSttParentNewOutsideCategory) {
                    $checkSttParentNewOutsideCategory->stt_sort_parent = $getSttParentNewOutsideCategoryWithId->stt_sort_parent;
                    $checkSttParentNewOutsideCategory->save();
                }
            } else {
                $checkSttParentNewOutsideCategory = NewsOutsideCategory::whereNull('parent_id')->pluck('stt_sort_parent')->toArray();
                if(in_array($request->stt_sort_parent, $checkSttParentNewOutsideCategory)) {
                    json_message('Số thứ tự sắp xếp cấp cha đã tồn tại', 'error');
                }
            }
        }

        if (empty($request->sort) && $request->parent_id) {
            if($request->id) {
                $getSttNewOutsideCategoryWithId = NewsOutsideCategory::find($request->id);

                $checkSttNewOutsideCategory = NewsOutsideCategory::where('sort',0)->where('stt_sort',$request->stt_sort)->where('parent_id',$request->parent_id)->first();
                if($checkSttNewOutsideCategory) {
                    $checkSttNewOutsideCategory->stt_sort = $getSttNewOutsideCategoryWithId->stt_sort;
                    $checkSttNewOutsideCategory->sort = $getSttNewOutsideCategoryWithId->sort;
                    $checkSttNewOutsideCategory->save();
                }
            } else {
                $checkNewOutsideCategoryLeft = NewsOutsideCategory::where('sort',0)->where('parent_id',$request->parent_id)->pluck('stt_sort')->toArray();
                if(in_array($request->stt_sort, $checkNewOutsideCategoryLeft)) {
                    json_message('Số thứ tự sắp xếp đã tồn tại', 'error');
                }
            }
        } else if ($request->sort == 2) {
            if($request->id) {
                $getSttNewOutsideCategoryWithId = NewsOutsideCategory::find($request->id);
                
                $checkSttNewOutsideCategory = NewsOutsideCategory::where('sort',2)->where('stt_sort',$request->stt_sort)->where('parent_id',$request->parent_id)->first();
                if($checkSttNewOutsideCategory) {
                    $checkSttNewOutsideCategory->stt_sort = $getSttNewOutsideCategoryWithId->stt_sort;
                    $checkSttNewOutsideCategory->sort = $getSttNewOutsideCategoryWithId->sort;
                    $checkSttNewOutsideCategory->save();
                }
            } else {
                $checkNewOutsideCategoryRight = NewsOutsideCategory::where('sort',2)->where('parent_id',$request->parent_id)->pluck('stt_sort')->toArray();
                if(in_array($request->stt_sort, $checkNewOutsideCategoryRight)) {
                    json_message('Số thứ tự sắp xếp đã tồn tại', 'error');
                }
            }
        }
        
        $model = NewsOutsideCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->status = $request->status ? $request->status : 0;
        $model->sort = $request->sort ? $request->sort : 0;
        $model->icon = path_upload($model->icon);
        if (empty($model->id)){
            $model->created_by = Auth::id();
        }
        $model->updated_by = Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.news_outside.category')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        NewsOutsideCategory::query()->whereIn('parent_id', $ids)->delete();
        NewsOutsideCategory::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
