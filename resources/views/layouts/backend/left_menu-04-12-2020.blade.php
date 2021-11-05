<nav class="vertical_nav">
    <div class="left_section menu_left" id="js-menu" >
        <div class="left_section">
            <ul>
                <li class="menu--item">
                    <a href="{{ route('module.dashboard') }}" class="menu--link {{ $tabs == 'dashboard' ? 'hover-backend-menu' : '' }}" title="@lang('app.dashboard')">
                        <i class='uil uil-apps menu--icon'></i>
                        <span class="menu--label">@lang('app.dashboard')</span>
                    </a>
                </li>

                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $management) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.management')">
                        <i class='fas fa-tasks menu--icon'></i>
                        <span class="menu--label">@lang('backend.management')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">

                        <li class="sub_menu--item">
                            <a href="{{ route('backend.category') }}" class="sub_menu--link {{ $tabs == 'category' ? 'hover-backend-menu' : '' }}" title="@lang('backend.category')">
                                @lang('backend.category')
                            </a>
                        </li>

                        @can('user')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.backend.user') }}" class="sub_menu--link {{ $tabs == 'user' ? 'hover-backend-menu' : '' }}" title="@lang('backend.user')">
                                @lang('backend.user')
                            </a>
                        </li>
                        @endcan

                        {{--@can('capabilities-review')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.capabilities.review') }}" class="sub_menu--link {{ $tabs == 'module.capabilities.review' ? 'hover-backend-menu' : '' }}" title="@lang('backend.capabilities')">
                                @lang('backend.capabilities')
                            </a>
                        </li>
                        @endcan--}}

                        {{--@can('potential')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.potential.index') }}" class="sub_menu--link {{ $tabs == 'module.potential.index' ? 'hover-backend-menu' : '' }}" title="@lang('backend.potential')">
                                @lang('backend.potential')
                            </a>
                        </li>
                        @endcan--}}

                        {{--@can('convert-titles')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.convert_titles.list_unit') }}" class="sub_menu--link {{ $tabs == 'module.convert_titles.list_unit' ? 'hover-backend-menu' : '' }}" title="@lang('backend.convert_titles_rate')">
                                @lang('backend.convert_titles_rate')
                            </a>
                        </li>
                        @endcan--}}

                        @can('feedback')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.feedback') }}" class="sub_menu--link {{ $tabs == 'feedback' ? 'hover-backend-menu' : '' }}" title="@lang('backend.feedback')">
                                @lang('backend.feedback')
                            </a>
                        </li>
                        @endcan

                        @can('forum')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.forum.category') }}" class="sub_menu--link {{ $tabs == 'forums' ? 'hover-backend-menu' : '' }}" title="@lang('backend.forum')">
                                @lang('backend.forum')
                            </a>
                        </li>
                        @endcan

                        @can('suggest')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.suggest') }}" class="sub_menu--link {{ $tabs == 'suggest' ? 'hover-backend-menu' : '' }}" title="@lang('backend.suggest')">
                                @lang('backend.suggest')
                            </a>
                        </li>
                        @endcan

                        {{--@can('potential-kpi')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.potential.kpi.list_kpi') }}" class="sub_menu--link {{ $tabs == 'module.potential.kpi.list_kpi' ? 'hover-backend-menu' : '' }}" title="@lang('backend.kpi')">
                                @lang('backend.kpi')
                            </a>
                        </li>
                        @endcan--}}

                        <li class="sub_menu--item">
                            <a href="{{ route('backend.evaluationform.manager') }}" class="sub_menu--link {{ in_array($tabs, ['evaluationform', 'rating', 'plan-app']) ? 'hover-backend-menu' : '' }}" title="@lang('backend.evaluation_form')">
                                @lang('backend.evaluation_form')
                            </a>
                        </li>

                        @can('survey')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.survey.index') }}" class="sub_menu--link {{ $tabs == 'survey' ? 'hover-backend-menu' : '' }}" title="@lang('backend.survey')">
                                @lang('backend.survey')
                            </a>
                        </li>
                        @endcan

                        @if(\App\Permission::isUnitManager() || userCan('plan-suggest'))
                        <li class="sub_menu--item">
                            <a href="{{ route('module.plan_suggest') }}" class="sub_menu--link {{ $tabs == 'plan-suggest' ? 'hover-backend-menu' : '' }}" title="@lang('backend.plan_suggest')">
                                @lang('backend.plan_suggest')
                            </a>
                        </li>
                        @endif

                        @can('career-roadmap')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.career_roadmap') }}" class="sub_menu--link {{ $tabs == 'career-roadmap' ? 'hover-backend-menu' : '' }}" title="@lang('career.career_roadmap')">
                                @lang('career.career_roadmap')
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $training) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.training')">
                        <i class='fas fa-chalkboard-teacher menu--icon'></i>
                        <span class="menu--label">@lang('backend.training')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        @can('training-plan')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.training_plan') }}" class="sub_menu--link {{ $tabs == 'training-plan' ? 'hover-backend-menu' : '' }}">@lang('backend.training_plan')</a>
                        </li>
                        @endcan

                        @can('online-course')
                            <li class="sub_menu--item">
                                <a href="{{ route('module.virtualclassroom.index') }}" class="sub_menu--link {{ $tabs == 'virtualclassroom' ? 'hover-backend-menu' : '' }}">@lang('backend.virtual_classroom')</a>
                            </li>
                        @endcan

                        @can('online-course')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.online.management') }}" class="sub_menu--link {{ in_array($tabs, ['online', 'course'])  ? 'hover-backend-menu' : '' }}">@lang('backend.online_course')</a>
                        </li>
                        @endcan

                        @can('offline-course')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.offline.management') }}" class="sub_menu--link {{ in_array($tabs, ['offline', 'course']) ? 'hover-backend-menu' : '' }}">@lang('backend.offline_course')</a>
                        </li>
                        @endcan

                       {{-- @can('training-unit')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.training_unit') }}" class="sub_menu--link {{ $tabs == 'module.training_unit' ? 'hover-backend-menu' : '' }}">@lang('backend.training_unit')</a>
                        </li>
                        @endcan--}}

                        @can('indemnify')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.indemnify') }}" class="sub_menu--link {{ $tabs == 'module.indemnify' ? 'hover-backend-menu' : '' }}">@lang('backend.indemnify')</a>
                        </li>
                        @endcan

                        @can('certificate-template')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.certificate') }}" class="sub_menu--link {{ $tabs == 'module.certificate' ? 'hover-backend-menu' : '' }}">@lang('backend.certificate')</a>
                        </li>
                        @endcan

                        <li class="sub_menu--item">
                            <a href="{{ route('module.trainingroadmap') }}" class="sub_menu--link {{ $tabs == 'module.trainingroadmap' ? 'hover-backend-menu' : '' }}">@lang('backend.trainingroadmap')</a>
                        </li>

                        @can('plan-app')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.plan_app.course') }}" class="sub_menu--link {{ $tabs == 'module.plan_app.course' ? 'hover-backend-menu' : '' }}">@lang('backend.plan_app')</a>
                        </li>
                        @endcan
                    </ul>
                </li>

                @if(userCan('quiz') || Auth::user()->isTeacher())
                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $quiz) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.quiz')">
                        <i class='far fa-question-circle menu--icon'></i>
                        <span class="menu--label">@lang('backend.quiz')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        @can('quiz-category-question')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.quiz.questionlib') }}" class="sub_menu--link {{ $tabs == 'module.quiz.questionlib' ? 'hover-backend-menu' : '' }}">@lang('backend.questionlib')</a>
                        </li>
                        @endcan

                        @can('quiz')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.quiz.manager') }}" class="sub_menu--link {{ $tabs == 'module.quiz.manager' ? 'hover-backend-menu' : '' }}">@lang('backend.quiz_list')</a>
                        </li>
                        @endcan

                        @if(Auth::user()->isTeacher())
                        <li class="sub_menu--item">
                            <a href="{{ route('module.quiz.grading') }}" class="sub_menu--link {{ $tabs == 'module.quiz.grading' ? 'hover-backend-menu' : '' }}">@lang('backend.grading')</a>
                        </li>
                        @endcan

                        @can('quiz-user-secondary')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.quiz.user_secondary') }}" class="sub_menu--link {{ $tabs == 'module.quiz.user_secondary' ? 'hover-backend-menu' : '' }}">@lang('backend.user_secondary')</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('libraries')
                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $library) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.library')">
                        <i class='fas fa-book menu--icon'></i>
                        <span class="menu--label">@lang('backend.library')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        @can('libraries-book')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.libraries.book') }}" class="sub_menu--link {{ $tabs == 'module.libraries.book' ? 'hover-backend-menu' : '' }}">@lang('backend.book')</a>
                        </li>
                        @endcan

                        @can('libraries-ebook')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.libraries.ebook') }}" class="sub_menu--link {{ $tabs == 'module.libraries.ebook' ? 'hover-backend-menu' : '' }}">@lang('backend.ebook')</a>
                        </li>
                        @endcan

                        @can('libraries-document')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.libraries.document') }}" class="sub_menu--link {{ $tabs == 'module.libraries.document' ? 'hover-backend-menu' : '' }}">@lang('backend.document')</a>
                        </li>
                        @endcan

                        @can('libraries-video')
                            <li class="sub_menu--item">
                                <a href="{{ route('module.libraries.video') }}" class="sub_menu--link {{ $tabs == 'module.libraries.video' ? 'hover-backend-menu' : '' }}">Video</a>
                            </li>
                        @endcan

                        @can('libraries-category')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.libraries.category') }}" class="sub_menu--link {{ $tabs == 'module.libraries.category' ? 'hover-backend-menu' : '' }}">@lang('backend.category')</a>
                        </li>
                        @endcan

                        @can('libraries-book-register')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.libraries.book.register') }}" class="sub_menu--link {{ $tabs == 'module.libraries.book.register' ? 'hover-backend-menu' : '' }}">@lang('backend.book_register')</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('news')
                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $news) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.news')">
                        <i class="far fa-newspaper menu--icon"></i>
                        <span class="menu--label">@lang('backend.news')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        @can('news-list')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.news.manager') }}" class="sub_menu--link {{ $tabs == 'module.news.manager' ? 'hover-backend-menu' : '' }}">@lang('backend.news_list')</a>
                        </li>
                        @endcan

                        @can('news-category')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.news.category') }}" class="sub_menu--link {{ $tabs == 'module.news.category' ? 'hover-backend-menu' : '' }}">@lang('backend.category')</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('promotion')
                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $promotion) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="{{ trans('backend.study_promotion_program') }}">
                        <i class='fas fa-gift menu--icon'></i>
                        <span class="menu--label">@lang('backend.study_promotion_program')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        @can('promotion')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.promotion') }}" class="sub_menu--link {{ $tabs == 'module.promotion' ? 'hover-backend-menu' : '' }}">@lang('backend.promotions')</a>
                        </li>
                        @endcan

                        @can('promotion-group')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.promotion.group') }}" class="sub_menu--link {{ $tabs == 'module.promotion.group' ? 'hover-backend-menu' : '' }}">@lang('backend.promotion_category_group')</a>
                        </li>
                        @endcan

                        @can('promotion-level')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.promotion.level') }}" class="sub_menu--link {{ $tabs == 'module.promotion.level' ? 'hover-backend-menu' : '' }}">@lang('backend.user_level_setting')</a>
                        </li>
                        @endcan

                        @can('promotion-purchase-history')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.promotion.orders.buy') }}" class="sub_menu--link {{ $tabs == 'module.promotion.orders.buy' ? 'hover-backend-menu' : '' }}">@lang('backend.purchase_history')</a>
                        </li>
                        @endcan

                        {{--<li class="sub_menu--item">
                            <a href="" class="sub_menu--link">@lang('backend.received_history')</a>
                        </li>--}}

                    </ul>
                </li>
                @endcan

                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $daily_training) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.training_video')">
                        <i class='uil uil-video menu--icon'></i>
                        <span class="menu--label">@lang('backend.training_video')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        <li class="sub_menu--item">
                            <a href="{{ route('module.daily_training') }}" class="sub_menu--link {{ $tabs == 'module.daily_training' ? 'hover-backend-menu' : '' }}" title="@lang('backend.training_video')">
                                @lang('backend.training_video')
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('module.daily_training.score_views') }}" class="sub_menu--link {{ $tabs == 'module.daily_training.score_views' ? 'hover-backend-menu' : '' }}" title="@lang('backend.setting_views')">
                                @lang('backend.setting_views')
                            </a>
                        </li>

                        <li class="sub_menu--item">
                            <a href="{{ route('module.daily_training.score_like') }}" class="sub_menu--link {{ $tabs == 'module.daily_training.score_like' ? 'hover-backend-menu' : '' }}" title="@lang('backend.setting_like')">
                                @lang('backend.setting_like')
                            </a>
                        </li>

                        <li class="sub_menu--item">
                            <a href="{{ route('module.daily_training.score_comment') }}" class="sub_menu--link {{ $tabs == 'module.daily_training.score_comment' ? 'hover-backend-menu' : '' }}" title="@lang('backend.setting_comment')">
                                @lang('backend.setting_comment')
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $permission) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.permission')">
                        <i class='fas fa-user-tag menu--icon'></i>
                        <span class="menu--label">@lang('backend.permission')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        @can('role')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.roles') }}" class="sub_menu--link {{ $tabs == 'backend.roles' ? 'hover-backend-menu' : '' }}">@lang('backend.role')</a>
                        </li>
                        @endcan
                        @can('permission-group')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.permission.type') }}" class="sub_menu--link {{ $tabs == 'module.permission.type' ? 'hover-backend-menu' : '' }}">@lang('backend.permission_group')</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                <li class="menu--item menu--item__has_sub_menu {{ in_array($tabs, $setting) ? 'menu--subitens__opened' : '' }}">
                    <label class="menu--link" title="@lang('backend.setting')">
                        <i class='uil uil-cog menu--icon'></i>
                        <span class="menu--label">@lang('backend.setting')</span>
                        <i class="fa fa-chevron-down"></i>
                    </label>
                    <ul class="sub_menu">
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.config') }}" class="sub_menu--link {{ $tabs == 'backend.config' ? 'hover-backend-menu' : '' }}">Cài đặt chung</a>
                        </li>

                        <li class="sub_menu--item">
                            <a href="{{ route('backend.config.refer') }}" class="sub_menu--link {{ $tabs == 'backend.config.refer' ? 'hover-backend-menu' : '' }}">Điểm giới thiệu</a>
                        </li>
                        @can('footer')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.footer') }}" class="sub_menu--link {{ $tabs == 'backend.footer' ? 'hover-backend-menu' : '' }}">Footer</a>
                        </li>
                        @endcan

                        <li class="sub_menu--item">
                            <a href="{{ route('backend.login_image') }}" class="sub_menu--link {{ $tabs == 'backend.login_image' ? 'hover-backend-menu' : '' }}">{{ data_locale('Hình nền đăng nhập', 'Background login') }}</a>
                        </li>

                        @can('logo')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.logo') }}" class="sub_menu--link {{ $tabs == 'backend.logo' ? 'hover-backend-menu' : '' }}">Logo</a>
                        </li>
                        @endcan

                        @can('favicon')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.logo.favicon') }}" class="sub_menu--link {{ $tabs == 'backend.logo.favicon' ? 'hover-backend-menu' : '' }}">Favicon</a>
                        </li>
                        @endcan

                        @if(Auth::user()->isAdmin())
                            <li class="sub_menu--item">
                                <a href="{{ route('backend.app_mobile') }}" class="sub_menu--link {{ $tabs == 'backend.app_mobile' ? 'hover-backend-menu' : '' }}">App Mobile</a>
                            </li>
                        @endif

                        @can('notify')
                        <li class="sub_menu--item">
                            <a href="{{ route('module.notify_send') }}" class="sub_menu--link {{ $tabs == 'module.notify_send' ? 'hover-backend-menu' : '' }}">@lang('backend.notify')</a>
                        </li>
                        @endcan

                        @can('mail-template')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.mailtemplate') }}" class="sub_menu--link {{ $tabs == 'backend.mailtemplate' ? 'hover-backend-menu' : '' }}">@lang('backend.mailtemplate')</a>
                        </li>
                        @endcan

                        @can('mail-template-history')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.mailhistory') }}" class="sub_menu--link {{ $tabs == 'backend.mailhistory' ? 'hover-backend-menu' : '' }}">@lang('backend.mailhistory')</a>
                        </li>
                        @endcan

                        @can('guide')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.guide') }}" class="sub_menu--link {{ $tabs == 'backend.guide' ? 'hover-backend-menu' : '' }}">@lang('backend.guide')</a>
                        </li>
                        @endcan

                        @can('banner')
                        <li class="sub_menu--item">
                            <a href="{{ route('backend.slider') }}" class="sub_menu--link {{ $tabs == 'backend.slider' ? 'hover-backend-menu' : '' }}">Banner</a>
                        </li>
                        @endcan

                        <li class="sub_menu--item">
                            <a href="{{ route('backend.donate_points') }}" class="sub_menu--link {{ $tabs == 'backend.donate_points' ? 'hover-backend-menu' : '' }}">@lang('backend.donate_points')</a>
                        </li>

                            <li class="sub_menu--item">
                                <a href="{{ route('module.faq') }}" class="sub_menu--link {{ $tabs == 'module.faq' ? 'hover-backend-menu' : '' }}">@lang('backend.faq')</a>
                            </li>
                    </ul>
                </li>
                @can('report')
                <li class="menu--item">
                    <a href="{{ route('module.report') }}" class="menu--link {{ $tabs == 'module.report' ? 'hover-backend-menu' : '' }}" title="@lang('backend.report')">
                        <i class='uil uil-clipboard-alt menu--icon'></i>
                        <span class="menu--label">@lang('backend.report')</span>
                    </a>
                </li>
                @endcan
                {{--@if(Module::has('TrainingAction'))
                <li class="menu--item">
                    <a href="{{ route('module.training_action') }}" class="menu--link {{ $tabs == 'module.training_action' ? 'hover-backend-menu' : '' }}" title="@lang('backend.training_action')">
                        <i class='uil uil-home-alt menu--icon'></i>
                        <span class="menu--label">@lang('backend.training_action')</span>
                    </a>
                </li>
                @endif--}}
            </ul>
        </div>
    </div>
</nav>
