<?php

namespace Modules\DailyTraining\Http\Controllers\Frontend;

use App\Profile;
use App\Scopes\CompanyScope;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Modules\DailyTraining\Entities\DailyTrainingCategory;
use Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserLikeCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserLikeVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserViewVideo;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Carbon\Carbon;

class DailyTrainingVideoController extends Controller
{
    protected $success_response = 'OK';
    protected $errors;

    public function __construct() {
        //parent::__construct();
        $this->errors = [];
    }

    public function dailyCate($id){
        $categories = DailyTrainingCategory::get();
        if (url_mobile()){
            return view('themes.mobile.frontend.daily_training.index',[
                'categories' => $categories,
                'lay' => 'video',
                'id' => $id,
            ]);
        }
    }

    public function index(Request $request) {
        DailyTrainingVideo::addGlobalScope(new CompanyScope());
        DailyTrainingCategory::addGlobalScope(new CompanyScope());

        $categories = DailyTrainingCategory::get();

        if (url_mobile()){
            $categories_item = '';

            $query = DailyTrainingVideo::query();
            $query->where('status',1);
            $query->orderByDesc('created_at');
            $query->take(4);
            if($request->cate_id) {
                $categories_item = DailyTrainingCategory::where('id',$request->cate_id)->get();
                $query->where('category_id',$request->cate_id);
            }
            $get_daily_training_video_news = $query->get();
            return view('themes.mobile.frontend.daily_training.index2',[
                'categories' => $categories,
                'lay' => 'video',
                'get_daily_training_video_news' => $get_daily_training_video_news,
                'cate_id' => $request->cate_id,
                'categories_item' => $categories_item
            ]);
        }

        $search = $request->get('search');
        $category = $request->get('category');
        $query = DailyTrainingVideo::query();
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('hashtask', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        if($category) {
            $query->where('category_id',$category);
        }
        $query->where('status', '=', 1);
        $query->where('approve', '=', 1);
        $query->orderByDesc('id');

        $set_paginate = 0;
        if($search || $category) {
            $videos = $query->get();
            $set_paginate = 1;
        } else {
            $videos = $query->paginate(8);
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($videos);
            return $data;
        }
        $type = 0;
        return view('dailytraining::frontend.index', [
            'categories' => $categories,
            'videos' => $videos,
            'set_paginate' => $set_paginate,
            'type' => $type,
        ]);
    }

    public function addVideo()
    {
        $mimetypes = ['.mp4'];
        $max_file_size = 50;

        $categories = DailyTrainingCategory::get();
        if (url_mobile()){
            return view('themes.mobile.frontend.daily_training.add_video',[
                'categories' => $categories,
                'mimetypes' => $mimetypes,
                'max_file_size' => $max_file_size,
            ]);
        }
        
        return view('dailytraining::frontend.add_video', [
            'categories' => $categories,
            'mimetypes' => $mimetypes,
            'max_file_size' => $max_file_size,
        ]);
    }

    public function saveVideo(Request $request)
    {
        $this->validateRequest([
            'name' => 'required',
            'hashtask'=>'required',
            'video' => 'required',
        ], $request, DailyTrainingVideo::getAttributeName());

        $cate_id = $request->category_id ? $request->category_id : 1;
        $category = DailyTrainingCategory::find($cate_id);

        $model = new DailyTrainingVideo();
        $model->fill($request->all());
        $model->hashtask = '#'.str_replace(' ', '', $request->hashtask);
        $model->category_id = $category->id;
        $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();
        $model->approve = 2;
        $model->video = $request->video;

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => config('app.ffmpeg.binaries'),
            'ffprobe.binaries' => config('app.ffprobe.binaries'),
            'timeout'          => 3600,
            'ffmpeg.threads'   => 12,
        ]);
        $path_img = 'video_'.time().'.png';

        $storage = \Storage::disk('upload');
        $uploadPath = $storage->path($request->video);

