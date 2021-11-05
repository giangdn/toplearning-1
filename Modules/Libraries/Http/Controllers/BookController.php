<?php

namespace Modules\Libraries\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Profile;
use App\ProfileView;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesCategory;
use Illuminate\Support\Facades\Auth;
use Modules\Libraries\Entities\RegisterBook;
use App\Exports\RegisterBookExport;
use App\Exports\ExportLibraries;
use App\Notifications;
use Modules\AppNotification\Helpers\AppNotification;
use Illuminate\Support\Str;
use App\Permission;
use Modules\Notify\Entities\Notify;
use App\Models\Categories\Unit;
use Carbon\Carbon;
use App\LibrariesStatistic;
use App\Models\Categories\Titles;
use Modules\Libraries\Entities\LibrariesObject;
use Modules\Libraries\Imports\ProfileImport;
use App\Automail;

class BookController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();
        $categories = LibrariesCategory::where('type', '=', 1)->get();
        return view('libraries::backend.libraries.book.index', [
            'categories' => $categories,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
            'notifications' => $notifications
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);
        Libraries::addGlobalScope(new DraftScope());
        $query = Libraries::query();
        $query->select(['el_libraries.*', 'b.name AS category_name']);
        $query->leftJoin('el_libraries_category AS b', 'b.id', '=', 'el_libraries.category_id' );
        $query->where('el_libraries.type', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('el_libraries.name','like','%' . $search . '%');
                $sub_query->orWhere('el_libraries.name_author','like','%' . $search . '%');
            });
        }
        if ($category_id){
            $query->where('el_libraries.category_id', '=', $category_id);
        }

        $count = $query ->count();
        $query -> orderBy('el_libraries.'.$sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $profile = Profile::where('user_id', '=', $row->updated_by)->first();
            $row->user_name = $profile ? $profile->lastname . ' ' . $profile->firstname : '';
            $row->edit_url = route('module.libraries.book.edit', ['id' => $row->id]);
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        $errors = session()->get('errors');
        \Session::forget('errors');
        $categories = LibrariesCategory::where('type','=',1)->get();

        if ($id) {
            $model = Libraries::find($id);
            $page_title = $model->name;
            $titles = Titles::where('status', '=', 1)->get();
            return view('libraries::backend.libraries.book.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'titles' => $titles,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[5],
            ]);
        }

        $model = new Libraries();
        $page_title = trans('backend.add_new');

        return view('libraries::backend.libraries.book.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            // 'phone_contact' => ['required','regex:/(09|03|07|08|05)+([0-9]{8})/','max:11'],
            'phone_contact' => 'required',
            'description' => 'required',
            'status'=>'required',
            'name_author'=>'required',
            'image' => 'nullable|string',
            'category_id' => 'nullable|exists:el_libraries_category,id',
        ], $request, Libraries::getAttributeName());

        $get_parents_cate_id = LibrariesCategory::getTreeParentUnit($request->category_id);
        foreach($get_parents_cate_id as $get_parent_cate_id) {
            $cate_parent[] =  $get_parent_cate_id->name;
        }
        // dd(implode(',',$cate_parent_id));
        $model = Libraries::firstOrNew(['id' => $request->id,'type'=>$request->type]);
        $model->fill($request->all());

        $sizes = config('image.sizes.library');
        $model->image = upload_image($sizes, $request->image);

        $model->phone_contact = $request->phone_contact;
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        $model->category_parent = implode(',',$cate_parent);
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.libraries.book')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        // Libraries::destroy($ids);
        foreach ($ids as $id){
            $libraries = Libraries::find($id);
            $libraries->delete();
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetGroupName(Request $request){
        $this->validateRequest([
            'category_id' => 'required|exists:el_libraries_category,id',
        ], $request, [
            'category_id' => 'Danh mục book',
        ]);
        $category_id = $request->category_id;

        $category_id = LibrariesCategory::find($category_id);

        json_result($category_id);
    }

    public function register(){
        $models = RegisterBook::query()
        ->select([
            'a.*',
            'b.name as book_name',
            'c.lastname',
            'c.firstname',
            'c.code as user_code'
        ])
        ->from('el_register_book as a')
        ->leftjoin('el_libraries as b','b.id','=','a.book_id')
        ->leftjoin('el_profile as c','c.user_id','=','a.user_id')
        ->where('a.status','=',2)
        ->where('a.approved',1)->get();
        if ( !empty($models) && count($models) > 0 ) {
            foreach ($models as $key => $model) {
                $get_pay_date = Carbon::parse($model->pay_date)->format('Y-m-d');
                $time = strtotime($get_pay_date);
                $date_time = date('Y-m-d');
                $before_pay_date = date("Y-m-d", strtotime("-1 day", $time));
                if ($date_time > $get_pay_date) {
                    $unit_id = [];
                    $unit = Unit::getTreeParentUnit(Profile::getUnitCode());
                    foreach ($unit as $item){
                        $unit_id[] = $item->id;
                    }
                    $query = \DB::query()
                        ->from('el_user_permission_type as a')
                        ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
                        ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
                        ->where(function ($sub) use ($unit_id){
                            $sub->orWhere(function ($sub1) use ($unit_id){
                                $sub1->where('b.type', '=', 'group-child')
                                    ->whereIn('b.unit_id', $unit_id);
                            });
                            $sub->orWhere(function ($sub2){
                                $sub2->where('b.type', '=', 'owner')
                                    ->where('b.unit_id', '=', Profile::getUnitId());
                            });
                        })
                        ->whereIn('c.name', function ($sub2){
                            $sub2->select(['per.parent'])
                                ->from('el_model_has_permissions as model')
                                ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                                ->whereColumn('model.model_id', '=', 'a.user_id')
                                ->where('per.name', '=', 'libraries-book-register-approve')
                                ->orWhere('per.name', '=', 'libraries-book-register');
                        })
                        ->where('c.name', '=', 'user')
                        ->pluck('a.user_id')->toArray();

                    $user_managers = $query;
                    if (count($user_managers) > 0){
                        foreach ($user_managers as $user) {
                            $model = new Notify();
                            $model->user_id = $user;
                            $model->subject = 'Qúa hạn mượn sách';
                            $model->content = 'Sách '.$model->book_name.' được mượn bởi '.$model->lastname . $model->firstname. '-' .$model->user_code.' đã quá hạn mượn vào ngày '. $model->pay_date;
                            $model->url = '';
                            $model->created_by = 0;
                            $model->save();

                            $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
                            $redirect_url = route('module.notify.view', [
                                'id' => $model->id,
                                'type' => 1
                            ]);

                            $notification = new AppNotification();
                            $notification->setTitle($model->subject);
                            $notification->setMessage($content);
                            $notification->setUrl($redirect_url);
                            $notification->add($user);
                        }
                        $notification->save();
                    }
                }
            }
        }

        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        return view('libraries::backend.libraries.book.register',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[6],
        ]);
    }

    public function getDataRegister(Request $request)
    {
        $search = $request -> input('search');
        $status = $request->input('status');
        $borrow_date = $request->input('borrow_date');
        $pay_date = $request->input('pay_date');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        RegisterBook::addGlobalScope(new DraftScope());
        $query = RegisterBook::query();
        $query->select([
            'el_register_book.*',
            'b.name AS book_name',
            'b.current_number',
            'c.lastname',
            'c.firstname',
            'd.name AS unit_name',
            'e.name AS title_name',
            'f.name AS unit_manager',
        ]);
        $query->leftJoin('el_libraries AS b', 'b.id', '=', 'el_register_book.book_id' );
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'el_register_book.user_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.unit_code');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'd.parent_code');
        $query->leftJoin('el_titles AS e', 'e.code', '=', 'c.title_code');
        $query->where('b.type', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('b.name','like','%' . $search . '%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $query->where('el_register_book.status', '=', $status)->where('el_register_book.approved', '=', 1);
        }

        if ($borrow_date) {
            $query->where('el_register_book.borrow_date', '>=', date_convert($borrow_date));
        }

        if ($pay_date) {
            $query->where('el_register_book.borrow_date', '<=', date_convert($pay_date, '23:59:59'));
        }

        $count = $query ->count();
        $query -> orderBy('el_register_book.'.$sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();

        foreach ($rows as $row) {
            $row->full_name = $row->lastname . ' ' . $row->firstname;
            $row->borrow_date = $row->borrow_date ? get_date($row->borrow_date, 'd/m/Y') : '-';
            $row->pay_date = $row->pay_date ? get_date($row->pay_date, 'd/m/Y') : '-';
            $row->user_return_book = $row->user_return_book ? get_date($row->user_return_book, 'd/m/Y') : '-';
            $row->register_date = get_date($row->register_date, 'd/m/Y');
            if ($row->approved == 2) {
                $row->status = '<span class="text-warning"> '.trans("backend.not_approved").' </span>';
            } elseif ($row->approved == 1) {
                if ($row->status == 1) {
                    $row->status = '<span class="text-primary">Chưa lấy sách</span>';
                }elseif ($row->status == 2){
                    $row->status = '<span class="text-info">Đang mượn sách</span>';
                }else{
                    $row->status = '<span class="text-black">Đã trả sách</span>';
                }
            } else{
                $row->status = '<span class="text-danger">'.trans("backend.deny").'</span>';
            }

        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function status(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:2,3'
        ], $request, [
            'ids' => 'Mượn sách',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status');
        foreach($ids as $id){
            $model = RegisterBook::findOrFail($id);
            if ($model->approved == 1){
                if ($model->status == 1 && $status == 3) {
                    json_result([
                        'status' => 'error',
                        'message' => 'Chưa lấy sách',
                    ]);
                }
                if ($status == 2 || $model->status == 3){
                    $borrow_date = Carbon::now();
                    $pay_date = Carbon::now()->addDays(7);
                    $model->borrow_date = $borrow_date;
                    $model->pay_date = $pay_date;
                    $model->save();
                }
                if ($status == 3 && $model->status != 3){
                    $user_return_book = Carbon::now();
                    $model->user_return_book = $user_return_book;
                    $model->save();
                    $book = Libraries::find($model->book_id);
                    $book->current_number = $book->current_number + $model->quantity;
                    $book->save();
                }
                $model->status = $status;
                $model->save();
            }else{
                json_result([
                    'status' => 'error',
                    'message' => 'Chưa được duyệt',
                ]);
            }
        }

        json_result([
            'status' => 'success',
            'message' =>trans('lageneral.successful_save'),
        ]);
    }

    public function approve(Request $request) {
        $ids = $request->input('ids', null);
        $approved = $request->input('status', null);

        foreach ($ids as $id) {
            $model = RegisterBook::findOrFail($id);
            if ($model && $model->status > 1 || ($model->approved == 1 && $approved == 1)){
                continue;
            }
            $model->approved = $approved;
            $model->status = 1;
            $book = Libraries::find($model->book_id);

            // if ($approved == 1 && $model->status == 1){
                // if ($book->current_number < 0 || $book->current_number < $model->quantily){
                //     $model->approved = 2;
                //     json_message('Số lượng sách '. $book->name .' không đủ cho mượn', 'error');
                // }
                // $book->current_number = $book->current_number - $model->quantily;
                // $book->save();
            // }
            if ($approved == 0 && $model->status == 1){
                $book->current_number = $book->current_number + $model->quantity;
                $book->save();
            }
            $save = $model->save();
            if ($save) {
                $getUserIdRegister = $model->user_id;
                $query = new Notify();
                $query->user_id = $getUserIdRegister;
                $query->subject = 'Duyệt đăng ký mượn sách';
                $query->content = 'Đăng ký mượn sách '. $book->name .' đã được duyệt. Vui lòng liên hệ người quản lý sách';
                $query->url = '';
                $query->created_by = 0;
                $query->save();

                $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $query->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($query->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add($getUserIdRegister);
                $notification->save();

                $profile = ProfileView::where('user_id',$getUserIdRegister)->first();
                $automail = new Automail();
                $automail->template_code = 'approve_book_register';
                $automail->params = [
                    'gender' => $profile->gender=='1'?'Anh':'Chị',
                    'full_name' => $profile->full_name,
                    'book_name' => $book->name,
                ];
                $automail->users = [$getUserIdRegister];
                $automail->check_exists = true;
                $automail->object_id = $book->id;
                $automail->check_exists_status = 0;
                $automail->object_type = 'approve_book_register';
                $automail->addToAutomail();
            }
        }

        if($approved == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function removeRegister(Request $request) {
        $ids = $request->input('ids', null);
        RegisterBook::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function registerExport()
    {
        return (new RegisterBookExport())->download('danh_sach_muon_sach_'. date('d_m_Y') .'.xlsx');
    }

    public function export()
    {
        return (new ExportLibraries(1))->download('danh_sach_sach_'. date('d_m_Y') .'.xlsx');
    }

    public function saveObject($libraries_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable',
        ], $request);

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');
        $status_unit = $request->input('status_unit');
        $status_title = $request->input('status_title');

        if ($parent_id && is_null($unit_id)){
            if (LibrariesObject::checkObjectUnit($libraries_id, $parent_id, 4)){

            }else{
                $model = new LibrariesObject();
                $model->libraries_id = $libraries_id;
                $model->unit_id = $parent_id;
                $model->type = 1;
                $model->status = 1;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }
        if ($unit_id) {
            foreach ($unit_id as $item){
                $model = LibrariesObject::firstOrNew(['libraries_id' => $libraries_id, 'unit_id' => $item]);
                $model->libraries_id = $libraries_id;
                $model->unit_id = $item;
                $model->type = 1;
                $model->status = 1;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm đơn vị thành công',
            ]);
        }else{
            foreach ($title_id as $item){
                $model = LibrariesObject::firstOrNew(['libraries_id' => $libraries_id, 'title_id' => $item]);
                $model->libraries_id = $libraries_id;
                $model->title_id = $item;
                $model->type = 1;
                $model->status = 1;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm chức danh thành công',
            ]);
        }
    }

    public function getUserObject($libraries_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = LibrariesObject::query();
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
        $query->from('el_libraries_object AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.libraries_id', '=', $libraries_id);
        $query->where('a.type', '=', 1);
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

    public function getObject($libraries_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = LibrariesObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.name AS parent_name']);
        $query->from('el_libraries_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.libraries_id', '=', $libraries_id);
        $query->where('a.type', '=', 1);
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

    public function removeObject($libraries_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        LibrariesObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importObject($libraries_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProfileImport($libraries_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.libraries.video.edit', ['id' => $libraries_id]),
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Sách giấy',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Libraries::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Libraries::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }
}
