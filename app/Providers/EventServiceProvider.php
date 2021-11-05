<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\LoginSuccess::class => [
            \App\Listeners\LoginHistory::class
        ],
        \App\Events\SaveOfflineScore::class => [
            \App\Listeners\UserPromotionPoint::class,
        ],
        \App\Events\Online\GoActivity::class => [
            \App\Listeners\Online\ActivityHistory::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
            'SocialiteProviders\\Azure\\AzureExtendSocialite@handle',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
