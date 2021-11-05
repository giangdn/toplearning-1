<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, shrink-to-fit=9"
    />
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
    <script>window.user = {{ auth()->user()->id }}</script>
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">
    <link href="{{ mix('css/theme.css') }}" rel="stylesheet">

    <script src="{{ mix('js/theme.js') }}" type="text/javascript"></script>

    <link href='{{ asset('css/chat.css?v='.time()) }}' rel='stylesheet' />
    <link href='{{ asset('css/chatuser.css?v='.time()) }}' rel='stylesheet' />
    @livewireStyles
    @yield('header')
    @php
        $tabs_course = Request::segment(2);
    @endphp
    @if ($tabs_course == 'detail-online')
        <link rel="stylesheet" href="{{ asset('css/detail_course.css') }}">
    @endif
</head>
<body>
    <input type="hidden" id="user-id" value="{{ auth()->user()->id }}">
    @php
        $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
        $get_color = \App\Config::where('name','setting_color')->first();
        $get_hover_color = \App\Config::where('name','setting_hover_color')->first();
    @endphp
    @if ($tabs_course != 'detail-online')
        @include('layouts.top_menu')
        @include('layouts.top_banner')
        @include('layouts.left_menu')
    @endif

    <!-- Body Start -->
    @if ($tabs_course != 'detail-online')
        <div class="wrapper _bg4586">
            <div class="mt-5">
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
    @else
        <div class="{{ $tabs_course != 'detail-online' && 'mt-5' }} body_content">
            @yield('content')
        </div>
        @include('layouts.footer')
    @endif

    @if($user_type == 1 && $tabs_course != 'detail-online')
{{--        @include('layouts.menu_bottom')--}}
    @endif
    <!-- Body End -->
    <script src="{{ mix('js/theme2.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var color = '{{ $get_color ? $get_color->value : "#1b4486" }}';
        var get_hover_color = '{{ $get_hover_color ? $get_hover_color->value : "#1b4486" }}';

        var lazyLoadInstance = new LazyLoad({
            // Your custom settings go here
        });
        // var editor = $('#editor');
        // if(editor.length > 0){
        //     CKEDITOR.replace( 'editor' );
        // }

        $('.datetimepicker').datetimepicker({
            locale:'vi',
            format: 'DD-MM-YYYY HH:mm'
        });
        $('.datetimepicker-timeonly').datetimepicker({
            locale:'vi',
            format: 'LT'
        });
        $('.datepicker').datetimepicker({
            locale:'vi',
            format: 'DD-MM-YYYY'
        });

        var scrollTrigger = 60,
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#logo img').attr('style', 'width: 45%;');
                } else {
                    $('#logo img').attr('style', '');
                }
            };

        // backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $(window).on('scroll', function () {
            if(window.scrollY > 0){
                $('.vertical-fontend').css({
                    'position': 'fixed',
                    'top' : '130px',
                })
                $('.search_button_vertical_frontend').css('position','fixed');
                $('.search_button_vertical_frontend').css('margin-top','70px');
                $('.top_menu').css('height','70px');
                $('.top_menu .main_logo img').css('max-height','70px');
                $('.top_menu .main_logo').css('height','unset');
            } else {
                $('.top_menu').css('height','100px');
                $('.top_menu .main_logo').css('height','80px');
                $('.top_menu .main_logo img').css('max-height','100px');
                $('.search_button_vertical_frontend').css('position','absolute');
                $('.search_button_vertical_frontend').css('margin-top','300px');
                $('.vertical-fontend').css({
                    'position': 'absolute',
                    'top' : '360px',
                    'height' : '500px',
                })
            }
        });
        var heightPage = document.body.scrollHeight;
        if (heightPage <= 590) {
            $('.vertical-fontend').css({
                    'position': 'fixed',
                    'top' : '360px',
                })
            $('.search_button_vertical_frontend').css('position','fixed');
            $('.search_button_vertical_frontend').css('margin-top','70px');
        }

        var close_open_menu_frontend = "{{ session()->get('close_open_menu_frontend') }}";
        if (close_open_menu_frontend && close_open_menu_frontend == 1 ) {
            $('.vertical-fontend').addClass('vertical_nav__minify');
            $('._bg4586').addClass('wrapper__minify');
            $('.search_button_vertical_frontend').addClass('close_menu_backend');
            $('.left-menu-frontend').addClass('w_60');
            $('.left-menu-frontend-2').addClass('w_60');
            $('.menu--label').hide();
        } else if (close_open_menu_frontend && close_open_menu_frontend == 0) {
            $('.vertical-fontend').removeClass('vertical_nav__minify')
            $('._bg4586').removeClass('wrapper__minify')
            $('.search_button_vertical_frontend').css('background', color);
            $('.search_button_vertical_frontend').css('width','200px');
            $('.left-menu-frontend').removeClass('w_60');
            $('.left-menu-frontend-2').removeClass('w_60');
            $('.menu--label').show();
        }

        $('#collapse_menu').on('click',function() {
            if(!$('.vertical-fontend').hasClass('vertical_nav__minify')) {
                $('.search_button_vertical_frontend').css('background','none');
                $('.search_button_vertical_frontend').css('width','0px');
                $('.left-menu-frontend').addClass('w_60');
                $('.left-menu-frontend-2').addClass('w_60');
                $('.menu--label').hide();
                open_close_menu(1);
            } else {
                $('.search_button_vertical_frontend').css('background',color);
                $('.search_button_vertical_frontend').css('width','240px');
                $('.left-menu-frontend').removeClass('w_60');
                $('.left-menu-frontend-2').removeClass('w_60');
                $('.menu--label').show();
                open_close_menu(0);
            }
        })

        function open_close_menu(status) {
            $.ajax({
                url: "{{ route('frontend.close_open_menu') }}",
                type: 'post',
                data: {
                    status: status,
                }
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        // MÀU CHO NÚT NHẤN
        $('#collapse_menu').attr('style', 'background: '+ color +' !important');
        $('.search_button_vertical_frontend').attr('style', 'background: '+ color +' !important');

        $('.btn').attr('style', 'background: '+ color +' !important');
        $(".btn").mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $(".btn").mouseout(function() {
            this.setAttribute('style', 'background: '+ color +' !important');
        });

        $('.menu_bottom').attr('style', 'background: '+ color +' !important');
        $(".menu_bottom").mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $(".menu_bottom").mouseout(function() {
            this.setAttribute('style', 'background: '+ color +' !important');
        });

        var sub_menu_active = $('.sub_menu--link').hasClass('active');
        var menu_link = $('.menu--link').hasClass('active');
        if ( sub_menu_active ) {
            $('.sub_menu--link.active').attr('style', 'background: '+ get_hover_color +' !important');
        }
        if ( menu_link ) {
            $('.menu--link.active').attr('style', 'background: '+ get_hover_color +' !important');
        }

        $( ".menu--label" ).mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $( ".menu--label" ).mouseout(function() {
            this.setAttribute('style', 'background: unset !important');
        });
        $( ".menu--link" ).mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $( ".menu--link" ).mouseout(function() {
            if (!$(this).hasClass("active")) {
                this.setAttribute('style', 'background: unset !important');
            }
        });

        $( ".sub_menu--link" ).mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $( ".sub_menu--link" ).mouseout(function() {
            if (!$(this).hasClass("active")) {
                this.setAttribute('style', 'background: unset');
            }
        });
    </script>
    @livewireScripts
    <div id="app">
        <div id="mod-chat">
{{--        @include('layouts.chat_bot')--}}
{{--        @include('layouts.chat_user')--}}
            <roomuser />
        </div>
    </div>
    @yield('footer')
    <div id="app-modal"></div>
    <script src="{{ mix('js/app.js') }}" defer type="text/javascript"></script>
</body>
</html>
