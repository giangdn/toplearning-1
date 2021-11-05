<!doctype html>
<html lang="en" class="deeppurple-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="Maxartkiller">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title')</title>

    <!-- Material design icons CSS -->
    <link rel="stylesheet" href="{{ asset('themes/mobile/vendor/materializeicon/material-icons.css') }}">

    <!-- Roboto fonts CSS -->
    <link href="{{ asset('css/font_Roboto_300_400_500_700_display_swap.css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Swiper CSS -->
    <link href="{{ asset('themes/mobile/vendor/swiper/css/swiper.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('themes/mobile/css/style.css') }}" rel="stylesheet">

</head>

<body>
<div class="wrapper homepage">
    <div class="row no-gutters bg-template">
        <div class="col p-1">
            <form method="get" class="form-search border-0" style="display: flex">
                <a href="{{ route('frontend.home') }}" class="btn btn-link text-white font-weight-bold pl-0">
                    <i class="material-icons font-weight-bold">navigate_before</i>
                </a>
                <input type="text" name="q" class="btn-rounded-10 form-control is-invalid" placeholder="{{ data_locale('Nhập tên khóa offline', 'Enter a course name online') }}" value="{{ request()->get('q') }}" onload="submit();">
                <input type="hidden" name="type" class="form-control" value="{{ request()->get('type') }}">
            </form>
        </div>
    </div>
    <div class="container">
        <div id="search" @if(request()->get('q')) hidden @endif>
            <p>@lang('app.hot_keyword')</p>
            <div class="list-group">
                <a href="javascript:void(0)" class="list-group-item" data-type="1"> @lang('app.monthly_online_courses')</a>
                <a href="javascript:void(0)" class="list-group-item" data-type="2"> @lang('app.course_about_to_organize')</a>
                <a href="javascript:void(0)" class="list-group-item" data-type="3"> @lang('app.offline_course_about_organize')</a>
                <a href="javascript:void(0)" class="list-group-item" data-type="4"> @lang('app.exams_the_month')</a>
                <a href="javascript:void(0)" class="list-group-item" data-type="5"> @lang('app.course_according_the_route')</a>
                <a href="javascript:void(0)" class="list-group-item" data-type="6"> @lang('app.course_learning')</a>
                <a href="javascript:void(0)" class="list-group-item" data-type="7"> @lang('app.training_results')</a>
            </div>
        </div>
        <br>
        <div id="item-obj">
            @if(request()->get('q') && count($items) > 0)
                <div class="row">
                @foreach($items as $item)
                    @if(request()->get('type') == 4)
                        @php
                            $start_date = \Modules\Quiz\Entities\QuizPart::where('quiz_id', '=', $item->id)->first(\DB::raw('MIN(start_date) as start_date'));
                            $end_date = \Modules\Quiz\Entities\QuizPart::where('quiz_id', '=', $item->id)->first(\DB::raw('MAX(end_date) as end_date'));
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                            <div class="card shadow border-0 mb-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 p-1">
                                            <img src="{{ image_file($item->image) }}" alt="" class="mw-100">
                                        </div>
                                        <div class="col-12 p-1 align-self-center">
                                            <a href="">
                                                <h6 class="mb-2 mt-1 font-weight-normal">{{ $item->name }}</h6>
                                            </a>
                                            <p class="text-mute">
                                                <b>@lang('app.time'): </b> {{ get_date($start_date->start_date) }} @if($end_date) @lang('app.to') {{ get_date($end_date->end_date) }} @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            if (request()->get('type') == 3){
                                $route = route('themes.mobile.frontend.offline.detail', ['course_id' => $item->id]);
                            }elseif (request()->get('type') == 5 || request()->get('type') == 7){
                                if ($item->course_type == 1){
                                    $route = route('themes.mobile.frontend.online.detail', ['course_id' => $item->id]);
                                }else{
                                    $route = route('themes.mobile.frontend.offline.detail', ['course_id' => $item->id]);
                                }
                            }else{
                                $route = route('themes.mobile.frontend.online.detail', ['course_id' => $item->id]);
                            }
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                            <div class="card shadow border-0 mb-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 p-1">
                                            <img src="{{ image_file($item->image) }}" alt="" class="mw-100">
                                        </div>
                                        <div class="col-12 p-1 align-self-center">
                                            <a href="{{ $route }}">
                                                <h6 class="mb-2 mt-1 font-weight-normal">{{ $item->name }}</h6>
                                            </a>
                                            <p class="text-mute">
                                                <b>@lang('app.time'): </b> {{ get_date($item->start_date) }} @if($item->end_date) @lang('app.to') {{ get_date($item->end_date) }} @endif
                                                <br>
                                                @if($item->register_deadline) <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }} @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                </div>
                <div class="row">
                    <div class="col-6">
                        @if($items->previousPageUrl())
                            <a href="{{ $items->previousPageUrl() }}" class="bp_left">
                                <i class="material-icons">navigate_before</i> @lang('app.previous')
                            </a>
                        @endif
                    </div>
                    <div class="col-6 text-right">
                        @if($items->nextPageUrl())
                            <a href="{{ $items->nextPageUrl() }}" class="bp_right">
                                @lang('app.next') <i class="material-icons">navigate_next</i>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col text-center">
                        <span>@lang('app.not_found')</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- footer-->
    @include('themes.mobile.layouts.footer')
    <!-- footer ends-->

</div>
<!-- jquery, popper and bootstrap js -->
<script src="{{ asset('themes/mobile/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('themes/mobile/js/popper.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js') }}"></script>

<!-- swiper js -->
<script src="{{ asset('themes/mobile/vendor/swiper/js/swiper.min.js') }}"></script>

<!-- cookie js -->
<script src="{{ asset('themes/mobile/vendor/cookie/jquery.cookie.js') }}"></script>

<!-- template custom js -->
<script src="{{ asset('themes/mobile/js/main.js') }}"></script>

<script type="text/javascript">
    $('.form-control.is-invalid').on('click', function () {
        $('input[name=q]').val('').change();
        $('input[name=type]').val('').change();
    });

    $('input[name=q]').on('change', function () {
        var search = $(this).val();
        $('input[name=type]').val('').change();

        if (search.length > 0){
            $('#search').prop('hidden', true);
        }else{
            $('#search').prop('hidden', false);
        }
    });

    $('.list-group-item').on('click', function () {
        var q = $(this).text();
        var type = $(this).data('type');

        $('input[name=q]').val(q).change();
        $('input[name=type]').val(type).change();
    });
</script>


</body>
</html>

