<!DOCTYPE html>
<html dir="ltr" lang="vi" xml:lang="vi">
<head>
    <title>@yield('page_title')</title>
    <link rel="shortcut icon" href="/theme/ila/pix/logo.png" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/backend/styles/css/grid.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/backend/styles/css/prism.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/backend/styles/css/base.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/backend/category/css/category.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/select2/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/fontawesom/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/bootstrap-table/bootstrap-table.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/module/quiz/css/customs-layout.css') }}" />

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>

    <script type="text/javascript" src="{{ asset('styles/js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/css/backend/styles/js/gdropdown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/sweetalert2/sweetalert2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/bootstrap-table/bootstrap-table-vi-VN.js') }}"></script>


    @yield('header')
    <script type="text/javascript" src="{{ asset('styles/js/load-ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/js/LoadBootstrapTable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/js/form-ajax.js') }}"></script>


</head>
<body id="page-exts-index" class="format-site  path-exts safari dir-ltr lang-vi yui-skin-sam yui3-skin-sam dev-bridgestone-toplearning-vn pagelayout-administrator course-1 context-1 two-column content-only">
<header role="banner" id="adminstrator-nav-bar" class="navbar navbar-fixed-top moodle-has-zindex">

    <nav class="navbar navbar-expand-lg navbar-light navbar-fixed-top navbar-findcond nav-custom" id="main-navbar">
        <div class="d-none d-sm-block site-logo">
            <a class="navbar-brand pjax-load img-custom" href="{{ route('frontend.home') }}" title="">
                <img src="{{ image_file(\App\Config::getLogo()) }}" alt="">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

        </div>
    </nav>

    <div class="menu-custom desktop">
        <span class="logout-custom">Bạn đang đăng nhập với tên <b>{{ App\Profile::fullname() }}</b> (<a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="#">Thoát</a>)</span>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</header>
<div id="adminstrator-page">
    <div class="container-fluid">
        <header id="page-header" class="clearfix">
            <h2><i class="fa fa-asterisk"></i>&nbsp;&nbsp;@yield('page_title')</h2>
            @yield('breadcrumb')
{{--            <div id="page-navbar" class="clearfix">--}}
{{--                <nav aria-label="breadcrumb">--}}
{{--                    <ol class="breadcrumb">--}}
{{--                        <li class="breadcrumb-item"><a itemprop="url" href="{{ route('frontend.home') }}"><span itemprop="title">Trang chủ</span></a></li>--}}
{{--                        @if(\Request::route()->getName() !== 'backend.dashboard')--}}
{{--                            <li class="breadcrumb-item"><a itemprop="url" href="{{ route('backend.dashboard') }}">Trang quản trị</a></li>--}}
{{--                        @else--}}
{{--                            <li class="breadcrumb-item">Trang quản trị</li>--}}
{{--                        @endif--}}
{{--                    </ol>--}}
{{--                </nav>--}}

{{--                <div class="breadcrumb-button"></div>--}}
{{--            </div>--}}
        </header>
        <!-- Copy từ column2 -->
        <div id="page-content-administrator" class="container-fluid">
            @yield('content')
        </div>

        <div id="footer">
            <div class="container-fluid">
                <hr />
                <div class="row footer-1">
                    <div class="col-md-7 text-left">
                        <p><strong>HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING (TOPLEARNING LMS)</strong></p>
                        <p style="margin-bottom: 5px;">
                            Sự lựa chọn hàng đầu cho doanh nghiệp của Bạn                                </p>
                    </div>
                    <div class="col-md-5">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <ul class="navbar-nav ml-auto footer-ul-custom">
                                <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                                <li><a href="#">{{ trans('backend.contacts') }}</a></li>
                                <li><a href="#">Web mail</a></li>
                                <li><a href="#">Sơ đồ web</a></li>
                                <li><a href="#top" style="font-size:1.5em;"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="app-modal"></div>


</body>
</html>
