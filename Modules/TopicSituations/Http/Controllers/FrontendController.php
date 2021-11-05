<?php

namespace Modules\TopicSituations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\TopicSituations\Entities\Topic;
use Modules\TopicSituations\Entities\Situation;
use Modules\TopicSituations\Entities\CommentSituation;
use Modules\TopicSituations\Entities\ReplyCommentSituation;
use Modules\TopicSituations\Entities\LikeSituation;
use Modules\TopicSituations\Entities\LikeCommentSituation;
use Carbon\Carbon;
use App\Scopes\CompanyScope;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $fromdate = $request->start_date;
        $todate = $request->end_date;
        Topic::addGlobalScope(new CompanyScope());
        $query = Topic::query();
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
                $sub_query->orWhere('code','like','%' . $search . '%');
            });
        }
        if ($fromdate) {
            $query->where('created_at', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('created_at', '<=', date_convert($todate, '23:59:59'));
        }
        $query->where('isopen',1);

        $set_paginate = 0;
        if($fromdate || $search || $todate) {
            $topics = $query->get();
            $set_paginate = 1;
        } else {
            $topics = $query->paginate(8);
        }

        $data = '';
        if ($request->ajax()) {
            $data = $this->loadData($topics);
            return $data;
        }

        return view('topicsituations::frontend.topic',[
            'topics' => $topics,
            'set_paginate' => $set_paginate
        ]);
    }

    public function getSituation($topic_id, Request $request)
    {
        $search = $request->input('search');
        $date_created = $request->input('date_created');
        $topic = Topic::find($topic_id);

        $query = Situation::query();
        $query->where('topic_id',$topic_id);

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
                $sub_query->orWhere('code','like','%' . $search . '%');
                $sub_query->orWhere('description','like','%' . $search . '%');
            });
        }
        
        if ($date_created){
            $query->where('created_at', '>=', date_convert($date_created));
        }

        $situations = $query->get();
        return view('topicsituations::frontend.situation',[
            'topic' => $topic,
            'situations' => $situations,
        ]);
    }

    public function situationDetail($topic_id, $situation_id)
    {
        $situation = Situation::find($situation_id);
        $situation->view = $situation->view + 1;
        $situation->save();
        $topic = Topic::find($topic_id);
        return view('topicsituations::frontend.situation_detail',[
            'topic' => $topic,
            'situation' => $situation,
        ]);
    }

    // like tình huống
    public function likeSituation(Request $request) {
        $check_like = 0;
        $id_situation = $request->id;
        $situation = Situation::where('id',$id_situation)->first(); 
        $profile = LikeSituation::where('user_id',\Auth::id())->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeSituation;
            $set_profile_like_situation = [$id_situation];
            $profile->situation_id = json_encode($set_profile_like_situation);
            $profile->user_id = \Auth::id();
            $profile->save();
            $like = $situation->like + 1;
            $situation->like = $like;
            $situation->save();
            return json_result([
                    'view_like'=>$situation->like,
                    'check_like'=>$check_like,
                ]);
        }
        $get_profile_like_situation = json_decode($profile->situation_id);
        if (($key = array_search($id_situation, $get_profile_like_situation)) !== false) {
            unset($get_profile_like_situation[$key]);
            $newarray = array_values($get_profile_like_situation);
            $profile->situation_id = json_encode($newarray);
            $like = $situation->like - 1;
        } else {
            array_push($get_profile_like_situation, $id_situation);
            $profile->situation_id = json_encode($get_profile_like_situation);
            $like = $situation->like + 1;
            $check_like = 1;
        }     
        $profile->save();   
        $situation->like = $like;
        $situation->save();
        return json_result([
                'view_like' => $situation->like,
                'check_like'=> $check_like,
            ]);
    }

    // like bình luận tình huống
    public function likeComment(Request $request) {
        $check_like = 0;
        $id_comment_situation = $request->id;
        $comment_situation = CommentSituation::where('id',$id_comment_situation)->first(); 
        $profile = LikeCommentSituation::where('user_id',\Auth::id())->whereNull('reply_comment_id')->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeCommentSituation;
            $set_profile_like_comment_situation = [$id_comment_situation];
            $profile->comment_id = json_encode($set_profile_like_comment_situation);
            $profile->user_id = \Auth::id();
            $profile->save();

            $like_comment = $comment_situation->like_comment + 1;
            $comment_situation->like_comment = $like_comment;
            $comment_situation->save();
            return json_result([
                    'view_like'=>$comment_situation->like_comment,
                    'check_like'=>$check_like,
                ]);
        }
        $get_profile_like_comment_situation = json_decode($profile->comment_id);
        if (($key = array_search($id_comment_situation, $get_profile_like_comment_situation)) !== false) {
            unset($get_profile_like_comment_situation[$key]);
            $newarray = array_values($get_profile_like_comment_situation);
            $profile->comment_id = json_encode($newarray);
            $like_comment = $comment_situation->like_comment - 1;
        } else {
            array_push($get_profile_like_comment_situation, $id_comment_situation);
            $profile->comment_id = json_encode($get_profile_like_comment_situation);
            $like_comment = $comment_situation->like_comment + 1;
            $check_like = 1;
        }     
        $profile->save();   
        $comment_situation->like_comment = $like_comment;
        $comment_situation->save();
        return json_result([
                'view_like' => $comment_situation->like_comment,
                'check_like'=> $check_like,
            ]);
    }

    // like phản hồi bình luận tình huống
    public function likeReplyComment(Request $request) {
        $check_like = 0;
        $id_comment_situation = $request->id;
        $comment_situation = ReplyCommentSituation::where('id',$id_comment_situation)->first(); 
        $profile = LikeCommentSituation::where('user_id',\Auth::id())->whereNull('comment_id')->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeCommentSituation;
            $set_profile_like_comment_situation = [$id_comment_situation];
            $profile->reply_comment_id = json_encode($set_profile_like_comment_situation);
            $profile->user_id = \Auth::id();
            $profile->save();

            $comment_situation->like = $comment_situation->like + 1;
            $comment_situation->save();
            return json_result([
                    'view_like'=>$comment_situation->like,
                    'check_like'=>$check_like,
                ]);
        }
        $get_profile_like_comment_situation = json_decode($profile->reply_comment_id);
        if (($key = array_search($id_comment_situation, $get_profile_like_comment_situation)) !== false) {
            unset($get_profile_like_comment_situation[$key]);
            $newarray = array_values($get_profile_like_comment_situation);
            $profile->reply_comment_id = json_encode($newarray);
            $like = $comment_situation->like - 1;
        } else {
            array_push($get_profile_like_comment_situation, $id_comment_situation);
            $profile->reply_comment_id = json_encode($get_profile_like_comment_situation);
            $like = $comment_situation->like + 1;
            $check_like = 1;
        }     
        $profile->save();   
        $comment_situation->like = $like;
        $comment_situation->save();
        return json_result([
                'view_like' => $comment_situation->like,
                'check_like'=> $check_like,
            ]);
    }

    public function loadData($items) {
        $data = '';
        foreach ($items as $topic) {
            $topic_created_at = \Carbon\Carbon::parse($topic->created_at)->format('d/m/Y');
            $data.='<div class="col-lg-3 col-md-4 p-1">
                        <div class="fcrse_1 my-3 p-0">
                            <a href="'.route('frontend.get.situations',['id' => $topic->id]).'" class="image_topic_link">
                                <img class="picture_topic" src="'.image_file($topic->image) .'" alt="" height="150px">
                            </a>
                            <div class="fcrse_content px-3">
                                <div class="course_names text-break">
                                    <a href="'.route('frontend.get.situations',['id' => $topic->id]).'" class="crse14s topic_name">'.$topic->name .'</a>
                                    <p class="">Ngày tạo: '. $topic_created_at .'</p>
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        return $data;
    }
}
