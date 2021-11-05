<?php

namespace Modules\Online\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Console\Scheduling\Schedule;

class OnlineServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

//        $this->app->booted(function () {
//            $schedule = $this->app->make(Schedule::class);
//            $schedule->command('online:unzip-scorm')->everyMinute()->withoutOverlapping();
//            $schedule->command('online:activity-complete')->everyMinute()->withoutOverlapping();
//            $schedule->command('online:complete')->everyMinute();
//        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->commands([
            \Modules\Online\Console\ActivityComplete::class,
            \Modules\Online\Console\OnlineComplete::class,
            \Modules\Online\Console\UnzipScorm::class,
        ]);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('online.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'online'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/online');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/online';
        }, \Config::get('view.paths')), [$sourcePath]), 'online');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/online');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'online');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'online');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
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
}
