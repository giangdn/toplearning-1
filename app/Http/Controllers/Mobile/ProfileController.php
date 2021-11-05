<?php

namespace App\Http\Controllers\Mobile;

use App\DonatePoints;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Profile;
use App\Http\Controllers\Controller;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionUserPoint;
use App\Models\Categories\TrainingTeacher;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $lay = 'profile';

        $user = Profile::find(Auth::id());
        $title = Titles::whereCode($user->title_code)->first();
        $unit = Unit::whereCode($user->unit_code)->first();

        $roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
            ->get(['id', 'name']);

        $user_meta = function ($key){
            return UserMeta::where('user_id', '=', \Auth::id())->where('key', '=', $key)->first(['value']);
        };
        $user_name = User::find($user->user_id)->username;

        return view('themes.mobile.frontend.profile', [
            'lay' => $lay,
            'user_point' => $this->getUserPoint(),
            'user' => $user,
            'title' => $title,
            'unit' => $unit,
            'user_meta' => $user_meta,
            'user_name' => $user_name,
            'five_user_max_point' => $this->getFiveUserMaxPoint(),
            'get_history_course' => $this->getMyCourse(),
            'total_user' => $this->getTotalUser(),
            'user_rank' => $this->getRankUser(),
            'roadmaps' => $roadmaps,
        ]);
    }

    public function qrCodeUser()
    {
        $info_qrcode = json_encode([
            'user_id' => Auth::id(),
            'type' => 'profile',
        ]);

        $userPointInfo = PromotionUserPoint::where('user_id', Auth::id())->first();
        $promotions = Promotion::where('el_promotion.status',1)
            ->select('el_promotion.*','el_promotion_group.name as group_name')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
            ->orderBy('period')->get();

        return view('themes.mobile.frontend.qrcode.qrcode_user', [
            'info_qrcode' => $info_qrcode,
            'promotions' => $promotions,
        ]);
    }

    public function trainingProcess()
    {
        return view('themes.mobile.frontend.training_process', [
            'get_history_course' => $this->getMyCourse(),
        ]);
    }

    /*Lấy điểm của 1 học viên*/
    public function getUserPoint(){
        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.user_id', '=', Auth::id())
            ->first();

        return $user;
    }

    /*Lấy điểm cao nhất của 5 học viên*/
    public function getFiveUserMaxPoint($limit = 5){
        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            ->whereNotIn('profile.user_id', $training_teacher)
            ->orderBy('user_point.point', 'DESC')
            ->limit($limit)
            ->get();

        return $user;
    }

    /*Lịch sử học của user*/
    public function getMyCourse()
    {
        $prefix = \DB::getTablePrefix();
        $query = \DB::table('el_course_view as a')->select(['a.*'])
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->where('b.user_id','=', \Auth::id())
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->orderBy('a.id', 'desc');
        return $query->paginate(20);
    }

    /*Lấy tất cả học sinh có điểm*/
    public function getTotalUser(){
        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $user = Profile::query()
            ->select([
                'profile.user_id',
                'user_point.point',
            ])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            ->whereNotIn('profile.user_id', $training_teacher)
            ->orderBy('user_point.point', 'DESC')
            ->get();

        return $user;
    }

    public function getRankUser(){
        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $user = Profile::query()
            ->select([
                'profile.user_id',
            ])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            ->whereNotIn('profile.user_id', $training_teacher)
            ->orderBy('user_point.point', 'DESC')
            ->get();

        $user_rank = '';
        foreach ($user as $key => $item){
            if ($item->user_id == \Auth::id()){
                $user_rank = ($key + 1);
            }
        }

        return $user_rank;
    }

    public function changeAvatar(Request $request){
        $posts = [ 'selectavatar' => $request->file('selectavatar') ];
        $rules = [ 'selectavatar' => 'required|image|max:10240' ];
        $message = [
            'selectavatar.required' => 'Chưa chọn hình để upload',
            'selectavatar.image' => 'File hình không hợp lệ',
            'selectavatar.uploaded'  =>'Dung lượng hình không được lớn hơn 10mb'
        ];

        $validator = \Validator::make($posts, $rules,$message);
        if ($validator->fails()){
            return redirect()->back();
        }

        $avatar = $request->file('selectavatar');
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $extension = $avatar->getClientOriginalExtension();
        $filename = 'avatar-' . \Auth::id() .'.'. $extension;

        if($storage->putFileAs('profile', $avatar, $filename))
        {
            Profile::where('user_id','=',\Auth::id())
                ->update(['avatar'=>$filename]);
            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
    }

    public function accumulatedCourse()
    {
        return view('themes.mobile.frontend.history_point.course', [
            'get_history_course' => $this->getMyCourse(),
        ]);
    }

    public function accumulatedVideo()
    {
        $get_history_video = DailyTrainingVideo::query()
            ->where('created_by', '=', Auth::id())
            ->where('status', '=', 1)
            ->where('approve', '=', 1)
            ->paginate(20);

        return view('themes.mobile.frontend.history_point.video', [
            'get_history_video' => $get_history_video,
        ]);
    }

    public function accumulatedBonusPoints()
    {
        $donate_points = DonatePoints::where('user_id', '=', Auth::id())->first();

        return view('themes.mobile.frontend.history_point.donate_points', [
            'donate_points' => $donate_points,
        ]);
    }

    public function myCourse(){
        return view('themes.mobile.frontend.my_course', [
            'my_course' => $this->getMyCourse(),
        ]);
    }

    public function getRank(){
        $lay = 'rank';

        return view('themes.mobile.frontend.rank_user', [
            'rank' => $this->getFiveUserMaxPoint(10),
            'lay' => $lay
        ]);
    }
}
