<?php

namespace Modules\Forum\Http\Controllers;

use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use App\Scopes\CompanyScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Forum\Entities\Forum;
use Modules\Forum\Entities\FilterWord;
use Modules\Forum\Entities\ForumCategory;
use Modules\Forum\Entities\ForumThread;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumCategoryPermission;
use App\Profile;
use App\Permission;
use App\ForumsStatistic;
use Illuminate\Support\Facades\Auth;
use Modules\Forum\Entities\ForumUserLikeComment;
use App\ProfileView;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        ForumsStatistic::update_forums_insert_statistic();

        $search = $request->search; //hashtag

        $profile_view = ProfileView::find(Auth::id());
        ForumCategory::addGlobalScope(new CompanyScope());
        $forum_categories = ForumCategory::query()
            ->where('status', '=', 1);
        $forum_cate_permission_user = ForumCategoryPermission::get();
        $forum_categories = $forum_categories->get();
        $forum = function ($parent_id) {
            return Forum::getAllForumCategory($parent_id);
        };

        $profile = function ($user_id){
            return Profile::where('user_id', '=', $user_id)->first();
        };

        $forum_thread_count = function($forum_id){
            return ForumThread::CountCategory($forum_id);
        };

        if ($search){
            $forum_thread_search = ForumThread::query()
                ->from('el_forum_thread as a')
                ->leftJoin('el_forum as b', 'b.id', '=', 'a.forum_id')
                ->where('a.status', '=', 1)
                ->where('a.hashtag', 'like', '%'.$search.'%')
                ->orderBy('a.id', 'DESC');
            $forum_thread_search = $forum_thread_search->get();
        }
        $is_admin = Permission::isAdmin();
        $get_unit =  ProfileView::where('user_id', \Auth::id())->first();
        if (url_mobile()){
            return view('themes.mobile.frontend.forum.index', [
                'forum_categories' => $forum_categories,
            ]);
        }
        return view('forum::frontend.index', [
            'forum_categories' => $forum_categories,
            'forum' => $forum,
            'forum_thread_search' => $search ? $forum_thread_search : '',
            'profile' => $profile,
            'is_admin' => $is_admin,
            'get_unit' => $get_unit,
            'profile_view' => $profile_view
        ]);
    }

    public function forum($id, Request $request)
    {
        $search = $request->input('q');

        $forum = Forum::findOrFail($id);
        $sub_categories = function ($parent_id) {
            return Forum::getAllForumCategory($parent_id);
        };
        $is_admin = Permission::isAdmin();

        $forum_thread = ForumThread::where('forum_id',$id)->where('status',1);
        if ($search){
            $forum_thread->where('title', 'like', '%'.$search.'%');
        }
        $forum_thread = $forum_thread->paginate(10);

        $forum_thread_all = ForumThread::where('status', '=', 1)->orderBy('id', 'DESC')->limit(5)->get();
        $forum_thread_count = function($count_comment_id){
           return ForumComment::CountComment($count_comment_id);
        };
        $profile = function ($user_id){
            return Profile::where('user_id', '=', $user_id)->first();
        };

        if (url_mobile()){
            return view('themes.mobile.frontend.forum.topic', [
                'forum' => $forum,
                'forum_thread' => $forum_thread,
                'forum_threat_count' => $forum_thread_count,
            ]);
        }

        return view('forum::frontend.topic', [
            'forum' => $forum,
            'sub_categories' => $sub_categories,
            'forum_thread' => $forum_thread,
            'forum_threat_all' => $forum_thread_all,
            'forum_threat_count' =>$forum_thread_count,
            'profile'=>$profile,
            'is_admin' => $is_admin,
        ]);
    }

    public function thread($id)
    {
        ForumThread::updateItemViews($id);
        $forum_category = ForumThread::findOrFail($id);
        $sub_categories = function ($parent_id) {
            return Forum::getAllForumCategory($parent_id);
        };
        $is_admin = Permission::isAdmin();
        $sub_categories_all = Forum::find($forum_category->forum_id);
        $forum_thread = ForumThread::getThreatByCategory($sub_categories_all->id, $id);

        $forum_thread_all = ForumThread::where('status', '=', 1)->where('id', '!=', $id)->orderBy('id', 'DESC')->limit(5)->get();
        $comments = ForumComment::where('thread_id','=',$id)->paginate(9);
        $profile = function ($user_id){
            return Profile::where('user_id', '=', $user_id)->first();
        };

        if (url_mobile()){
            return view('themes.mobile.frontend.forum.thread', [
                'forum_category' => $forum_category,
            ]);
        }

        return view('forum::frontend.thread', [
            'forum_category' => $forum_category,
            'sub_categories_all'=>$sub_categories_all,
            'sub_categories' => $sub_categories,
            'forum_threat' => $forum_thread,
            'profile' => $profile,
            'forum_threat_all' => $forum_thread_all,
            'comments'=> $comments,
            'is_admin' => $is_admin,
        ]);
    }

    public function form($id){
        $sub_categories_all = Forum::findOrFail($id);

        if (url_mobile()){
            return view('themes.mobile.frontend.forum.form', [
                'sub_categories_all' => $sub_categories_all,
            ]);
        }

        return view('forum::frontend.form', [
            'sub_categories_all' => $sub_categories_all,
        ]);
    }

    public function saveTopic($id, Request $request){
        $this->validateRequest([
            'title'=>'required',
            'content' => 'required'
        ], $request, ForumThread::getAttributeName());

        if ($request->post('hastag')){
            $check_hastag = str_split($request->hashtag);
            if ($check_hastag[0] != '#'){
                return json_result([
                    'message' => 'Hashtag không đúng',
                    'status' => 'warning',
                ]);
            }
        }
        $check_comments = FilterWord::where('status',1)->pluck('name')->all();
        $content = mb_strtolower($request->input('content'));
        // dd($content);
        $content = html_entity_decode($content,ENT_HTML5, "UTF-8");
        foreach($check_comments as $check_comment){
            $text = strpos($content, $check_comment);
            if ($text > 0 ){
                return json_result([
                    'message' => 'Nội dung có từ phản cảm: ' . $check_comment . ', vui lòng liên hệ admin để biết thêm chi tiết',
                    'status' => 'warning',
                ]);
            }
        }

        $model = new ForumThread();
        $model->fill($request->all());
        $model->forum_id = $id;
        $model->hashtag = str_replace(' ', '', $request->hashtag);
        $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();
        $model->status = 2;
        if ($model->save()) {

            $forum = Forum::find($id);
            $forum->num_topic = ForumThread::where('forum_id', '=', $id)->count();
            $forum->save();

            return json_result([
                'message' => 'Gửi bài thành công',
                'status' => 'success',
                'redirect' => route('module.frontend.forums.topic', ['id' => $id]),
            ]);
        }

        return json_result([
            'message' => trans('lageneral.data_error '),
            'status' => 'error',
        ]);
    }

    public function thread_cmt(Request $request, $id)
    {
        $this->validateRequest([
            'comment'=>'required',
        ], $request, ForumComment::getAttributeName());

        $check_comments = FilterWord::where('status',1)->pluck('name')->all();
        $content = mb_strtolower($request->comment);
        $content = html_entity_decode($content,ENT_HTML5, "UTF-8");
        // dd($content);
        foreach($check_comments as $check_comment){
            $text = strpos($content, $check_comment);
            if ($text > 0 ){
                return json_result([
                    'message' => 'Nội dung có từ phản cảm : ' . $check_comment . ', vui lòng liên hệ admin để biết thêm chi tiết',
                    'status' => 'warning',
                ]);
            }
        }

        $cmt = new ForumComment();
        $cmt->thread_id = $id;
        $cmt->created_by = Auth::id();
        $cmt->comment = $request->comment;
        if ($cmt->save()) {

            $thread = ForumThread::find($id);
            $thread->total_comment = ForumComment::where('thread_id', '=', $id)->count();
            $thread->save();

            $forum = Forum::find($thread->forum_id);
            $forum->num_comment = $forum->getTotalComment();
            $forum->save();

            $post = ForumThread::find($id)->update(['updated_at'=> now()]);

            if (url_mobile()){
                json_result([
                    'message' => 'Bình luận thành công',
                    'status' => 'success',
                    'img_user' => Profile::avatar($cmt->created_by),
                    'name_user' => Profile::fullname($cmt->created_by),
                    'time_created' => \Carbon\Carbon::parse($cmt->created_at)->diffForHumans(),
                    'comment' => ucfirst($cmt->comment),
                    'comment_id' => $cmt->id,
                ]);
            }
            json_result([
                'message' => 'Bình luận thành công',
                'status' => 'success',
                'redirect' => route('module.frontend.forums.thread',['id'=>$id]),
            ]);
            /*return redirect()->route('module.frontend.forums.thread',['id'=>$id]);*/
        }
    }

    public function comment_delete(Request $request, $thread_id){
        $this->validateRequest([
            'id'=>'required'
        ], $request);
        $id = $request->id;
        $del = ForumComment::find($id);

        if($del->delete()){

            $thread = ForumThread::find($thread_id);
            $thread->total_comment = ForumComment::where('thread_id', '=', $id)->count();
            $thread->save();

            $forum = Forum::find($thread->forum_id);
            $forum->num_comment = $forum->getTotalComment();
            $forum->save();

            return 'ok';
        }

    }

    public function forum_delete(Request $request, $category_id)
    {
        $this->validateRequest([
            'id'=>'required'
        ], $request);
        $id = $request->id;
        $del = ForumThread::find($id);
        $del->comments()->delete();
        if($del->delete()){

            $forum = Forum::find($category_id);
            $forum->num_topic = ForumThread::where('forum_id', '=', $category_id)->count();
            $forum->save();

            return 'ok';
        }
    }

    public function edit($id){
        $thread = ForumThread::findOrFail($id);
        $forum = Forum::find($thread->forum_id);
        return view('forum::frontend.edit', [
            'thread' => $thread,
            'forum' => $forum,
        ]);
    }

    public function update($id, Request $request){
        $this->validateRequest([
            'title'=>'required',
            'content' => 'required'
        ], $request, [
            'title' => 'Tiêu đề bài viết',
            'content' => 'Nội dung bài viết'
        ]);

        if (strpos('#', $request->hashtag) == false){
            return json_result([
                'message' => 'Hashtag không đúng',
                'status' => 'warning',
            ]);
        }

        $model = ForumThread::findOrFail($id);
        $topic = $model->category->id;
        $model->fill($request->all());
        $model->forum_id = $topic;
        $model->hashtag = str_replace(' ', '', $request->hashtag);
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        $model->status = 1;
        if ($model->save()) {
            return redirect()->route('module.frontend.forums.topic',['id'=>$topic]);
        }
    }

    public function edit_cmt($id){
        $comment = ForumComment::findOrFail($id);
        $thread = ForumThread::find($comment->thread_id);
        $forum = Forum::find($thread->forum_id);

        return view('forum::frontend.comment', [
            'comment' => $comment,
            'thread' => $thread,
            'forum' => $forum,
        ]);

    }

    public function update_cmt($id, Request $request){
        $this->validateRequest([
            'comment' => 'required'
        ], $request, [
            'comment' => 'Nội dung bình luận'
        ]);
        $model = ForumComment::findOrFail($id);
        $thread = $model->thread->id;
        $model->fill($request->all());
        $model->created_by = Auth::id();
        if ($model->save()) {
            return redirect()->route('module.frontend.forums.thread',['id'=>$thread]);
        }
    }

    public function like_dislike_cmt($id, Request $request)
    {
        $type = $request->type;
        $comment_id = $request->comment_id;

        $user_like = ForumUserLikeComment::query()->firstOrNew(['thread_id' => $id, 'comment_id' => $comment_id, 'user_id' => \Auth::id()]);

        $user_like->comment_id = $comment_id;
        $user_like->thread_id = $id;
        $user_like->user_id = \Auth::id();
        if ($type == 'like'){
            $user_like->like = 1;
            $user_like->dislike = null;
        }elseif($type == 'dislike'){
            $user_like->like = null;
            $user_like->dislike = 1;
        }else{
            $user_like->like = null;
            $user_like->dislike = null;
        }
        $user_like->save();

        $count_like_comment = ForumUserLikeComment::query()
            ->where('comment_id', '=', $comment_id)
            ->where('thread_id', '=', $id)
            ->where('like', '=', 1)
            ->count();

        $count_dislike_comment = ForumUserLikeComment::query()
            ->where('comment_id', '=', $comment_id)
            ->where('thread_id', '=', $id)
            ->where('dislike', '=', 1)
            ->count();

        json_result(['comment_id' => $comment_id, 'count_like_comment' => $count_like_comment, 'count_dislike_comment' => $count_dislike_comment]);
    }
}
