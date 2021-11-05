<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, public">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('page_title')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">

    <link href="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/OwlCarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ asset('css/cdnjs_cloudflare_v4.7.0_font-awesome.min.css') }}">
    <link href="{{ asset('css/outside.css') }}" rel="stylesheet">
    <link href="{{ asset('styles/bxslider/jquery.bxslider.css') }}" rel="stylesheet">
    <script src="{{ mix('js/theme.js') }}" type="text/javascript"></script>

    @livewireStyles
    @yield('header')
</head>
<body>

    @include('layouts.top_menu_outside')
<!-- Body Start -->
<div class="fix-content" id="home-page" style="opacity: 1;">
    @yield('content')
</div>
    <script src="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            $(".has-sub").hover(function () {
                $(this).find('.sub-menu-drop').addClass('active');
                $(this).find('.sub-menu-drop').find('.has-child').addClass('active');
            }, function () {
                $(this).find('.sub-menu-drop').removeClass('active');
                $(this).find('.sub-menu-drop').find('.has-child').removeClass('active');
            });
        });
        $('.menu_bottom').hide();
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        // true for mobile device
            window.onscroll = function (e) {
                if(window.scrollY == 0){
                    $('.inner-header').show();
                    $('.menu_top').show();
                    $('.menu_bottom').hide();
                    $('.second-menu').css('top','150px');
                    $('.banner_outside').css('margin-top','210px');
                } else {
                    $('.inner-header').hide();
                    $('.menu_top').hide();
                    $('.menu_bottom').show();
                    $('.second-menu').css('top','0px');
                    $('.banner_outside').css('margin-top','160px');
                }
            }
        }else{
        // false for not mobile device
            window.onscroll = function (e) {
                if(window.scrollY == 0){
                    $('.inner-header').show();
                    $('.menu_top').show();
                    $('.menu_bottom').hide();
                    $('.second-menu').css('top','90px');
                } else {
                    $('.inner-header').hide();
                    $('.menu_top').hide();
                    $('.menu_bottom').show();
                    $('.second-menu').css('top','0px');
                }
            }
        }

        $('.datepicker').datetimepicker({
            locale:'vi',
            format: 'DD-MM-YYYY'
        });
    </script>
<!-- Body End -->
    @livewireScripts
</body>
</html>
