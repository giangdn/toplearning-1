<?php

namespace Modules\Online\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Modules\Online\Events\CourseCompleted::class => [
            \Modules\Online\Listeners\UserCompletePoint::class
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
