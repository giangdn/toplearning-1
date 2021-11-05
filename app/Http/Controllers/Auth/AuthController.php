<?php

namespace App\Http\Controllers\Auth;

use App\Events\LoginSuccess;
use App\Http\Controllers\Controller;
use App\Profile;
use App\User;
use App\Visits;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Facades\Socialite;
use Modules\Quiz\Entities\QuizUserSecondary;
use SocialiteProviders\Manager\Config;
use TorMorten\Eventy\Facades\Events;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';
    public function __construct()
    {
        if (url_mobile()){
            $clientId = \Config::get('app.azure.client_id_mobi');
            $clientSecret =  \Config::get('app.azure.client_secret_mobi');
            $redirectUrl = \Config::get('app.azure.redirect_mobi');
            return $config = new Config($clientId, $clientSecret, $redirectUrl);
        }else{
            $clientId = \Config::get('app.azure.client_id');
            $clientSecret =  \Config::get('app.azure.client_secret');
            $redirectUrl = \Config::get('app.azure.redirect');
            return $config = new Config($clientId, $clientSecret, $redirectUrl);
        }

    }
    public function config()
    {

        $clientId = env('AZURE_CLIENT_ID');
        $clientSecret =  urlencode(env("AZURE_CLIENT_SECRET"));
        $redirectUrl = env('AZURE_REDIRECT');
        return $config = new Config($clientId, $clientSecret, $redirectUrl);
    }
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
//        return Socialite::driver($provider)->setConfig($this->config())->with(["prompt" => "select_account"])->redirect();
    }
    public function handleProviderCallbackAzure()
    {
        try {
            $user = Socialite::driver('azure')->user();
//            $user = Socialite::driver('azure')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
//        $url_preouis = session()->get('url_previous') ? session()->get('url_previous') : route('module.news');
        $url = isMobile() ? \Config::get('app.mobile_url') : route('module.news');
        $email = explode("#", $user->user['mail'])[0];
        // check if they're an existing user
        $existingUser = User::where('email',$email)->first();
        if($existingUser){
            $profile = Profile::find($existingUser->id);
            if (!$profile->avatar){
                $profile->avatar = $user->avatar;
                $profile->save();
            }
            // set session login microsoft
            session(['autho' => 'azure']);
            auth()->login($existingUser);

            event(new LoginSuccess($existingUser));
        } else {
            return redirect($url);
        }
        return redirect($url);
    }
    public function handleProviderCallbackGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // only allow people with @company.com to login
        if(explode("@", $user->email)[1] !== 'gmail.com'){
            return redirect()->to('/');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {dd(3);
            // create a new user
            $newUser                  = new User;
            $newUser->firstname            = $user->given_name;
            $newUser->lastname            = $user->family_name;
            $newUser->lastname            = $user->family_name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->to('/');
    }
}
