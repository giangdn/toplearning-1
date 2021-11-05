<?php

namespace Modules\Quiz\Providers;

use Illuminate\Support\ServiceProvider;
use TorMorten\Eventy\Facades\Events as Eventy;

class MenuServiceProvider extends ServiceProvider
{
    public function boot() {
        Eventy::addFilter('backend.menu_left', [$this, 'registerBackendMenu'], 10, 1);
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

    public function registerBackendMenu($items) {
        return $items;
    }
}
