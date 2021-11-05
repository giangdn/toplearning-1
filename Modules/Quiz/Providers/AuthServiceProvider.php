<?php

namespace Modules\Quiz\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Quiz\Entities\QuizUserSecondary;
use TorMorten\Eventy\Facades\Events;

class AuthServiceProvider extends ServiceProvider
{
    public function boot() {
        Events::addAction('auth.login_failed', [$this, 'login'], 20, 3);
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
    
    public function login($username, $password, $remember) {
        $user = QuizUserSecondary::whereUsername('secondary_' . $username)->first();
        if ($user) {
            if (Auth::guard('secondary')->attempt([
                'username' => $username,
                'password' => $password,
            ], $remember)) {
            
                die(response()->json([
                    'redirect' => route('module.quiz')
                ])->getContent());
            
            } else {
                die(response()->json([
                    'status' => 'error',
                    'message' => trans('auth.login_user'),
                    'redirect' => route('login'),
                ])->getContent());
            }
        }
    }
}
