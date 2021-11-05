<?php

namespace Modules\Forum\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Forum\Entities\ForumCategory;
use Modules\Forum\Entities\Forum;
use Modules\Forum\Entities\ForumThread;
use Illuminate\Support\Facades\Auth;


class CreateNewsController extends Controller
{
    public function index($cate_id, $forum_id, $id = 0)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $cate = ForumCategory::find($cate_id);
        $forum = Forum::where('id', '=', $forum_id)->where('category_id', '=', $cate_id)->first();

        if ($id) {
            $model = ForumThread::find($id);
            $page_title = $model->title;
            return view('forum::backend.forum_thread.form', [
                'model' => $model,
                'forum' => $forum,
                'page_title' => $page_title,
                'cate' => $cate,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new ForumThread();
        $page_title = trans('backend.add_new');

        return view('forum::backend.forum_thread.form', [
            'model' => $model,
            'forum' => $forum,
            'page_title' => $page_title,
            'cate' => $cate,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save($cate_id, $forum_id, Request $request) {
        $this->validateRequest([
            'title' => 'required',
            'content' => 'required',
            'forum_id' => 'required',
        ], $request, ForumThread::getAttributeName());

        $model = ForumThread::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.forum.thread',[
                    'cate_id' => $cate_id,
                    'forum_id' => $forum_id
                ])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        ForumThread::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

}
