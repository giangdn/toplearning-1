<?php


namespace App\Helpers;

use App\Profile;

class AppApi
{
    protected $AppToken = '60fb3fccc7279b903ccd921410c49c6786228052';
    protected $api_url = 'https://tms.teama.io/app';

    protected $user_id;

    public function __construct($user_id = null)
    {
        $this->user_id = $user_id ?? \Auth::id();
    }

    public function register($DeviceModel, $VersionCode, $DeviceToken) {
        $response = $this->post('arrow', [
            'AppToken' => $this->AppToken,
            '_act' => 'updateDevice',
            'UserId' => $this->user_id,
            'DeviceToken' => $DeviceToken,
            'DeviceModel' => $DeviceModel,
            'VersionCode' => $VersionCode,
            'UserName' => Profile::fullname($this->user_id)
        ]);

        $response = json_decode($response);
        return $response->module->Result;
    }

    public function pushNotify($title, $MessageBody, $Route, $Image){
        $response = $this->post('arrow', [
            'AppToken' => $this->AppToken,
            '_act' => 'pushNotification',
            'UserId' => $this->user_id,
            'Title' => $title,
            'MessageBody' => $MessageBody,
            'Route' => $Route,
            'Image' => $Image
        ]);

        $response = json_decode($response);
        return $response->module->Result;
    }

    protected function get($uri, $params = []) {
        return HttpClient::get($this->api_url . '/'. $uri, $params);
    }

    protected function post($uri, $params = []) {
        return HttpClient::post($this->api_url . '/'. $uri, $params);
    }
}
