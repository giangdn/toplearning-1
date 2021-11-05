<?php

namespace Modules\DailyTraining\Http\Controllers\Backend;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\DailyTraining\Entities\DailyTrainingCategory;
use Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserViewVideo;
use Modules\DailyTraining\Entities\DailyTrainingVideo;

class DailyTrainingVideoController extends Controller
{
    public function index($cate_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('dailytraining::backend.video.index', [
            'cate_id' => $cate_id,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData($cate_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'view');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = DailyTrainingVideo::query()
            ->where('category_id', '=', $cate_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('view', $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $profile = Profile::find($row->created_by);
            $title_name = @$profile->titles->name;

            $user_apporve = Profile::find($row->user_approve);

            $row->video = $row->getLinkPlay();
            $row->created_by = $profile->lastname .' '. $profile->firstname .' ('. $profile->code .') <br>'. $title_name;
            $row->created_time = get_date($row->created_at, 'H:i d/m/Y');

            $row->user_approve = $user_apporve ? ($user_apporve->lastname .' '. $user_apporve->firstname .' ('. $user_apporve->code .')') : '';
            $row->time_approve = get_date($row->time_approve, 'H:i d/m/Y');

            $row->view_comment = route('module.daily_training.video.view_comment', ['cate_id' => $cate_id, 'video_id' => $row->id]);
            $row->view_report = route('module.daily_training.video.view_report', ['cate_id' => $cate_id, 'video_id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $cate = DailyTrainingVideo::find($id);

            DailyTrainingUserCommentVideo::query()->where('video_id', '=', $id)->delete();

            $cate->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function approve(Request $request) {
        $ids = $request->input('ids', null);
        $approve = $request->input('status');

        foreach ($ids as $id){
            $video = DailyTrainingVideo::find($id);
            $video->approve = $approve;
            $video->user_approve = \Auth::id();
            $video->time_approve = date('Y-m-d H:i:s');
            $video->save();
        }

        json_message('Trạng thái đã thay đổi');
    }

    public function viewComment($cate_id, $video_id, Request $request){
        $comments = DailyTrainingUserCommentVideo::where('video_id', '=', $video_id)->orderBy('created_at', 'DESC')->get();
        $video = DailyTrainingVideo::find($video_id);

        return view('dailytraining::backend.video.comment', [
            'comments' => $comments,
            'video' => $video,
            'cate_id' => $cate_id
        ]);
    }

    public function checkFailedComment($cate_id, $video_id, Request $request){
        $comment = DailyTrainingUserCommentVideo::where('id', '=', $request->comment_id)
            ->where('video_id', '=', $video_id)->first();
        $comment->failed = ($comment->failed == 0 ? 1 : 0);
        $comment->save();

        json_message('Đánh dấu xong');
    }

    public function viewReport($cate_id, $video_id, Request $request){
        $video = DailyTrainingVideo::find($video_id);

        return view('dailytraining::backend.video.report', [
            'video' => $video,
            'cate_id' => $cate_id
        ]);
    }

    public function getDataReport($cate_id, $video_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = DailyTrainingUserViewVideo::query()
            ->select([
                'user_view.*',
                'profile.dob',
                'title.name as title_name',
                'unit.name as unit_name',
                'unit_manager.name as unit_manager',
            ])
            ->from('el_daily_training_user_view_video as user_view')
            ->leftJoin('el_profile as profile', 'profile.user_id', '=', 'user_view.user_id')
            ->leftJoin('el_titles as title', 'title.code', 'profile.title_code')
            ->leftJoin('el_unit as unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_unit as unit_manager', 'unit_manager', '=', 'unit.parent_code')
            ->where('user_view.video_id', '=', $video_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('profile.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.firstname', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->fullname = Profile::fullname($row->user_id);
            $row->time_view = get_date($row->time_view, 'H:i d/m/Y');
            $row->dob = get_date($row->dob, 'd/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
