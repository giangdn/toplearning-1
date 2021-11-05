<?php

namespace App\Http\Controllers\Auth;

use App\Automail;
use App\Events\Logged;
use App\Events\LoginSuccess;
use App\Http\Controllers\Controller;
use App\LoginHistory;
use App\MailTemplate;
use App\Profile;
use App\SliderOutside;
use App\InfomationCompany;
use App\UserContactOutside;
use App\AdvertisingPhoto;
use App\User;
use App\UserThird;
use App\Visits;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\NewsOutside\Entities\NewsOutsideCategory;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\User\Entities\LoginFail;
use TorMorten\Eventy\Facades\Events;
use Modules\NewsOutside\Entities\NewsOutside;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected function authenticated()
    {
        \Auth::logoutOtherDevices(request('password'));
    }

    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $remember = $request->filled('remember');

        $remember_login = $request->post('remember_login');
        if($remember_login) {
            session(['username' => $username]);
            session(['password' => $password]);
            session(['remember_login' => 1]);
            session()->save();
        } else {
            session()->forget('username');
            session()->forget('password');
            session()->forget('remember_login');
        }

        $secondary = QuizUserSecondary::where('username', '=', 'secondary_' . $username)->first();
        if ($secondary) {
            $login_fail_user_second = LoginFail::query()
                ->where('user_id', '=', $secondary->id)
                ->where('username', '=', $username)
                ->where('user_type', '=', 2)
                ->first();
            if ($login_fail_user_second && $login_fail_user_second->num_fail == 3){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tài khoản của bạn bị khóa. Vui lòng liên hệ Trung tâm đào tạo.',
                    'redirect' => route('login'),
                ]);
            }

            if (Auth::guard('secondary')->attempt(['username' => 'secondary_' . $username, 'password' => $password], $remember)) {

                if ($login_fail_user_second){
                    $login_fail_user_second->num_fail = 0;
                    $login_fail_user_second->save();
                }
                $user_secondary = LoginHistory::where('user_id', '=', $secondary->id)
                    ->where('user_type',2)
                    ->orderBy('created_at', 'DESC')
                    ->first(['number_hits']);

                $model = new LoginHistory();
                $model->user_id = $secondary->id;
                $model->user_code = $secondary->code;
                $model->user_name = $secondary->username;
                $model->ip_address = request()->ip();
                $model->user_type = 2;
                if ($user_secondary){
                    $model->number_hits = $user_secondary->number_hits + 1;
                }else{
                    $model->number_hits = 1;
                }
                $model->save();

                return response()->json([
                    'redirect' => route('module.quiz')
//                    'redirect' => route('module.frontend.user.my_course',['type' => 0])
                ]);
            } else {

                LoginFail::query()
                    ->updateOrCreate([
                        'user_id' => $secondary->id,
                        'user_type' => 2,
                        'username' => $username
                    ], [
                        'num_fail' => $login_fail_user_second ? ($login_fail_user_second->num_fail + 1) : 1
                    ]);

                return response()->json([
                    'status' => 'error',
                    'message' => trans('auth.login_user'),
                    'redirect' => route('login'),
                ]);
            }
        }

        $user = User::whereUsername($username)->where('auth', '!=', 'blocked')->first(['id', 'username', 'auth']);

        if ($user) {
            if ($user->login($password, $remember)) {
                session(['login' => 1]);
                $url = session()->get('url_previous');
                if($url && (!in_array('login.live.com', explode('/',$url)) || !in_array('redirect', explode('/',$url)) || !in_array('login', explode('/',$url)))) {
                    return response()->json([
                        'redirect' => session()->get('url_previous')
                    ]);
                }
                event(new LoginSuccess(Auth::user()));
                Visits::saveVisits();

                LoginFail::query()
                    ->updateOrCreate([
                        'user_id' => $user->id,
                        'user_type' => 1,
                        'username' => $username
                    ], [
                        'num_fail' => 0
                    ]);

                if (url_mobile()){
                    return response()->json([
                        'redirect' => route('frontend.home')
                    ]);
                }

                return response()->json([
                    // 'redirect' => route('module.frontend.user.my_course',['type' => 0])
                    'redirect' => route('module.news')
                ]);
            }
        }

        Events::action('auth.login_failed', $username, $password, $remember);

        $login_fail_user = LoginFail::query()
            ->where('user_id', '=', ($user ? $user->id : 0))
            ->where('username', '=', $username)
            ->where('user_type', '=', 1)
            ->first();

        if (($login_fail_user && $login_fail_user->num_fail == 3) || @$user->auth == 'blocked'){
            DB::table('user')
                ->where('id', '=', @$user->id)
                ->update(['auth' => 'blocked']);

            return response()->json([
                'status' => 'error',
                'message' => 'Tài khoản của bạn bị khóa. Vui lòng liên hệ Trung tâm đào tạo.',
                'redirect' => route('login'),
            ]);
        }

        $num_fail = LoginFail::query()
            ->updateOrCreate([
                'user_id' => ($user ? $user->id : 0),
                'user_type' => 1,
                'username' => $username
            ], [
                'num_fail' => $login_fail_user ? ($login_fail_user->num_fail + 1) : 1
            ]);

        return response()->json([
            'status' => 'error',
            'message' => trans('auth.login_user') .PHP_EOL.' Bạn còn ' . (3 - @$num_fail->num_fail) .' đăng nhập',
            'redirect' => route('login'),
        ]);
    }

    public function logout(Request $request)
    {
        if(url_mobile()) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $isLoginAzure = session('autho');
        $model = LoginHistory::where('user_id', '=', Auth::id())
            ->orderBy('created_at', 'DESC')->first();
        if ($model) {
            $model->updated_at = time();
            $model->save();
        }
        session()->flush();
        $this->guard()->logout();
        $request->session()->invalidate();


        if($flag == 1) {
                return  redirect()->route('mobile');
        }
        session(['logout' => 1]);
        session()->save();
        return  redirect(route('home_outside', ['type' => 0]));
    }

    public function logoutAzure(Request $request)
    {
        return  redirect('https://login.microsoftonline.com/common/oauth2/logout?post_logout_redirect_uri='.route('returnLogoutAzure'));
//        return  redirect('https://login.microsoftonline.com/c940a8c7-1c8f-44f1-9f2c-de62cdef713d/oauth2/logout?post_logout_redirect_uri='.route('returnLogoutAzure'));
    }
    public function returnLogoutAzure(){
        if(url_mobile()) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        \Session::flush();
        if($flag == 1) {
            return  redirect()->route('mobile');
        }
        return redirect(route('home_outside',['type' => 0]));
    }
    public function showLoginForm()
    {
        $logout = route('logout');
        if(session()->get('url_previous') == $logout) {
            session()->forget('url_previous');
        }
        if (url_mobile()){
            session(['layout' => 'mobile']);
            return view('themes.mobile.auth.login');
        }

        return view('auth.login');
    }

    public function resetPass(Request $request){
        $username = $request->username;
        $email = $request->email;

        $user = User::where('username', '=', $username)->where('auth', '=', 'manual')->first();
        if ($user && $user->id > 2){
            $profile = Profile::where('user_id', '=', $user->id)->where('email', '=', $email)->first();
            if ($profile){
                $pass_new = Str::random(10);

                $check_template_mail = MailTemplate::where('code', '=', 'reset_pass');
                if (!$check_template_mail->exists()){
                    $mail_template = new MailTemplate();
                    $mail_template->code = 'reset_pass';
                    $mail_template->name = 'Lấy lại mật khẩu khi quên';
                    $mail_template->title = 'Mail lấy lại mật khẩu';
                    $mail_template->content = 'Mật khẩu mới của bạn là: {pass}';
                    $mail_template->note = 'Đối tượng nhận: mọi user';
                    $mail_template->status = 1;
                    $mail_template->save();
                }

                $automail = new Automail();
                $automail->template_code = 'reset_pass';
                $automail->params = [
                    'pass' => $pass_new,
                ];
                $automail->users = [$profile->user_id];
                $automail->object_id = $profile->user_id;
                $automail->object_type = 'reset_pass';
                $automail->addToAutomail();

                return response()->json([
                    'status' => 'sucess',
                    'message' => 'Password đã thay đổi. Mời vào mail bạn lấy thông tin',
                    'redirect' => route('login'),
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email không đúng',
                    'redirect' => route('login'),
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Tên đăng nhập không đúng',
                'redirect' => route('login'),
            ]);
        }
    }

    public function resetPassUserQuestion(Request $request){
        $user_id = decrypt_array($request->user_id);
        $password = $request->password;

        $user = User::find($user_id[0]);
        if($user && $user->id > 2){
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();

            return json_message('Password đã thay đổi','success');
        }
        return json_message('Password chưa thay đổi','error');
    }

    public function checkUserQuestion(Request $request){
        $key = $request->key;

        foreach ($key as $item){
            if (!$request->{$item}){
                return json_message('Chưa nhập câu hỏi đầy đủ','error');
            }
        }
        $month = $request->month;
        $day = $request->day;
        $year = $request->year;
        $join_company = $request->join_company;
        $code = $request->code;
        $phone = $request->phone;
        $identity_card = $request->identity_card;
        $unit_code = $request->unit_code;
        $title_code = $request->title_code;

        $profile = Profile::where('status', '=', 1);
        if ($month){
            $profile->where(\DB::raw('month(dob)'), '=', $month);
        }
        if ($day){
            $profile->where(\DB::raw('day(dob)'), '=', $day);
        }
        if ($year){
            $profile->where(\DB::raw('year(dob)'), '=', $year);
        }
        if ($join_company){
            $profile->where('join_company', '=', date_convert($join_company));
        }
        if ($code){
            $profile->where('code', '=', $code);
        }
        if ($phone){
            $profile->where('phone', '=', $phone);
        }
        if ($identity_card){
            $profile->where('identity_card', '=', $identity_card);
        }
        if ($unit_code){
            $profile->where('unit_code', '=', $unit_code);
        }
        if ($title_code){
            $profile->where('title_code', '=', $title_code);
        }

        $profile = $profile->first();
        if ($profile){
            return json_result([
                'user_id' => $profile ? encrypt_array([$profile->user_id]) : ''
            ]);
        }else{
            return json_message('Thông tin không chính xác','error');
        }
    }

    public function saveUserThird(Request $request){
        $this->validateRequest([
            'username' => 'required_if:id,==,|unique:user,username',
            'password' => 'nullable|required_if:id,==,',
            'lastname' => 'required',
            'firstname' => 'required',
        ],$request, Profile::getAttributeName());

        $user = User::firstOrNew(['id' => $request->id]);
        $user->auth = 'manual';
        $user->username = $request->username;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->save();

        $model = Profile::firstOrNew(['id' => $user->id]);
        $model->fill($request->all());
        $model->code = Str::random(10);
        $model->unit_code = 'DV1';
        $model->title_code = 'CD1';
        $model->type_user = 2;
        $model->id = $user->id;
        $model->user_id = $user->id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('app.notify_register_success'),
                'redirect' => route('login')
            ]);
        }

        json_message('Không thể lưu', 'error');
    }

    public function homeOutside($type){
        $url = url()->previous();
        $check = '';
        if (in_array('detail-home-outside', explode('/',$url)) || in_array('news-outside', explode('/',$url)) || in_array('admin-cp', explode('/',$url))) {
            $check = 1;
        }
        $get_app_url = env('APP_URL');
        $home = route('frontend.home');
        $home_outside = route('home_outside',['type' => 0]);
        $login = route('login');
        if( $url && $check != 1
            && ($get_app_url != $url)
            && ($url != $home_outside)
            && ($url != $login)
            && ($url != $home) )
        {
            session(['url_previous' => $url]);
            session()->save();
        }
        // dd(session()->all());
        $sliders = SliderOutside::where('status', '=', 1)->get();
        $get_main_new_hot = NewsOutside::where('hot_public',1)->orderByDesc('created_at')->first();
        $get_hot_news = '';
        if($get_main_new_hot) {
            $get_hot_news = NewsOutside::select(['id','image','description','title','date_setup_icon'])->where('status',1)->where('hot_public',1)->where('id','!=',$get_main_new_hot->id)->get();
        }

        $get_news_parent_cate_left = NewsOutsideCategory::whereNull('parent_id')->where('status',1)->orderBy('stt_sort_parent','asc')->get();

        $get_news_category_sort_right = NewsOutsideCategory::query()
        ->select('a.*')
        ->from('el_news_outside_category as a')
        ->leftJoin('el_news_outside_category as b','b.id','=','a.parent_id')
        ->where('a.sort',2)
        ->orderBy('b.stt_sort_parent', 'asc')
        ->orderBy('a.stt_sort', 'asc')->get();

        $get_infomation_company = InfomationCompany::first();

        $getAdvertisingPhotos = AdvertisingPhoto::where('status',1)->where('type',0)->get();
        if($type == 1) {
            session(['show_home_page' => 1]);
            session()->save();
        }
        return view('frontend.home_outside', [
            'sliders' => $sliders,
            'users_online' => \App\User::countUsersOnline(),
            'get_main_new_hot' => $get_main_new_hot,
            'get_hot_news' => $get_hot_news,
            'get_news_parent_cate_left' => $get_news_parent_cate_left,
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'get_infomation_company' => $get_infomation_company,
            'type' => $type,
        ]);
    }

    public function detailHomeOutside($id, $type) {
        // \Session::forget('like');
        $sliders = SliderOutside::where('status', '=', 1)->get();

        $get_news_category_sort_right = NewsOutsideCategory::query()
        ->select('a.*')
        ->from('el_news_outside_category as a')
        ->leftJoin('el_news_outside_category as b','b.id','=','a.parent_id')
        ->where('a.sort',2)
        ->orderBy('b.stt_sort_parent', 'asc')
        ->orderBy('a.stt_sort', 'asc')->get();

        // dd($get_news_category_sort_right);
        $get_new_outside = NewsOutside::find($id);
        $get_new_outside->views = $get_new_outside->views + 1;
        $get_new_outside->save();
        $get_category = NewsOutsideCategory::where('id',$get_new_outside->category_id)->first();
        $get_category_parent = NewsOutsideCategory::where('id',$get_category->parent_id)->first();
        $get_related_news_outside = NewsOutside::select(['image','title','description','id'])->where('category_id',$get_new_outside->category_id)->where('status',1)->where('id','!=',$get_new_outside->id)->paginate(20);
        $getAdvertisingPhotos = AdvertisingPhoto::where('status',1)->where('type',0)->get();
        $get_infomation_company = InfomationCompany::first();
        return view('frontend.detail_home_outside', [
            'sliders' => $sliders,
            'users_online' => \App\User::countUsersOnline(),
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'get_new_outside' => $get_new_outside,
            'get_related_news_outside' => $get_related_news_outside,
            'get_category' => $get_category,
            'get_category_parent' => $get_category_parent,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'get_infomation_company' => $get_infomation_company,
            'type' => $type,
        ]);
    }

    public function hotNewsHomeOutside() {
        $get_hot_news = NewsOutside::select(['image','title','description','id'])->where('hot',1)->where('status',1)->get();
        return view('frontend.hot_news_outside',[
            'get_hot_news' => $get_hot_news,
        ]);
    }

    public function likeNewOutside(Request $request) {
        $check_like = 0;
        $id = $request->id;
        // dd(session()->get('like'));
        if (empty(session()->get('like'))) {
            $sessionLike = session()->put('like', []);
            $like = NewsOutside::find($request->id);
            $like->like_new = $like->like_new + 1;
            $check_like = 1;
            session()->push('like', $id);
            session()->save();
            $like->save();
        } else {
            $sessionLike = session()->get('like');
            if (($key = array_search($request->id, $sessionLike)) !== false) {
                unset($sessionLike[$key]);
                $sessionLike = array_values($sessionLike);
                $like = NewsOutside::find($request->id);
                $like->like_new = $like->like_new - 1;
                $like->save();
                session()->forget('like');
                session()->put('like', $sessionLike);
                session()->save();
            } else {
                $like = NewsOutside::find($request->id);
                $like->like_new = $like->like_new + 1;
                $check_like = 1;
                session()->push('like', $id);
                session()->save();
                $like->save();
            }
        }

        return json_result([
            'view_like'=>$like->like_new,
            'check_like'=>$check_like,
        ]);
    }

    public function ajaxGetRelatedNews(Request $request) {
        $category_id = $request->category_id;
        $date_search = date("Y-m-d", strtotime($request->date_search));
        $new_id = $request->new_id;
        $get_related_news_outside = NewsOutside::where('category_id',$category_id)
        ->select(['image','title','description','id'])
        ->where('status',1)
        ->where('id','!=',$new_id)
        ->whereDate('created_at', '=', $date_search)
        ->get();
        // dd($get_related_news_outside);
        $image_related_new = [];
        if(!$get_related_news_outside->isEmpty()){
            foreach($get_related_news_outside as $item) {
                $image_related_new[] = ['image' => image_file($item->image), 'id' => $item->id, 'title' => $item->title, 'description' => $item->description];
            }
        }
        return json_result([
            'get_related_news_outside'=>$image_related_new,
        ]);
    }

    public function userContact() {
        $model = new InfomationCompany();
        $get_infomation_company = InfomationCompany::first();
        return view('frontend.user_contact_outside',[
            'model' => $model,
            'get_infomation_company' => $get_infomation_company,
        ]);
    }

    public function saveUserContact(Request $request) {
        $this->validateRequest([
            'title' => 'required',
            'content' => 'required',
        ], $request, UserContactOutside::getAttributeName());

        $model = UserContactOutside::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('user_contact_outside')
            ]);
        }

        json_message('Không thể lưu', 'error');

    }

    public function modalResetPass(){
        if (url_mobile()){
            return view('themes.mobile.modal.fogot_password');
        }
        return view('modal.reset_pass');
    }
}
