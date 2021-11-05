<?php

namespace Modules\NewsOutside\Http\Controllers;

use App\Profile;
use App\ProfileView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\NewsOutside\Entities\NewsOutside;
use Modules\NewsOutside\Entities\NewsOutsideCategory;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Warehouse;
use App\Scopes\DraftScope;

class NewsOutsideController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $cates = NewsOutsideCategory::get();
        return view('newsoutside::backend.news.index',[
            'cates' => $cates,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $type = $request->type;
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $cate_id = $request -> input('cate_id');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset =$request->input('offset',0);
        $limit = $request->input('limit',20);
        NewsOutside::addGlobalScope(new DraftScope());
        $query = NewsOutside::query();
        $query->select([
            'el_news_outside.id',
            'el_news_outside.title',
            'el_news_outside.created_at',
            'el_news_outside.views',
            'el_news_outside.like_new',
            'el_news_outside.updated_by',
            'el_news_outside.created_by',
            'el_news_outside.status',
            'el_news_outside.updated_at',
            'el_news_outside.type',
            'b.name AS category_name'
        ]);
        $query->leftJoin('el_news_outside_category AS b', 'b.id', '=', 'el_news_outside.category_id');

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('el_news_outside.title','like','%' . $search . '%');
            });
        }
        if($cate_id){
            $query->where(function($sub_query) use ($cate_id){
                $sub_query->where('el_news_outside.category_id',$cate_id);
                $sub_query->orWhere('el_news_outside.category_parent_id',$cate_id);
            });
        }
        if ($start_date) {
            $query->where('el_news_outside.created_at', '>=', date_convert($start_date));
        }
        if ($end_date) {
            $query->where('el_news_outside.created_at', '<=', date_convert($end_date, '23:59:59'));
        }
        if ($type) {
            $query->where('el_news_outside.type', '=', $type);
        }
        $count = $query->count();
        $query->orderBy('el_news_outside.'.$sort,$order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.news_outside.edit', ['id' => $row->id]);

            $created_by = ProfileView::where('user_id', '=', $row->created_by)->first();
            $updated_by = ProfileView::where('user_id', '=', $row->updated_by)->first();
            $row->created_by = $created_by->full_name;
            $row->updated_by = $updated_by ? $updated_by->full_name : '';

            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $categories = NewsOutsideCategory::query()->whereNotNull('parent_id')->get();

        if ($id) {
            $model = NewsOutside::find($id);
            $page_title = $model->title;

            return view('newsoutside::backend.news.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new NewsOutside();
        $page_title = trans('backend.add_new') ;

        return view('newsoutside::backend.news.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'category_id' => 'required|exists:el_news_outside_category,id',
            'title' => 'required',
            'description' => 'required',
            'content' => 'required_if:type,1',
            'status'=>'required',
            'image' => 'nullable|string',
            'type' => 'required',
        ], $request, NewsOutside::getAttributeName());

        $get_parent_cate_id = NewsOutsideCategory::find($request->category_id);

        $count_news_hot = NewsOutside::where('hot',1)->where('category_id',$request->category_id)->get();
        $count_news_hot = count($count_news_hot);

        if($request->hot == 1 && $count_news_hot == 4 && !$request->id){
            json_message('Tin hot mục con chỉ được tối đa 4 tin', 'error');
        } else if ($request->id) {
            $count_news_hot_id = NewsOutside::where('hot',1)->where('id','!=',$request->id)->where('category_id',$request->category_id)->get();
            $count_news_hot_id = count($count_news_hot_id);
            if($request->hot == 1 && $count_news_hot_id == 4) {
                json_message('Tin hot mục con chỉ được tối đa 4 tin', 'error');
            }
        }

        if($request->id && $request->number_setup) {
            $get_new_id = NewsOutside::find($request->id);
            $date_setup_icon = Carbon::parse($get_new_id->created_at)->addDays($request->number_setup)->format('Y-m-d H:s');
        } else if (!$request->id && $request->number_setup) {
            $date = date('Y-m-d H:s');
            $date_setup_icon = Carbon::parse($date)->addDays($request->number_setup)->format('Y-m-d H:s');
        } else {
            $date_setup_icon = date('Y-m-d H:s');
        }

        $type = $request->type;
        $flag = $request->flag;
        if ($type == 2) {
            $this->validateRequest([
                'video' => 'required',
            ], $request, NewsOutside::getAttributeName());
            $content = path_upload($request->video);
        } else if ($type == 3 && $flag == 0) {
            $this->validateRequest([
                'pictures' => "required|array|min:1",
                'pictures.*' => 'required|mimes:jpeg,bmp,png,gif,svg,pdf',
            ], $request, NewsOutside::getAttributeName());
            if ($request->hasfile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    $folder_id = '';

                    if (empty($folder_id)) {
                        $folder_id = null;
                    }

                    $type_file = 'images';
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) . '-' . time() . '-' . Str::random(10) . '.' . $extension;
                    $storage = \Storage::disk('upload');
                    $new_paths[] = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
                }
                $content = json_encode($new_paths);
            } else {
                return back()->with('false', 'Chưa chọn file');
            }
        } else if ($type == 1) {
            $content = $request->input('content');
        } else {
            $content = $request->content_of_id;
        }

        $description = html_entity_decode($request->input('description'),ENT_HTML5, "UTF-8");

        $model = NewsOutside::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->category_parent_id = $get_parent_cate_id->parent_id;

        $model->description = $description;

        if ($request->image) {
            $sizes = config('image.sizes.news');
            $model->image = upload_image($sizes, $request->image);
        }
        
        if (empty($model->id)){
            $model->created_by = Auth::id();
        }
        $model->content = $content;
        $model->updated_by = Auth::id();
        $model->date_setup_icon = $date_setup_icon;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.news_outside.manager')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        NewsOutside::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = NewsOutside::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = NewsOutside::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }
}