        $ffmpeg->open($uploadPath)
            ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(15))
            ->save($storage->path(date('Y/m/d').'/'.$path_img));

        $model->avatar = date('Y/m/d').'/'.$path_img;
        $model->status = 1;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Thành công. Xin chờ duyệt',
            'redirect' => route('module.daily_training.frontend'),
        ]);
    }

    public function disableVideo(Request $request){
        $video = DailyTrainingVideo::find($request->id);
        $video->status = 0;
        $video->save();

        json_message('Xoá thành công');
    }

    public function search(Request $request){
        DailyTrainingVideo::addGlobalScope(new CompanyScope());

        $search = $request->get('q');
        $query = DailyTrainingVideo::query();
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('hashtask', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        $query->where('status', '=', 1);
        $query->where('approve', '=', 1);

        $items = $query->paginate(10);
        $items->appends($request->query());

        return view('themes.mobile.frontend.daily_training.search',[
            'items' => $items,
        ]);
    }

    public function detailVideo($id, Request $request){
        $agent = new Agent();
        DailyTrainingVideo::updateView($id);
        $video = DailyTrainingVideo::find($id);

        $user_view = new DailyTrainingUserViewVideo();
        $user_view->video_id = $id;
        $user_view->user_id = \Auth::id();
        $user_view->time_view = date('Y-m-d H:i:s');
        $user_view->device = $agent->deviceType();
        $user_view->save();

        $count_like = DailyTrainingUserLikeVideo::query()
            ->where('video_id', '=', $id)
            ->where('like', '=', 1)
            ->count();

        $count_dislike = DailyTrainingUserLikeVideo::query()
            ->where('video_id', '=', $id)
            ->where('dislike', '=', 1)
            ->count();

        $comments = DailyTrainingUserCommentVideo::where('video_id', '=', $id)->get();

        if (url_mobile()){
            return view('themes.mobile.frontend.daily_training.detail',[
                'video' => $video,
                'count_like' => $count_like,
                'count_dislike' => $count_dislike,
                'comments' => $comments
            ]);
        }

        return view('dailytraining::frontend.detail', [
            'video' => $video,
            'count_like' => $count_like,
            'count_dislike' => $count_dislike,
            'comments' => $comments
        ]);
    }

    public function likeVideo($id, Request $request)
    {
        $type = $request->type;

        $user_like = DailyTrainingUserLikeVideo::query()->firstOrNew(['video_id' => $id, 'user_id' => \Auth::id()]);
        $user_like->video_id = $id;
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

        $count_like = DailyTrainingUserLikeVideo::query()
            ->where('video_id', '=', $id)
            ->where('like', '=', 1)
            ->count();

        $count_dislike = DailyTrainingUserLikeVideo::query()
            ->where('video_id', '=', $id)
            ->where('dislike', '=', 1)
            ->count();

        json_result(['count_like' => $count_like, 'count_dislike' => $count_dislike]);
    }

    public function commentVideo($id, Request $request){
        $this->validateRequest([
            'content' => 'required',
        ], $request, ['content' => 'Nội dung']);

        $content = strtolower($request->post('content'));

        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            return json_result([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }
        $user_comment = new DailyTrainingUserCommentVideo();
        $user_comment->video_id = $id;
        $user_comment->user_id = \Auth::id();
        $user_comment->content = $request->post('content');
        $user_comment->save();

        if (url_mobile()){
            json_result([
                'message' => 'Bình luận thành công',
                'status' => 'success',
                'img_user' => Profile::avatar($user_comment->user_id),
                'name_user' => Profile::fullname($user_comment->user_id),
                'time_created' => \Carbon\Carbon::parse($user_comment->created_at)->diffForHumans(),
                'content' => ucfirst($user_comment->content),
                'comment_id' => $user_comment->id,
            ]);
        }

        json_result([
            'message' => 'Bình luận thành công',
            'status' => 'success',
            'img_user' => Profile::avatar($user_comment->user_id),
            'name_user' => Profile::fullname($user_comment->user_id),
            'time_created' => \Carbon\Carbon::parse($user_comment->created_at)->diffForHumans(),
            'content' => ucfirst($user_comment->content),
            'comment_id' => $user_comment->id,
        ]);
    }

    public function likeCommentVideo($id, Request $request)
    {
        $type = $request->type;
        $comment_id = $request->comment_id;

        $user_like = DailyTrainingUserLikeCommentVideo::query()->firstOrNew(['video_id' => $id, 'comment_id' => $comment_id, 'user_id' => \Auth::id()]);

        $user_like->comment_id = $comment_id;
        $user_like->video_id = $id;
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

        $count_like_comment = DailyTrainingUserLikeCommentVideo::query()
            ->where('comment_id', '=', $comment_id)
            ->where('video_id', '=', $id)
            ->where('like', '=', 1)
            ->count();

        $count_dislike_comment = DailyTrainingUserLikeCommentVideo::query()
            ->where('comment_id', '=', $comment_id)
            ->where('video_id', '=', $id)
            ->where('dislike', '=', 1)
            ->count();

        json_result(['comment_id' => $comment_id, 'count_like_comment' => $count_like_comment, 'count_dislike_comment' => $count_dislike_comment]);
    }

    public function upload(Request $request)
    {
        $error_bag = [];

        try {

            $receiver = new FileReceiver('upload', $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();
            if ($save->isFinished()) {
                $save_file = $this->saveFile($save->getFile());
                if ($save_file) {
                    return response()->json(['path' => $save_file]);
                }
                return $this->response($this->errors);
            }

            $handler = $save->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            array_push($error_bag, $e->getMessage());
            return $this->response($error_bag);
        }
    }

    protected function saveFile(UploadedFile $file) {
        $filename = $this->createFilename($file);
        $storage = \Storage::disk('upload');
        $new_path = $storage->putFileAs(date('Y/m/d'), $file, $filename);

        if (!$this->fileIsValid($storage->path($new_path))) {
            unlink($storage->path($new_path));
            return false;
        }

        return $new_path;
    }

    protected function createFilename(UploadedFile $file) {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. Str::random(10) .'.' . $extension;
        return $new_filename;
    }

    protected function fileIsValid($file_path)
    {
        $file = new UploadedFile($file_path, basename($file_path));

        if (empty($file)) {
            array_push($this->errors, $this->error('file-empty'));
            return false;
        }

        if (! $file instanceof UploadedFile) {
            array_push($this->errors, $this->error('instance'));
            return false;
        }

        if ($file->getError() != UPLOAD_ERR_OK) {
            $msg = 'File failed to upload. Error code: ' . $file->getError();
            array_push($this->errors, $msg);
            return false;
        }

        // Bytes to MB
        $max_size = 2048;
        $file_size = $file->getSize();

        if ($max_size > 0) {
            if ($file_size > ($max_size * 1024 * 1024)) {
                array_push($this->errors, $this->error('size'));
                return false;
            }
        }

        return true;
    }

    protected function response($error_bag) {
        $response = count($error_bag) > 0 ? $error_bag : $this->success_response;
        return response()->json($response);
    }

    protected function error($error_type, $variables = [])
    {
        return trans('lfm.error-' . $error_type, $variables);
    }

    public function loadData($items) {
        $data = '';
        foreach ($items as $video) {
            $data.='<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">';
            $data.='    <div class="row">';
            $data.='        <div class="col-12">';
            $data.='            <a href="'. route("module.daily_training.frontend.detail_video", ["id" => $video->id]) .'">';
            $data.='                <img src="'. image_file($video->avatar) .'" alt="" class="w-100" style="height: 250px;">';
            $data.='            </a>';
            $data.='        </div>';
            $data.='    </div>';
            $data.='    <div class="row mx-0 mb-4 mt-1">';
            $data.='        <div class="col-3 avatar_account_daily p-1">';
            $data.='            <img src="'. \App\Profile::avatar($video->created_by) .'" alt="" class="ml-0 w-100">';
            $data.='        </div>';
            $data.='        <div class="'. (\Auth::id() == $video->created_by ? "col-7" : "col-8") .' pl-1 pr-0">';
            $data.='            <a href="'. route('module.daily_training.frontend.detail_video', ['id' => $video->id]) .'" class="crse14s link_daily_training">';
            $data.='            <span class="daily_name_training">'. $video->name .'</span>';
            $data.='                <div class="show_daily_name_training">';
            $data.='                    '. $video->name .'';
            $data.='                </div>';
            $data.='            </a>';
            $data.='            <p class="text-mute small mb-1">'. Profile::fullname($video->created_by) .' - '. $video->view .' '. trans('app.view') .'</p>';
            $data.='            <p class="text-mute small mb-1">'. Carbon::parse($video->created_at)->diffForHumans() .'</p>';
            $data.='        </div>';
            if(\Auth::id() == $video->created_by) {
                $data.='    <div class="col-2 p-0">';
                $data.='        <span class="text-danger pr-2">';
                $data.='            <img src="'. asset('themes/mobile/img/heart.png') .'" alt="" style="width: 15px; height: 15px;">';
                $data.='        </span>';
                $data.='         <div class="eps_dots more_dropdown">';
                $data.='            <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>';
                $data.='            <div class="dropdown-content">';
                $data.='                <span class="disable-video text-danger" onclick=deleteVideo('. $video->id .') data-video_id="'. $video->id .'">';
                $data.='                    <i class="uil uil-ban"></i>'. trans("app.delete") .'';
                $data.='                 </span>';
                $data.='            </div>';
                $data.='        </div>';
                $data.='    </div>';
            } else {
                $data.='    <div class="col-1 p-0">';
                $data.='        <div class="float-right">';
                $data.='            <span class="text-danger pr-2">';
                $data.='                <img src="'. asset('themes/mobile/img/heart.png') .'" alt="" style="width: 15px; height: 15px;">';
                $data.='            </span>';
                $data.='        </div>';
                $data.='    </div>';
            }
            $data.='    </div>';
            $data.='</div>';
        }
        return $data;
    }

    public function myVideo(Request $request) {
        $categories = DailyTrainingCategory::get();
        $search = $request->get('search');
        $category = $request->get('category');
        $query = DailyTrainingVideo::query();
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('hashtask', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        if($category) {
            $query->where('category_id',$category);
        }
        $query->where('status', '=', 1);
        $query->where('created_by', '=', \Auth::id());
        $query->where('approve', '=', 1);
        $query->orderByDesc('id');

        $set_paginate = 0;
        if($search || $category) {
            $videos = $query->get();
            $set_paginate = 1;
        } else {
            $videos = $query->paginate(8);
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($videos);
            return $data;
        }
        $type = 1;
        return view('dailytraining::frontend.index', [
            'categories' => $categories,
            'videos' => $videos,
            'set_paginate' => $set_paginate,
            'type' => $type,
        ]);
    }
}
