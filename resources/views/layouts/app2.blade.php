<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title')</title>
    <meta property="og:title" content="@yield('page_title')">
    <meta property="og:description" content="@yield('page_title')">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/custom_mobile.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/fontawesom/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href='{{ asset('css/font_Source_Sans_Pro_400_700_600_300.css') }}'>
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bootstrap-table/bootstrap-table.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bxslider/jquery.bxslider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/select2/select2.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/styles.css') }}">

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>

    <script type="text/javascript" src="{{ asset('styles/js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/css/backend/styles/js/gdropdown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap/js/popper.min.js') }}"></script>
    {{--<script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-table/bootstrap-table-vi-VN.js') }}"></script>

    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-table/bootstrap-table-en-US.js') }}"></script>

    {{-- <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.vi.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('styles/vendor/bxslider/jquery.bxslider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/sweetalert2/sweetalert2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/select2/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('styles/js/load-ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/js/LoadBootstrapTable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/js/form-ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/js/ListView.js') }}"></script>

    @yield('header')
</head>
<body>
<header>
    <div class="head-header">
        <div class="lang">
            <a href="{{ route('change_language', ['language' => 'vi']) }}">Viet Nam</a> / <a href="{{ route('change_language', ['language' => 'en']) }}">English</a>
        </div>
        <ul class="menu-top">
            <li>
                <a href="{{ route('module.notify.index') }}"><i class="fa fa-bell"></i>&nbsp; {{ trans('app.notify') }} <font style="background: #8c8e90; padding: 5px; border-radius: 10px;">{{ \Modules\Notify\Entities\NotifySend::countMessage() }}</font></a>
            </li>

            @guest
                <li><a href="{{ route('login') }}"><i class="fa fa-user"></i> {{ trans('app.login') }}</a></li>
            @else
                <li class="sub-item"><a class="show-sub" href="#" data-mid="sub-menu-top1"><i class="fa fa-user"></i> &nbsp;{{ \App\Profile::fullname() }}</a>
                    <ul class="sub-menus" id="sub-menu-top1">

                        <li onclick="window.location='{{ route('frontend.my_course') }}'">
                            <a href="{{ route('frontend.my_course') }}"><i class="fa fa-tasks"></i> {{ trans('app.my_course') }} </a>
                        </li>

                        <li onclick="window.location='{{ route('module.online') }}'">
                            <a href="{{ route('module.online') }}"><i class="fa fa-adjust"></i> {{ trans('app.onl_course') }} </a>
                        </li>

                        <li onclick="window.location='{{ route('module.user.dashboard') }}'">
                            <a href="{{ route('module.user.dashboard') }}"><i class="fa fa-train"></i> {{ trans('app.dashboard') }} </a>
                        </li>

                        <li onclick="window.location='{{ route('module.frontend.user.info') }}'">
                            <a href="{{ route('module.frontend.user.info') }}"><i class="fa fa-user"></i> {{ trans('app.user_info') }} </a>
                        </li>

                        <li onclick="window.location='{{ route('frontend.plan_app') }}'">
                            <a href="{{ route('frontend.plan_app') }}"><i class="fa fa-paper-plane"></i> {{ trans('app.action_plan') }} </a>
                        </li>

                        <li onclick="window.location='{{ route('module.new_recruitment.evaluate_employees_list') }}'">
                            <a href="{{ route('module.new_recruitment.evaluate_employees_list') }}"><i class="fa fa-clipboard"></i> {{ trans('app.employee_probation_report') }} </a>
                        </li>

                            <li onclick="window.location='{{ route('module.new_recruitment.evaluate_manager_list') }}'">
                                <a href="{{ route('module.new_recruitment.evaluate_manager_list') }}"><i class="fa fa-clipboard"></i> {{ trans('app.management_probation_report') }} </a>
                            </li>

                        @if(\App\Permission::hasPermission() || \App\Permission::hasPermissionUnit())
                            <li onclick="window.location='{{ route('backend.dashboard') }}'">
                                <a href="{{ route('backend.dashboard') }}"> <i class="fa fa-tachometer"></i> {{ trans('app.admin_page') }} </a>
                            </li>
                        @endif

                        <li onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><a href=""><i class="fa fa-sign-out"></i> {{ trans('app.logout') }} </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest

            <li><a href="{{ route('frontend.guide') }}"><i class="fa fa-newspaper"></i> &nbsp;{{ trans('app.guide') }}</a></li>
            {{--            <li><a href="{{ route('frontend.map') }}"><i class="fa fa-sitemap"></i> &nbsp;Sơ đồ web</a></li>--}}
        </ul>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light bg-faded main-menu nav-desktop">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/"><img src="{{ asset('styles/images/logo.png') }}" alt="" width="100px"/></a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto @if(!Auth::check()) d-none @endif">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('frontend.home') }}"> {{ trans('app.home') }} </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link submenu" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
                        {{ trans('app.course') }} <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2" style="top: unset;left: unset;">
                        <a class="dropdown-item" href="{{ route('module.online') }}"><i class="fa fa-globe"></i> {{ trans('app.onl_course') }} </a>
                        <a class="dropdown-item" href="{{ route('module.offline') }}"><i class="fa fa-map-marker"></i> {{ trans('app.off_course') }} </a>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('module.quiz') }}"> {{ trans('app.quiz') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('module.libraries') }}"> {{ trans('app.libraries') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('module.frontend.forums') }}"> {{ trans('app.forum') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('module.suggest.index') }}"> {{ trans('app.suggest') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('module.news') }}"> {{ trans('app.news') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('module.survey') }}"> {{ trans('app.survey') }} </a></li>
            </ul>
        </div>
    </nav>

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded main-menu nav-mobile">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContentMobile" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/"><img src="{{ asset('styles/images/logo.png') }}" alt=""/></a>
        <div class="collapse navbar-collapse" id="navbarSupportedContentMobile">
            <p style="text-align: right;font-size: 20px;"><a href="javascript:void(0)" style="color: black;" id="close-menu"><i class="fa fa-times-circle"></i></a></p>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('frontend.my_course') }}"><i class="fa fa-book"></i> {{ trans('app.my_course') }} </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('module.notify.index') }}"><i class="fa fa-bell"></i> {{ trans('app.notify') }} </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link submenu" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><i class="fa fa-graduation-cap"></i> {{ trans('app.course') }} <i class="fa fa-angle-down"></i></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('module.online') }}"> {{ trans('app.onl_course') }} </a>
                        <a class="dropdown-item" href="{{ route('module.offline') }}"> {{ trans('app.off_course') }} </a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('module.libraries') }}"><i class="fa fa-list-alt"></i> {{ trans('app.libraries') }} </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('module.quiz') }}"><i class="fa fa-pencil-square-o"></i> {{ trans('app.quiz') }} </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('module.news') }}"><i class="fa fa-hacker-news"></i> {{ trans('app.news') }} </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('module.frontend.forums') }}"><i class="fa fa-foursquare"></i>&nbsp; {{ trans('app.forum') }} </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('module.frontend.user.info') }}"><i class="fa fa-user"></i> {{ trans('app.user_info') }} </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.plan_app') }}"><i class="fa fa-paper-plane"></i> {{ trans('app.action_plan') }} </a>
                </li>

                <li class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <a class="nav-link" href="javascript:void(0)"><i class="fa fa-sign-out"></i> {{ trans('app.logout') }} </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main>
    @yield('content')
