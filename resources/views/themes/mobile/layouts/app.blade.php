<!doctype html>
<html lang="en" class="deeppurple-theme">
<head>
    <meta charset="utf-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no"> --}}
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <meta http-equiv="content-language" content="en"> --}}
    <meta name="language" content="en">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, shrink-to-fit=9"
    />
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, public">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('page_title')</title>
    <link href="{{ asset('css/font_Roboto_300_400_500_700_display_swap.css') }}" rel="stylesheet">
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">

    <link href="{{ asset('themes/mobile/vendor/materializeicon/material-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/swiper/css/swiper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/OwlCarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/fullcalendar/main.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

    <link href="{{ asset('themes/mobile/vendor/emojionearea/css/emojionearea.min.css') }}" rel="stylesheet">

    <link href="{{ asset('themes/mobile/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/css/dropzone.css') }}" rel="stylesheet">
    <link href="https://vjs.zencdn.net/7.8.4/video-js.css" rel="stylesheet"/>
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('themes/mobile/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/jquery-ui.js') }}"></script>

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
        var title_message = "{{ data_locale('Thông báo', 'Notification') }}";
    </script>

    @yield('header')

    <style>
        .btn-primary:hover, .btn-info:hover, .btn-default:hover, .btn-secondary:hover {
            background-color: #8c110a !important;
            border-color: #8c110a;
        }
        .btn{
            font-size: 14px;
        }
    </style>
</head>
<body>
@include('themes.mobile.layouts.sidebar')
<div class="wrapper homepage" id="homepage">
    @include('themes.mobile.layouts.header')
    @yield('content')
    @include('themes.mobile.layouts.footer')
</div>

<div class="refresher">
    <div class="loading-bar"></div>
    <div class="loading-bar"></div>
    <div class="loading-bar"></div>
    <div class="loading-bar"></div>
</div>

<script>
    (() => {
        async function simulateRefreshAction() {
            const sleep = (timeout) => new Promise(resolve => setTimeout(resolve, timeout));

            const transitionEnd = function (propertyName, node) {
                return new Promise(resolve => {
                    function callback(e) {
                        e.stopPropagation();
                        if (e.propertyName === propertyName) {
                            node.removeEventListener('transitionend', callback);
                            resolve(e);
                        }
                    }

                    node.addEventListener('transitionend', callback);
                });
            };

            const refresher = document.querySelector('.refresher');

            document.body.classList.add('refreshing');
            await sleep(2000);

            refresher.classList.add('shrink');
            await transitionEnd('transform', refresher);
            refresher.classList.add('done');

            refresher.classList.remove('shrink');
            document.body.classList.remove('refreshing');
            await sleep(0); // let new styles settle.
            refresher.classList.remove('done');
        }

        let _startY = 0;
        const homepage = document.querySelector('#homepage');
        homepage.addEventListener('touchstart', e => {
            _startY = e.touches[0].pageY;
        }, {passive: true});
        homepage.addEventListener('touchmove', e => {
            const y = e.touches[0].pageY;
            // Activate custom pull-to-refresh effects when at the top fo the container
            // and user is scrolling up.
            if (document.scrollingElement.scrollTop === 0 && y > _startY &&
                !document.body.classList.contains('refreshing')) {
                simulateRefreshAction();
                window.location = '';
            }
        }, {passive: true});
    })();

    $('body').on('click', '.load-modal', function () {
        let item = $(this);
        let url = $(this).data('url');

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'html',
            data: {},
        }).done(function(data) {

            $("#app-modal").html(data);
            $("#app-modal #myModal").modal();
        }).fail(function(data) {

            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    $('body').on('click', '.userThird', function () {
        show_message("{{ data_locale('Không có quyền', 'Permission denied') }}", 'error')
    });
</script>

<!-- Modal -->
@yield('modal')
<!-- color chooser menu -->
@include('themes.mobile.modal.colorscheme')

<!-- change language -->
@include('themes.mobile.modal.settings')

<!-- change avatar user -->
@include('themes.mobile.modal.change_avatar_user')

@include('themes.mobile.modal.filter_online')

<script src="https://vjs.zencdn.net/7.8.4/video.js"></script>
<!-- jquery, popper and bootstrap js -->
<script src="{{ asset('themes/mobile/js/popper.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/bootstrap-table/bootstrap-table-vi-VN.js') }}"></script>
<script src="{{ asset('themes/mobile/js/LoadBootstrapTable.js') }}"></script>
<script src="{{ asset('themes/mobile/js/load-ajax.js') }}"></script>
<script src="{{ asset('themes/mobile/js/form-ajax.js') }}"></script>
<script src="{{ asset('themes/mobile/js/moment.min.js') }}"></script>

<script src="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/sweetalert2/sweetalert2.js') }}" type="text/javascript"></script>
<script src="{{ asset('themes/mobile/vendor/fullcalendar/main.js') }}" type="text/javascript"></script>
<script src="{{ asset('themes/mobile/vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/swiper/js/swiper.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/cookie/jquery.cookie.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/emojionearea/js/emojionearea.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js') }}"></script>

<script src="{{ asset('themes/mobile/js/load-select2.js') }}"></script>
<script src="{{ asset('themes/mobile/js/main.js') }}"></script>
<script src="{{ asset('themes/mobile/js/dropzone.js') }}"></script>

<div id="app-modal"></div>

@yield('footer')

</body>
</html>
