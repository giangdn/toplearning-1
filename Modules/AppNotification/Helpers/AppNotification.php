<?php

namespace Modules\AppNotification\Helpers;

use App\User;
use Illuminate\Database\Query\Builder;
use Modules\AppNotification\Entities\AutoSendNotification;

class AppNotification
{
    protected $add_ids;
    protected $title;
    protected $message;
    protected $url;
    protected $image;
    
    protected $send_limit = 20;
    
    public function add($user_id) {
        $this->add_ids[] = $user_id;
    }
    
    public function save() {
        $add_ids = User::whereIn('id', $this->add_ids)
            ->whereExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_app_device_tokens')
                    ->whereColumn('user_id', '=', 'user.id');
            })
            ->pluck('id')
            ->toArray();
        
        $add_ids = array_chunk($add_ids, $this->send_limit);
        
        foreach ($add_ids as $item) {
            $model = new AutoSendNotification();
            $model->fill([
                'user_ids' => implode(',', $item),
                'title' => $this->title,
                'message' => $this->message,
                'url' => $this->url,
                'image' => $this->image,
            ]);
            
            $model->save();
        }
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function setUrl($url) {
        $this->url = $url;
    }
    
    public function setImage($image) {
        $this->image = $image;
    }
}