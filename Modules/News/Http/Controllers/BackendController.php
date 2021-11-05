<?php

namespace Modules\News\Http\Controllers;

use App\Profile;
use App\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\News\Entities\News;
use Modules\News\Entities\NewsCategory;
use Modules\News\Entities\NewsLink;
use Modules\News\Entities\NewsObject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Warehouse;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\NewsStatistic;
use Carbon\Carbon;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

class BackendController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $cates = NewsCategory::get();
        return view('news::backend.news.index',[
            'cates' => $cates,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $cate_id = $request -> input('cate_id');
        $type = $request -> type;
        $sort = $request ->input('sort','id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        News::addGlobalScope(new DraftScope());
        $query = News::query();
        $query->select([
            'el_news.id',
            'el_news.title',
            'el_news.created_at',
            'el_news.views',
            'el_news.like_new',
            'el_news.updated_by',
            'el_news.created_by',
            'el_news.status',
            'el_news.updated_at',
            'el_news.type',
            'b.name AS category_name',
        ]);
        $query->leftJoin('el_news_category AS b', 'b.id', '=', 'el_news.category_id');

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('el_news.title','like','%' . $search . '%');
            });
        }
        if($cate_id){
            $query->where(function($sub_query) use ($cate_id){
                $sub_query->where('el_news.category_id',$cate_id);
                $sub_query->orWhere('el_news.category_parent_id',$cate_id);
            });
        }
        if ($start_date) {
            $query->where('el_news.created_at', '>=', date_convert($start_date));
        }
        if ($end_date) {
            $query->where('el_news.created_at', '<=', date_convert($end_date, '23:59:59'));
        }
        if ($type) {
            $query->where('el_news.type', '=', $type);
        }
        $count = $query ->count();
        $query -> orderBy('el_news.'.$sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();

        foreach ($rows as $row) {
            $created_by = ProfileView::where('user_id', '=', $row->created_by)->first();
            $updated_by = ProfileView::where('user_id', '=', $row->updated_by)->first();
            $row->created_by = $created_by->full_name;
            $row->updated_by = $updated_by->full_name;
            $row->edit_url = route('module.news.edit', ['id' => $row->id]);

            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $categories = NewsCategory::whereNotNull('parent_id')->get();

        if ($id) {
            $model = News::find($id);
            $page_title = $model->title;
            $titles = Titles::where('status', '=', 1)->get();

            $news_link = NewsLink::where('news_id', $id)->get();
            return view('news::backend.news.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'titles' => $titles,
                'news_link' => $news_link,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new News();
        $page_title = trans('backend.add_new') ;

        return view('news::backend.news.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'category_id' => 'nullable|exists:el_news_category,id',
            'title' => 'required',
            'type' => 'required',
            'description' => 'required',
            'status'=>'required',
            'image' => 'nullable|string',
            'content' => 'required_if:type,1',
        ], $request, News::getAttributeName());

        $news_link_url = $request->news_link_url;
        $news_link_title = $request->news_link_title;
        $news_link_type = $request->news_link_type;
        $link_id = $request->link_id;

        $get_parent_id_cate = NewsCategory::find($request->category_id);

        $count_news_hot = News::where('hot',1)->where('category_id',$request->category_id)->get();
        $count_news_hot = count($count_news_hot);

        if($request->hot == 1 && $count_news_hot == 4 && !$request->id){
            json_message('Tin hot mục con chỉ được tối đa 4 tin', 'error');
        } else if ($request->id) {
            $count_news_hot_id = News::where('hot',1)->where('id','!=',$request->id)->where('category_id',$request->category_id)->get();
            $count_news_hot_id = count($count_news_hot_id);
            if($request->hot == 1 && $count_news_hot_id == 4) {
                json_message('Tin hot mục con chỉ được tối đa 4 tin', 'error');
            }
        }

        if($request->id && $request->number_setup) {
            $get_new_id = News::find($request->id);
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
            ], $request, News::getAttributeName());
            $content = path_upload($request->video);
        } else if ($type == 3 && $flag == 0) {
            $this->validateRequest([
                'pictures' => "required|array|min:1",
                'pictures.*' => 'required|mimes:jpeg,bmp,png,gif,svg,pdf',
            ], $request, News::getAttributeName());
            if ($request->hasfile('pictures')) {
                foreach ($request->file('pictures') as $file) {
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
        // $content = mb_strtolower($request->input('description'));
        $description = html_entity_decode($request->input('description'),ENT_HTML5, "UTF-8");
        $model = News::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->content = $content;
        $model->description = $description;

        if ($request->image) {
            $sizes = config('image.sizes.news');
            $model->image = upload_image($sizes, $request->image);
        }

        $model->created_by = !$request->id ? Auth::id() : $model->created_by;
        $model->updated_by = Auth::id();
        $model->category_parent_id = $get_parent_id_cate->parent_id;
        $model->date_setup_icon = $date_setup_icon;

        if ($model->save()) {

            if($news_link_url){
                foreach ($news_link_url as $key => $item){
                    $news_link = NewsLink::firstOrNew(['id' => $link_id[$key]]);
                    $news_link->news_id = $model->id;
                    $news_link->title = isset($news_link_title[$key]) ? $news_link_title[$key] : null;
                    $news_link->link = $news_link_type[$key] == 'link' ? $item : path_upload($item);
                    $news_link->type = $news_link_type[$key];
                    $news_link->save();
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.news.edit',['id' => $model->id])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        // News::destroy($ids);
        foreach ($ids as $id){
            $new = News::find($id);
            $new->delete();
        }
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
                $model = News::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = News::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }
    // Xem trước nội dung
    public function previewNew($id) {
        News::updateItemViews($id);
        $item = News::findOrFail($id);
        $cate_new = NewsCategory::find($item->category_id);
        $user = User::getProfileById($item->created_by)->profile;
        $author = $user->lastname." ".$user->firstname;
        $next_post = News::where('id','>',$item->id)->where('category_id', '=', $item->category_id)->orderBy('id')->first();
        $prev_post = News::where('id','<',$item->id)->where('category_id', '=', $item->category_id)->orderBy('id','DESC')->first();
        $item->status = 0;
        $item->save();

        // if (url_mobile()){
        //     return view('themes.mobile.frontend.news.detail', [
        //         'item' => $item,
        //         'author' => $author,
        //         'categories' => $categories,
        //         'next_post' => $next_post,
        //         'prev_post' => $prev_post
        //     ]);
        // }

        return view('news::backend.news.previewNew', [
            'item' => $item,
            'author' => $author,
            'next_post' => $next_post,
            'prev_post' => $prev_post,
            'cate_new' => $cate_new
        ]);
    }

    public function saveObject($new_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable',
        ], $request);
        $get_parent_id_cate = News::find($request->category_id);
        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');
        $status_unit = $request->input('status_unit');
        $status_title = $request->input('status_title');

        if ($parent_id && is_null($unit_id)){
            if (NewsObject::checkObjectUnit($new_id, $parent_id)){

            }else{
                $model = new NewsObject();
                $model->new_id = $new_id;
                $model->unit_id = $parent_id;
                $model->status = 1;
                $model->new_category_parent_id = $get_parent_id_cate->category_parent_id;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }
        if ($unit_id) {
            foreach ($unit_id as $item){
                if (NewsObject::checkObjectUnit($new_id, $item)){
                    continue;
                }
                $model = new NewsObject();
                $model->new_id = $new_id;
                $model->unit_id = $item;
                $model->status = 1;
                $model->new_category_parent_id = $get_parent_id_cate->category_parent_id;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }else{
            foreach ($title_id as $item){
                if (NewsObject::checkObjectTitle($new_id, $item)){
                    continue;
                }
                $model = new NewsObject();
                $model->new_id = $new_id;
                $model->title_id = $item;
                $model->status = 1;
                $model->new_category_parent_id = $get_parent_id_cate->category_parent_id;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm chức danh thành công',
            ]);
        }
    }

    public function getUserObject($new_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = NewsObject::query();
        $query->select([
            'a.*',
            'b.code AS profile_code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name AS parent_name'
        ]);
        $query->from('el_news_object AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.new_id', '=', $new_id);
        $query->where('a.title_id', '=', null);
        $query->where('a.unit_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->profile_name = $row->lastname . ' ' . $row->firstname;

            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
            $row->status = 'Xem';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($new_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = NewsObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.name AS parent_name']);
        $query->from('el_news_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.new_id', '=', $new_id);
        $query->where('a.user_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }

            $row->status = 'Xem';

        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($new_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        NewsObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importObject($new_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProfileImport($new_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.libraries.video.edit', ['id' => $new_id]),
        ]);
    }

    public function removeItemNewLink(Request $request){
        $link_id = $request->link_id;

        NewsLink::where('id', $link_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
