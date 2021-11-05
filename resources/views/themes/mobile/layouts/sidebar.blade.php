{{--<div class="row no-gutters vh-100 loader-screen">
    <div class="col align-self-center text-white text-center">
        <img src="{{ image_file(\App\Config::getLogo()) }}" alt="logo" class="header-logo">
    </div>
</div>--}}

<div class="sidebar">
    <div class="pt-1 pb-2 mb-1 bg-white">
        <div class="row">
            <div class="col text-center">
                @if(!userThird())
                <img src="{{ image_file(\App\Config::getLogo()) }}" alt="" class="header-logo">
                @endif
            </div>
           {{-- <a href="javascript:void(0)" class="closesidemenu"><i class="material-icons text-white bg-danger">close</i></a>--}}
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="list-group main-menu">
                @if(!userThird() && \App\User::canPermissionTrainingOrganization())
                <a href="{{ route('themes.mobile.frontend.approve_course.course') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/approve.png') }}" alt="" class="icons-raised"> @lang('app.approve_register')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                @endif
                @if(\App\Permission::isTeacher())
                <a href="{{ route('frontend.attendance') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/attendance.png') }}" alt="" class="icons-raised"> Scan @lang('app.attendance') (In house)
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                @endif
                {{-- <a href="{{ route('module.news') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/news.png') }}" alt="" class="icons-raised"> @lang('app.news_mobile')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                <a href="{{ route('module.libraries') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/library.png') }}" alt="" class="icons-raised"> @lang('app.library')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                <a href="{{ route('module.frontend.forums') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/forum.png') }}" alt="" class="icons-raised"> @lang('app.forum')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a> --}}
                @if(\App\Profile::usertype() != 2)
                <a href="{{ route('module.daily_training.frontend') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/video.png') }}" alt="" class="icons-raised"> @lang('backend.training_video')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                @endif
                {{--<a href="{{ route('frontend.plan_app') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/plan.png') }}" alt="" class="icons-raised"> @lang('app.action_plan')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>--}}

                {{-- <a href="{{ route('themes.mobile.frontend.training_process') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/history.png') }}" alt="" class="icons-raised"> @lang('app.history')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a> --}}

                <a href="{{ route('module.career_roadmap.frontend') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/career_path.png') }}" alt="" class="icons-raised"> @lang('app.career_path')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                {{--<a href="javascript:void(0)" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#settings">
                    <img src="{{ asset('themes/mobile/img/settings.png') }}" alt="" class="icons-raised"> @lang('app.settings')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>--}}

                {{-- <a href="{{ route('module.front.promotion') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/promotion.png') }}" alt="" class="icons-raised"> @lang('app.promotion')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a> --}}

                {{-- @if(\App\Permission::isAdmin() || \App\Permission::isUnitManager())
                <a href="{{ route('themes.mobile.frontend.manager') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/head_of_department.png') }}" alt="" class="icons-raised"> @lang('app.head_of_department')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                @endif --}}
                <a href="{{ route('module.faq.frontend.index') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/faq.png') }}" alt="" class="icons-raised"> @lang('app.faq')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                @if(!userThird())
                <a href="{{ route('frontend.guide') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/guide.png') }}" alt="" class="icons-raised"> @lang('app.guide')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                @endif
                <!-- <a href="javascript:void(0)" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#colorscheme">
                    <img src="{{ asset('themes/mobile/img/color_scheme.png') }}" alt="" class="icons-raised"> @lang('app.color_scheme')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a> -->
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">
                    <img src="{{ asset('themes/mobile/img/logout.png') }}" alt="" class="icons-raised"> @lang('app.logout')
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
            </div>
        </div>
    </div>
</div>