</main>

<div id="app-modal"></div>
<a href="#" id="back-to-top" title="Back to top">&uarr;</a>

<script src="{{ asset('styles/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('styles/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.vi.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('styles/vendor/bootstrap-datepicker/js/load-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('styles/js/load-select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('styles/js/frontend/script.js') }}" type="text/javascript"></script>

<footer>
    @php
        $footer = \App\Footer::where('status', '=', 1)->first();
    @endphp
    <div class="container-fluid">
        <div class="row row-bottom">
            <div class="container wrap-footer">
                <div class="row">
                    <div class="col-12 col-md-4 col-sm-6">
                        <div class="title"> {{ trans('app.contact') }}</div>
                        <div class="" style="font-weight: 700; font-size: 14px;"> {{ $footer ? $footer->name : '' }}</div>
                        <div class=""></div>
                        <div class=""></div>
                        <div class=""> {{ $footer && $footer->email ? 'Email: '. $footer->email : '' }} </div>
                    </div>
                    <div class="col-12 col-md-4 col-sm-6">
                        <div>
                            <p class="title"> {{ trans('app.link') }} </p>
                            <a href="{{ route('module.news') }}"> {{ trans('app.news') }} </a>
                        </div>
                        <div>
                            <a href="{{ route('module.online') }}"> {{ trans('app.onl_course') }} </a>
                        </div>
                        <div>
                            <a href="{{ route('module.offline') }}"> {{ trans('app.off_course') }} </a>
                        </div>
                        <div>
                            <a href="{{ route('module.frontend.forums') }}"> {{ trans('app.forum') }} </a>
                        </div>
                        <div>
                            <a href="{{ route('module.libraries') }}"> {{ trans('app.libraries') }} </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-sm-6">
                        <p class="title"> {{ trans('app.connect_with_us') }} </p>
                        <div class="form-group">
                            <a href="{{ $footer->link_youtobe }}" target="_tbank"><img src="{{ asset('/styles/images/youtube.png') }}"></a>
                            <a href="{{ $footer->link_fb }}" target="_tbank"><img src="{{ asset('/styles/images/face.png') }}"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--        <div class="row row-bottom">--}}
        {{--            <div class="col-md-6 bottom-left">--}}
        {{--                --}}{{--<img src="{{ asset('styles/images/logo-bottom.png') }}" alt="" />--}}

        {{--                <ul style="margin-top: 25px;">--}}
        {{--                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>--}}
        {{--                    <li><a href="{{ route('frontend.my_course') }}">Khóa học của tôi</a></li>--}}
        {{--                    <li><a href="{{ route('module.quiz') }}">Thi trực tuyến</a></li>--}}
        {{--                    <li><a href="{{ route('module.libraries') }}">Thư viện</a></li>--}}
        {{--                </ul>--}}
        {{--            </div>--}}
        {{--            <div class="col-md-6 bottom-right">--}}
        {{--                --}}{{--<div class="text">--}}
        {{--                    <div class="introduce" style="margin-bottom: 10px;">> Giới thiệu </div>--}}
        {{--                    > Liên hệ & tư vấn: support@bridgestone.com <br />--}}
        {{--                    <font class="copy">copyright© bridgestonevietnam corporation</font>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        {{--        <div class="bottom-mobile">--}}
        {{--            <ul>--}}
        {{--                <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>--}}
        {{--                <li><a href="{{ route('frontend.my_course') }}">Khóa học của tôi</a></li>--}}
        {{--                <li><a href="{{ route('module.quiz') }}">Thi trực tuyến</a></li>--}}
        {{--                <li><a href="{{ route('module.libraries') }}">Thư viện</a></li>--}}
        {{--            </ul>--}}
        {{--        </div>--}}
    </div>
</footer>
</body>
</html>
