<?php

namespace Modules\AppNotification\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AppNotification\Entities\AppDeviceToken;
use TorMorten\Eventy\Facades\Events;

class AuthDeviceModelProvider extends ServiceProvider
{
    public function boot() {
        Events::addAction('auth.middleware_handle', [$this, 'registerDeviceModel'], 20);
    }
    
    public function register()
    {
        //
    }
    
    public function provides()
    {
        return [];
    }
    
    public function registerDeviceModel($request) {
        if (!\Auth::check()) {
            if ($request->get('DeviceModel')) {
                session(['DeviceModel' => $request->DeviceModel]);
                session(['VersionCode' => $request->VersionCode]);
                session(['DeviceToken' => $request->DeviceToken]);
            }
        }
        
        if (\Auth::check() && session()->exists('DeviceModel')) {
            $DeviceModel = session()->get('DeviceModel');
            $VersionCode = session()->get('VersionCode');
            $DeviceToken = session()->get('DeviceToken');
    
            AppDeviceToken::updateOrCreate([
                'user_id' => \Auth::id(),
                'device_token' => $DeviceToken,
            ], [
                'device_model' => $DeviceModel,
                'version_code' => $VersionCode,
                'device_token' => $DeviceToken,
            ]);
    
            session()->pull('DeviceModel');
            session()->pull('VersionCode');
            session()->pull('DeviceToken');
        }
        
        
    }
}
