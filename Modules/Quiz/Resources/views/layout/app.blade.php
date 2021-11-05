<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="itechco">
    <meta name="author" content="itechco">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<meta name="turbolinks-cache-control" content="no-cache">--}}
    <title>@yield('page_title')</title>

    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">

    <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,500" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
{{--    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>--}}
    <script src="{{ asset('js/theme.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" ></script>

    @livewireStyles
    @yield('header')
    <style>
        @media (max-width: 823px) {
            .header .header_right{
                display: none;
            }
        }
        .footer{
            position: fixed;
            bottom: 0;
        }
        .faq1256{
            padding-bottom: 50px;
        }
    </style>
</head>

<body>
@php
    $routeName = Route::currentRouteName();
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
@endphp
{{-- <header class="header clearfix">
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                <div class="row text-center">
                    <div class="back_link col-6 col-md-4">
                        @if($routeName != 'module.quiz.doquiz.do_quiz')
                        <a href="{{ route('module.quiz') }}" class="hde151">Back</a>
                        @endif
                    </div>
                    <div class="ml_item col-6 col-md-4"> --}}
                       {{-- <div class="main_logo15" id="logo">
                            <a href="/"><img src="{{ image_file(\App\Config::getLogo()) }}" alt=""></a>
                            <a href="/"><img class="logo-inverse" src="{{ image_file(\App\Config::getLogo()) }}" alt=""></a>
                        </div>--}}
                    {{-- </div>
                    @if($user_type == 1)
                    <div class="header_right pr-0 col-md-4">
                        <ul>
                            <li class="ui top right pointing dropdown">
                                <a href="#" class="opts_account">
                                    <img src="{{ \App\Profile::avatar() }}" alt="">
                                </a>
                                <div class="menu dropdown_account">
                                    <div class="channel_my">
                                        <div class="profile_link">
                                            <img src="{{ \App\Profile::avatar() }}" alt="">
                                            <div class="pd_content">
                                                <div class="rhte85">
                                                    <h6>{{ \App\Profile::fullname() }}</h6>
                                                    <div class="mef78" title="Verify">
                                                        <i class='uil uil-check-circle'></i>
                                                    </div>
                                                </div>
                                                <span>{{ \App\Profile::email() }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('module.frontend.user.info') }}" class="dp_link_12">@lang('app.user_info')</a>
                                    </div>
                                    <a href="{{ route('logout') }}" class="item channel_item">@lang('app.logout')</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header> --}}

<div class="{{--_bg4586 _new89--}}">
    <div class="faq1256">
        @yield('content')
    </div>

    @include('layouts.footer')
</div>

<script src="{{ asset('js/theme2.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    var lazyLoadInstance = new LazyLoad({
        // Your custom settings go here
    });
    var editor = $('#editor');
    if(editor.length > 0){
        // CKEDITOR.replace( 'editor' );
        CKEDITOR.replace('editor', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    }

</script>
@livewireScripts
@yield('footer')
</body>
</html>
