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
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/detail_course.css') }}">
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/theme.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dropzone.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    @livewireStyles
    @yield('header')

</head>
<body>
    @php
        $tabs_course = Request::segment(2);
    @endphp
    @if ($tabs_course != 'detail-online')
        @include('layouts.top_menu')
    @endif
<!-- Body Start -->
    @if ($tabs_course != 'detail-online')
        <div class="wrapper _bg4586">
            @yield('left_menu_activity')
        </div>
    @endif
    <div class="{{ $tabs_course != 'detail-online' && 'mt-5' }} body_content">
        @yield('content')
        @include('layouts.footer')
    </div>

<!-- Body End -->
<script src="{{ asset('js/theme2.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    var lazyLoadInstance = new LazyLoad({
        // Your custom settings go here
    });
    var editor = $('#editor');
    if(editor.length > 0){
        CKEDITOR.replace( 'editor' );
    }

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

</script>
@livewireScripts
@yield('footer')

<div id="app-modal"></div>
</body>
</html>
