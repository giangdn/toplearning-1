<?php

namespace Modules\Notify\Entities;

use App\CacheModel;
// use Illuminate\Database\Eloquent\Model;
use Modules\AppNotification\Helpers\AppNotification;

class Notify extends CacheModel
{
    protected $table = 'el_notify';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'subject',
        'content',
        'url',
        'created_by',
        'type',
        'important',
    ];

    public $users;

    public function addMultiNotify()
    {
        if (empty($this->users) || empty($this->subject) || empty($this->content)) {
            return false;
        }

        try {
            foreach ($this->users as $user) {
                $model = new Notify();
                $model->user_id = $user;
                $model->subject = $this->subject;
                $model->content = $this->content;
                $model->url = $this->url;
                $model->created_by = empty($this->created_by) ? 0 : $this->created_by;
                $model->save();

                $content = \Str::words(html_entity_decode(strip_tags($this->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $model->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($this->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add($user);
            }
            $notification->save();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
