<ul class="navbar-nav ml-auto ul-custom">

    @if(\App\Permission::showMenuManager())
        <li class="nav-item dropdown">
            <a id="managementDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" v-pre> {{ trans('backend.management') }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="managementDropdown">
                    <a class="dropdown-item" href="{{ route('backend.category') }}"><i class="fa fa-tasks"></i> {{ trans('backend.category') }}</a>
                    <a class="dropdown-item" href="{{ route('module.backend.user') }}"><i class="fa fa-user"></i> {{ trans('backend.user') }}</a>
                    <a class="dropdown-item" href="{{ route('module.backend.manager_level') }}"><i class="fa fa-user"></i> {{ trans('backend.manager_level') }}</a>
                    <a class="dropdown-item" href="{{ route('module.capabilities.review') }}"><i class="fa fa-road"></i> {{ trans('backend.capabilities') }}</a>
                    <a class="dropdown-item" href="{{ route('module.new_recruitment') }}"><i class="fa fa-user-plus"></i> {{ trans('backend.new_recruitment') }}</a>
                    <a class="dropdown-item" href="{{ route('module.potential.index') }}"><i class="fa fa-user-secret"></i> {{ trans('backend.potential') }}</a>
                    <a class="dropdown-item" href="{{ route('module.convert_titles') }}"><i class="fa fa-exchange"></i> {{ trans('backend.convert_titles') }}</a>

                @if(\App\Permission::isUnitManager())
                    <a class="dropdown-item" href="{{ route('module.convert_titles.list_unit') }}"><i class="fa fa-exchange"></i> {{ trans('backend.convert_titles_rate') }}</a>
                @endif

                    <a class="dropdown-item" href="{{ route('backend.feedback') }}"><i class="fa fa-retweet"></i> {{ trans('backend.feedback') }}</a>
                    <a class="dropdown-item" href="{{ route('module.suggest') }}"><i class="fa fa-filter"></i> {{ trans('backend.suggest') }}</a>
                    <a class="dropdown-item" href="{{ route('module.forum.category') }}"><i class="fa fa-envelope"></i> {{ trans('backend.forum') }}</a>
                    <a class="dropdown-item" href="{{ route('module.potential.kpi.list_kpi') }}"><i class="fa fa-list-ul"></i> {{ trans('backend.kpi') }}</a>

                @if (!\App\Permission::isUnitManager())
                    <a class="dropdown-item" href="{{ route('backend.evaluationform.manager') }}"><i class="fa fa-life-ring"></i> {{ trans('backend.evaluation_form') }}</a>
{{--                    <a class="dropdown-item" href="{{ route('backend.ihrp') }}"><i class="fa fa-list-alt"></i> Xuất file IHRP </a>--}}
                @endif
                    <a class="dropdown-item" href="{{ route('module.survey.index') }}"><i class="fa fa-edit"></i> {{ trans('backend.survey') }}</a>

                @if(\App\Permission::isAdmin() || \App\Permission::isUnitManager())
                    <a class="dropdown-item" href="{{ route('module.plan_suggest') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.plan_suggest') }}</a>
                @endif
            </div>
        </li>
    @endif

    @if(\App\Permission::showMenuTraining())
        <li class="nav-item dropdown">
            <a id="courseDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" v-pre> {{ trans('backend.training') }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="courseDropdown">
                    <a class="dropdown-item" href="{{ route('module.training_plan') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.training_plan') }}</a>
                    <a class="dropdown-item" href="{{ route('module.online.management') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.online_course') }}</a>
                    <a class="dropdown-item" href="{{ route('module.offline.management') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.offline_course') }}</a>
                    <a class="dropdown-item" href="{{ route('module.manager_course') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.manager_course') }}</a>
                    <a class="dropdown-item" href="{{ route('module.training_unit') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.training_unit') }}</a>
                    <a class="dropdown-item" href="{{ route('module.indemnify') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.indemnify') }}</a>

                @if (!\App\Permission::isUnitManager())
                    <a class="dropdown-item" href="{{ route('module.certificate') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.certificate') }}</a>
                    <a class="dropdown-item" href="{{ route('module.trainingroadmap.list') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.trainingroadmap') }}</a>
                @endif

                <a class="dropdown-item" href="{{ route('module.plan_app.course') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.plan_app') }}</a>
            </div>
        </li>
    @endif

    @if (\App\Permission::showMenuQuiz())
        <li class="nav-item dropdown">
            <a id="quizDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" v-pre>{{ trans('backend.quiz') }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="quizDropdown">
                    <a class="dropdown-item" href="{{ route('module.quiz.questionlib') }}"><i class="fa fa-list"></i> {{ trans('backend.questionlib') }}</a>
                    <a class="dropdown-item" href="{{ route('module.quiz.manager') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.quiz_list') }}</a>
                    <a class="dropdown-item" href="{{ route('module.quiz.grading') }}"><i class="fa fa-edit"></i> {{ trans('backend.grading') }}</a>
                    <a class="dropdown-item" href="{{ route('module.quiz.user_secondary') }}"><i class="fa fa-user"></i> {{ trans('backend.user_secondary') }}</a>
            </div>
        </li>
    @endif
    @if (\App\Permission::isAdmin() || \App\Permission::isUnitManager())
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('module.report') }}"> {{ trans('backend.report') }} </a>
        </li>
    @endif
    @if (\App\Permission::isAdmin())
        @if (\App\Permission::showMenuNews())
            <li class="nav-item dropdown">
                <a id="newsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" v-pre>{{ trans('backend.news') }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="newsDropdown">
                        <a class="dropdown-item" href="{{ route('module.news.manager') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.news_list') }}</a>
                        <a class="dropdown-item" href="{{ route('module.news.category') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.category') }}</a>
                </div>
            </li>
        @endif
        @if (\App\Permission::showMenuLibraries())
            <li class="nav-item dropdown">
                <a id="librariesDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" v-pre>{{ trans('backend.library') }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="librariesDropdown">
                        <a class="dropdown-item" href="{{ route('module.libraries.book') }}"><i class="fa fa-book"></i>
                            {{ trans('backend.book') .' ('. \Modules\Libraries\Entities\Libraries::countBookByStatus() . '/' . \Modules\Libraries\Entities\Libraries::countAllBook() . ')' }}</a>
                        <a class="dropdown-item" href="{{ route('module.libraries.ebook') }}"><i class="fa fa-book"></i> Ebook ({{ \Modules\Libraries\Entities\Libraries::countEBookByStatus() . '/' . \Modules\Libraries\Entities\Libraries::countAllEBook() }}) </a>
                        <a class="dropdown-item" href="{{ route('module.libraries.document') }}"><i class="fa fa-address-book"></i> {{ trans('backend.document') .' ('. \Modules\Libraries\Entities\Libraries::countDocByStatus() . '/' . \Modules\Libraries\Entities\Libraries::countAllDoc() .')' }}</a>
                        <a class="dropdown-item" href="{{ route('module.libraries.category') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.category') }}</a>
                        <a class="dropdown-item" href="{{ route('module.libraries.book.register') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.book_register') }}</a>
                </div>
            </li>
        @endif
        @if(\App\Permission::showMenuSetting())
            <li class="nav-item dropdown">
                <a id="settingDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ trans('backend.setting') }} <span class="caret"></span></a>

                <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="settingDropdown">
                    <a class="dropdown-item" href="{{ route('backend.permission.list_permisstion') }}"><i class="fa fa-users"></i> {{ trans('backend.permisstion') }}</a>
                        <a class="dropdown-item" href="{{ route('backend.slider') }}"><i class="fa fa-sliders"></i> Slider</a>
                    <a class="dropdown-item" href="{{ route('backend.footer') }}"><i class="fa fa-sliders"></i> Footer</a>
                        <a class="dropdown-item" href="{{ route('module.notify_send') }}"><i class="fa fa-bell"></i> {{ trans('backend.notify') }}</a>
                        <a class="dropdown-item" href="{{ route('backend.mailtemplate') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.mailtemplate') }}</a>
                        <a class="dropdown-item" href="{{ route('backend.mailhistory') }}"><i class="fa fa-list-alt"></i> {{ trans('backend.mailhistory') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.guide') }}"><i class="fa fa-cog"></i> {{ trans('backend.guide') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.parameter') }}"><i class="fa fa-cog"></i> Thông số</a>
                </div>
            </li>
        @endif
        @if(array_key_exists('Promotion',Module::allEnabled()))
            <li class="nav-item dropdown">
                <a id="settingDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Chương trình Quà tặng <span class="caret"></span></a>

                    <div class="dropdown-menu dropdown-menu-left dropdown-custom" aria-labelledby="settingDropdown">
                        <a class="dropdown-item" href="{{ route('module.promotion') }}"><i class="fa fa-sliders"></i> Chương trình Quà tặng</a>
                        <a class="dropdown-item" href="{{ route('module.promotion.group') }}"><i class="fa fa-sliders"></i> {{ trans('backend.promotion_category_group') }}</a>
                        <a class="dropdown-item" href="{{ route('module.promotion.level') }}"><i class="fa fa-sliders"></i> Cài đặt cấp bật người dùng</a>
                        <a class="dropdown-item" href="{{ route('module.promotion.orders.buy') }}"><i class="fa fa-history"></i> Lịch sử mua</a>
                        <a class="dropdown-item" href="{{ route('module.notify_send') }}"><i class="fa fa-history"></i> Lịch sử nhận</a>
                    </div>
                </li>
            @endif
    @endif
</ul>
