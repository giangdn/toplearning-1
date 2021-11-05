<?php

namespace Modules\Config\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ConfigController extends Controller
{
    public function index()
    {
        return view('config::index');
    }

    public function formRefer()
    {
        $grade_refer = Config::where('name','=','grade_refer')->value('value');
        $grade_refered = Config::where('name','=','grade_refered')->value('value');
        $point_course_referer = Config::where('name','=','point_course_referer')->value('value');
        $point_course_referer_finish = Config::where('name','=','point_course_referer_finish')->value('value');
        return view('config::backend.refer',
            [
                'grade_refer'=>$grade_refer,
                'grade_refered'=>$grade_refered,
                'point_course_referer'=>$point_course_referer,
                'point_course_referer_finish'=>$point_course_referer_finish,
            ]
        );
    }
    
    public function saveRefer(Request $request)
    {
        Config::updateOrCreate(['name'=>'grade_refer'],['value'=>$request->input('grade_refer')]);
        Config::updateOrCreate(['name'=>'grade_refered'],['value'=>$request->input('grade_refered')]);
        Config::updateOrCreate(['name'=>'point_course_referer'],['value'=>$request->input('point_course_referer')]);
        Config::updateOrCreate(['name'=>'point_course_referer_finish'],['value'=>$request->input('point_course_referer_finish')]);
        json_message('Cập nhật thành công','success');
    }

    public function formEmail()
    {
        return view('config::backend.email');
    }

    public function saveEmail(Request $request)
    {
        $configs = $request->only('email_driver','email_host','email_port','email_user','email_password','email_encryption','email_from_name','email_address');
        foreach ($configs as $key => $config) {
            Config::setConfig($key, $config);
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success')
        ]);
    }
    
    public function testSendMail(Request $request) {
        $this->validateRequest([
            'email' => 'required',
        ], $request, [
            'email' => 'Email test',
        ]);
    
        $emails = explode(',', $request->post('email'));
        $subject = 'Email gửi từ hệ thống LMS';
        $content = 'Đây là email gửi từ hệ thống LMS. Nếu bạn nhận được email này tức là cấu hình mail đã chính xác.';
        
        try {
            Mail::send('mail.default', [
                'content' => $content
            ], function ($message) use ($emails, $subject) {
                $message->to($emails)->subject($subject);
            });
        
            if (Mail::failures()) {
                return response()->json([
                    'status' => 'error',
                    'message' => Mail::failures()[0],
                ]);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Gửi mail thành công! Vui lòng kiểm tra mail test.',
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
