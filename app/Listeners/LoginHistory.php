<?php

namespace App\Listeners;

use App\Analytics;
use App\Events\LoginSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\LoginHistory as AppLoginHistory;

class LoginHistory
{
    public function __construct()
    {
        //
    }
    
    public function handle(LoginSuccess $event)
    {
        AppLoginHistory::setLoginHistory();
        /* analytics  */
        
        $analytic = new Analytics();
        $analytic->user_id = $event->user->id;
        $analytic->ip_address = request()->ip();
        $analytic->start_date = date('Y-m-d H:i:s');
        $analytic->day = date('Y-m-d');
        $analytic->save();
    }
}
