<?php

namespace Modules\Quiz\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use TorMorten\Eventy\Facades\Events as Eventy;

class QuizServiceProvider extends ServiceProvider
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

        //\Livewire::component('quiz::livewire.attempt', \Modules\Quiz\Http\Livewire\Attempt::class);

        //\Livewire::component('quiz::livewire.attempt.question', \Modules\Quiz\Http\Livewire\Question::class);

        //$this->app['router']->aliasMiddleware('quiz.attempt', 'Modules\Quiz\Http\Middleware\QuizAttempt');
        $this->app['router']->aliasMiddleware('quiz.secondary', 'Modules\Quiz\Http\Middleware\Secondary');

//        $this->registerBackendMenu();
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
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(ConsoleServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('quiz.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'quiz'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/quiz');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/quiz';
        }, \Config::get('view.paths')), [$sourcePath]), 'quiz');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/quiz');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'quiz');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'quiz');
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

    protected function registerBackendMenu() {
        Eventy::addFilter('backend.menu_left', function ($items) {
            $items['quiz'] = [
                'permission' => userCan('quiz') || \Auth::user()->isTeacher(),
                'name' => trans('backend.quiz'),
                'icon' => 'far fa-question-circle menu--icon',
                'items' => [
                    [
                        'name' => trans('backend.questionlib'),
                        'url' => route('module.quiz.questionlib'),
                        'permission' => userCan('quiz-category-question'),
                    ],
                    [
                        'name' => trans('backend.quiz_list'),
                        'url' => route('module.quiz.manager'),
                        'permission' => userCan('quiz')
                    ],
                    [
                        'name' => trans('backend.grading'),
                        'url' => route('module.quiz.grading'),
                        'permission' => \Auth::user()->isTeacher()
                    ],
                    [
                        'name' => trans('backend.user_secondary'),
                        'url' => route('module.quiz.user_secondary'),
                        'permission' => userCan('quiz-user-secondary')
                    ]
                ],
            ];

            return $items;
        }, 1, 50);
    }
}
