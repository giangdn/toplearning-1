<?php

namespace Modules\AppNotification\Helpers;

use GuzzleHttp\Client;

class FirebaseApi
{
    protected $ClientToken = 'AAAA9-o0XyE:APA91bEkYw2cMX5pvMc8jCHD9t-HLpfEa-bJZY5mhsifAhqdHsJYugVxvKjj7tUfvm3WfCGU7Kqrr-zHEWMNFy8ujv9Yylnlp4WLy-tVBVt1MBbn3tsXdprivSSY_fqhdlanEt94WdqR';
    
    protected $Transposter = null;
    
    protected $Response = null;
    
    protected $ResponseCode = 0;
    
    protected $Uri  = 'https://fcm.googleapis.com/fcm/send';
    
    protected $Header = [];
    
    public function __construct($ClientToken = null)
    {
        // build the header
        $this->Header['Content-Type'] = 'application/json';
        $this->Header['Authorization'] = 'key=' . $this->ClientToken;
        
        // init the guzzclient
        $this->Transposter = new Client();
    }
    
    /**
     * @param string$DeviceToken
     * @param \Modules\AppNotification\Helpers\FirebaseMessage
     * @return json
     * @throws
     * */
    public function send($DeviceToken, $Message)
    {
        try {
            
            $Options = [
                'headers' => $this->Header,
                'body' => $Message->to($DeviceToken)
            ];
            
            $res = $this->Transposter->post($this->Uri, $Options);
            $this->ResponseCode = $res->getStatusCode();
            $this->Response = $res->getBody()->getContents();
    
            /*$debugItem = (object) [
                'StatusCode'    => $this->ResponseCode,
                'Uri'           => $this->Uri,
                'Options'       => $Options,
                'SendMessage'		=> json_decode($Message->to($DeviceToken)),
                'ResponseMessages'      => $this->getResponseMessages(),
                'Response' 			=> $this->Response
            ];*/
        
            
            return json_decode($this->Response);
            
        } catch (\Exception $e) {
            \Log::error('FirebaseApi: Remote request failse with exception message: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * @return NULL | array
     */
    public function getResponseMessages()
    {
        if ($this->Response && $objResponse = json_decode($this->Response)) {
            return isset($objResponse->messages) ? $objResponse->messages : null;
        }
    }
    
    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->Response;
    }
}