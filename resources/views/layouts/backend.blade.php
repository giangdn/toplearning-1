<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<meta name="turbolinks-cache-control" content="no-cache">--}}
    <title>@yield('page_title')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    <script src="{{ mix('js/backend.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.css') }}" />
    <script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/bootstrap-table-treegrid.min.js') }}"></script>

    @livewireStyles
    @yield('header')
</head>

<body>
    @php
        $get_color = \App\Config::where('name','setting_color')->first();
        $get_hover_color = \App\Config::where('name','setting_hover_color')->first();
    @endphp
    @include('layouts.backend.top_menu')
    {{-- @include('layouts.top_banner') --}}
    @include('layouts.backend.left_menu')
    <!-- Body Start -->
    <div class="wrapper _bg4586 wrapper_backend">
        <div class="sa4d25 mt-5 body_content">
            <div class="container-fluid container_backend">
                <div class="row mb-10 mt-2 bg-white">
                    <div class="col-md-12">
                        @yield('breadcrumb')
                    </div>
                </div>

                <div class="row bg-white backend-container pt-3">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('layouts.backend.footer') --}}
    </div>

    <div id="app-modal"></div>
    <!-- Body End -->
    <script src="{{ mix('js/backend2.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var color = '{{ $get_color ? $get_color->value : "#1b4486" }}';
        var get_hover_color = '{{ $get_hover_color ? $get_hover_color->value : "#1b4486" }}';

        $(".form-validate").validate({
            onfocusout: false,
            highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                    elem.addClass(errorClass);
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                    elem.removeClass(errorClass);
                }
            },
            errorPlacement: function (error, element) {
                return true;
            }
        });
        var editor = $('#editor');
        if(editor.length > 0){
            CKEDITOR.replace( 'editor' );
        }

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
                $('.nav_backend').addClass('scroll_top');
                $('.search_button_vertical').css('margin-top','70px');
                $('.top_menu_backend').css('height','70px');
                $('.top_menu_backend .main_logo img').css('max-height','70px');
                $('.top_menu_backend .main_logo').css('height','unset');
                $('.nav_backend').addClass('scroll_top');
                $('.nav_backend').removeClass('scroll_menu')
            } else {
                $('.top_menu_backend').css('height','100px');
                $('.top_menu_backend .main_logo').css('height','80px');
                $('.top_menu_backend .main_logo img').css('max-height','100px');
                $('.search_button_vertical').css('margin-top','100px');
                $('.nav_backend').addClass('scroll_menu');   
                $('.nav_backend').removeClass('scroll_top')        
            }
        });

        var close_open_menu_backend = "{{ session()->get('close_open_menu_backend') }}";
        if (close_open_menu_backend && close_open_menu_backend == 1 ) {
            $('.vertical_nav').addClass('vertical_nav__minify');
            $('.wrapper_backend').addClass('wrapper__minify');
            $('.search_button_vertical').addClass('close_menu_backend');
            $('.body_content').css('padding-left','40px');
            $('.nav_backend').css('width','60px');
        } else if (close_open_menu_backend && close_open_menu_backend == 0) {
            $('.vertical_nav').removeClass('vertical_nav__minify')
            $('.wrapper_backend').removeClass('wrapper__minify')
            $('.search_button_vertical').css('background', color);
            $('.search_button_vertical').css('width','200px');
            $('.body_content').css('padding-left','0px');
            $('.nav_backend').css('width','200px');
        }

        $('#collapse_menu').on('click',function() {
            if(!$('.vertical_nav').hasClass('vertical_nav__minify')) {
                $('.search_button_vertical').css('background','none');
                $('.search_button_vertical').css('width','60px');
                $('.body_content').css('padding-left','40px');
                $('.nav_backend').css('width','60px');
                open_close_menu(1);
            } else {
                $('.search_button_vertical').css('background', color);
                $('.search_button_vertical').css('width','200px');
                $('.body_content').css('padding-left','0px');
                $('.nav_backend').css('width','200px');
                open_close_menu(0);
            }
            // lấy function trong breadcum.blade
            updateNav();
        })

        function open_close_menu(status) {
            $.ajax({
                url: "{{ route('backend.close_open_menu') }}",
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
        $('.search_button_vertical').attr('style', 'background: '+ color +' !important');

        $('.btn').attr('style', 'background: '+ color +' !important');

        $( ".btn" ).mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $( ".btn" ).mouseout(function() {
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
    @yield('footer')
</body>
</html>
