<?php

namespace Modules\News\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\News\Entities\NewsCategory;
use Illuminate\Support\Facades\Auth;
class CategoryController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        $categories = NewsCategory::whereNull('parent_id')->get();
        return view('news::backend.news_category.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'categories' => $categories
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        NewsCategory::addGlobalScope(new DraftScope());
        $query = NewsCategory::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.news.category.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = NewsCategory::findOrFail($request->id);
        $parent_cate = NewsCategory::where('id',$model->parent_id)->first();
        json_result([
            'model' => $model,
            'parent_cate' => $parent_cate,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'parent_id' => 'nullable|exists:el_news_category,id',
        ], $request, NewsCategory::getAttributeName());

        if($request->parent_id && empty($request->stt_sort)) {
            json_message('Vui lòng chọn số thứ tự sắp xếp', 'error');
        } 

        if ( empty($request->parent_id) && isset($request->stt_sort_parent) ) {
            if($request->id) {
                $getSttParentNewCategoryWithId = NewsCategory::find($request->id);

                $checkSttParentNewCategory = NewsCategory::where('stt_sort_parent', $request->stt_sort_parent)->first();
                if($checkSttParentNewCategory) {
                    $checkSttParentNewCategory->stt_sort_parent = $getSttParentNewCategoryWithId->stt_sort_parent;
                    $checkSttParentNewCategory->save();
                }
            } else {
                $checkParentNewCategory = NewsCategory::whereNull('parent_id')->pluck('stt_sort_parent')->toArray();
                if(in_array($request->stt_sort_parent, $checkParentNewCategory)) {
                    json_message('Số thứ tự sắp xếp đã tồn tại', 'error');
                }
            }
        }

        if ($request->sort == 0 && $request->parent_id) {
            if($request->id) {
                $getSttNewCategoryWithId = NewsCategory::find($request->id);

                $checkSttNewCategory = NewsCategory::where('sort',0)->where('stt_sort',$request->stt_sort)->where('parent_id',$request->parent_id)->first();
                if($checkSttNewCategory) {
                    $checkSttNewCategory->stt_sort = $getSttNewCategoryWithId->stt_sort;
                    $checkSttNewCategory->sort = $getSttNewCategoryWithId->sort;
                    $checkSttNewCategory->save();
                }
            } else {
                $checkNewCategoryLeft = NewsCategory::where('sort',0)->where('parent_id',$request->parent_id)->pluck('stt_sort')->toArray();
                if(in_array($request->stt_sort, $checkNewCategoryLeft)) {
                    json_message('Số thứ tự sắp xếp đã tồn tại', 'error');
                }
            }
        } else if ($request->sort == 2 && $request->parent_id) {
            if($request->id) {
                $getSttNewCategoryWithId = NewsCategory::find($request->id);
                
                $checkSttNewCategory = NewsCategory::where('sort',2)->where('stt_sort',$request->stt_sort)->where('parent_id',$request->parent_id)->first();
                if($checkSttNewCategory) {
                    $checkSttNewCategory->stt_sort = $getSttNewCategoryWithId->stt_sort;
                    $checkSttNewCategory->sort = $getSttNewCategoryWithId->sort;
                    $checkSttNewCategory->save();
                }
            } else {
                $checkNewCategoryRight = NewsCategory::where('sort',2)->where('parent_id',$request->parent_id)->pluck('stt_sort')->toArray();
                if(in_array($request->stt_sort, $checkNewCategoryRight)) {
                    json_message('Số thứ tự sắp xếp đã tồn tại', 'error');
                }
            }
        }

        $model = NewsCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->status = $request->status ? $request->status : 0;
        $model->sort = $request->sort ? $request->sort : 0;
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.news.category')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        NewsCategory::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
