<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Promotion\Entities\PromotionUserPoint;

class QrcodeController extends Controller
{
    public function index()
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.qrcode.qrcode');
        }
        return view('qrcode.qrcode');
    }

    public function message()
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.qrcode.qrcode-message');
        }
        return view('qrcode.qrcode-message');
    }

    public function process(Request $request)
    {
        if($request->type=='attendance'){
            $x=OfflineAttendance::updateAttendance($request->user,$request->course,$request->schedule);
            if ($x)
                return redirect()->route('qrcode_message')->with('success','Điểm danh thành công');
            else
                return redirect()->route('qrcode_message')->with('error','Học viên này chưa được ghi danh vào khóa học');
        }
        elseif($request->type=='survey_after_course'){
            $url = route('module.rating.course',['type'=>$request->course_type,'id'=>$request->course]);
            return redirect($url);
        }
        elseif($request->type=='quiz'){
            $url = route('module.quiz.doquiz.index',['quiz_id'=>$request->quiz,'part_id'=>$request->part]);
            return redirect($url);
        }elseif($request->type=='teacher_attendance'){
            $x=OfflineAttendance::updateAttendance($request->user,$request->course,$request->schedule);
            if ($x)
                return redirect()->route('frontend.attendance.course',['course_id'=>$request->course]+ ['schedule'=>$request->schedule])->with('success','Điểm danh thành công');
            else
                return redirect()->route('frontend.attendance.course',['course_id'=>$request->course]+ ['schedule'=>$request->schedule])->with('error','Học viên này chưa được ghi danh vào khóa học');
        }elseif($request->type=='referer'){
            $user = Profile::where('user_id','=',$request->user)->first();
            if ($user){
                if (!$user->referer){
                    Profile::where('user_id','=',\Auth::id())->update(['referer'=>$user->id_code]);
                    PromotionUserPoint::updatePoint($user->id_code);
                }
                return redirect()->route('frontend.user.referer')->with('success','Cập nhật người giới thiệu thành công');
            }
            else
                return redirect()->route('frontend.user.referer' )->with('error','User không tồn tại trong hệ thống');
        }elseif($request->type=='referer-course'){
            $user = Profile::where('user_id','=',$request->user)->first();
            if ($user){
                return json_result(['message'=>'ok','data'=>$user->id_code,'status'=>'success']);
            }
            else
                return json_result(['message'=>'error','status'=>'error']);
        }
        return abort('404');
    }
}
