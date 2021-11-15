<?php

namespace App\Providers;

use App\Helpers\Tracking;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

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

        $isDebug = config('app.debug', false);

        if ($isDebug) {
            // app events depend on the eloquent trigger
            Event::listen('eloquent.*', function ($eventName) {
                Tracking::put((object) ['event' => $eventName], 'app');
            });

            // layout init events
            Event::listen('creating:*', function ($eventName) {
                Tracking::put((object) ['event' => $eventName], 'lay');
            });

            // layout compose events
            Event::listen('composing:*', function ($eventName) {
                Tracking::put((object) ['event' => $eventName], 'lay');
            });
        }
    }
}
