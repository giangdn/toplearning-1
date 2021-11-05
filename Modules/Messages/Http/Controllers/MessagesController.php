<?php

namespace Modules\Messages\Http\Controllers;

use App\Events\MessageBot;
use App\Events\MessagePost;
use App\Events\MessageUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Messages\Entities\Message;
use Modules\Online\Entities\OnlineCourse;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('messages::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('messages::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();
        $message = new Message();
        $message->message = $request->input('message', '');
        $message->from = $user->id;
        $message->to = 0;
        $message->save();
        event(new MessageUser($message,$user,$request->header('X-Socket-Id')));
        return ['message' => 'Toi la bot ban can gi'];
    }

    public function botProcess(Request $request)
    {
        $user = \Auth::user();
        $bot = new Message();
        $bot->message = $this->getMessageBot($request->message);
        $bot->from = 0;
        $bot->to = $user->id;
        $bot->save();
        event(new MessageUser($bot,$user,$request->header('X-Socket-Id'),1));
        return 'ok';
    }
    public function getMessageUser(Request $request){
        return Message::with('user')
            ->where(['room',$request->query('room')])
            ->latest()
            ->paginate(50);
    }

    public function saveMessageUser(Request $request)
    {
        $user = \Auth::user();
        $receiver = (int)$request->input('receiver');
        $sender = $user->id;
        $message = new Message();
        $message->message = $request->input('message', '');
        $message->from = $sender;
        $message->to = (int)$request->input('receiver');
        $message->room=$sender < $receiver ? $sender.'__'.$receiver : $receiver.'__'.$sender;
        $message->save();
        broadcast(new MessagePost($message,$user));
        return ['message' => 'ok'];
    }
    private function getMessageBot($key){
        $mesage ='Xin chào tôi có thể giúp gì cho bạn';
        $detectKey = substr($key,0,2);
        if (strtolower($key)=='#report') {
            $mesage  = '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id'=>'BC01']) . '">01. Báo cáo số liệu công tác khảo thi</a><br>';
            $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id'=>'BC02']) . '">02. Báo cáo số liệu điểm thi chi tiết</a><br>';
            $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id'=>'BC03']) . '">03. Báo cáo cơ cấu đề thi</a><br>';
            $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id'=>'BC04']) . '">04. Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi</a><br>';
            $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id'=>'BC05']) . '">05. Báo cáo học viên tham gia khóa học tập trung / trực tuyến</a><br>';
            $mesage .= '<a class="text-danger" target="_blank" ="' . route('module.report_new.review', ['id'=>'BC06']) . '">06. Danh sách học viên của đơn vị theo chuyên đề</a> ';
        }elseif(strtolower($detectKey)=='#e'){
            $code = substr($key,3);
            $online = OnlineCourse::where(['code'=>$code])->first();
            if ($online)
                $mesage ='<a class="text-danger" target="_blank" href="' . route('module.online.detail_online', ['id'=>$online->id]) . '">'.$online->name.'</a>';
            else
                $mesage ='Không tìm thấy khóa học';
        }
        return $mesage;
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('messages::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('messages::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
