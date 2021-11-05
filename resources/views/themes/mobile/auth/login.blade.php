<!doctype html>
<html lang="en" class="deeppurple-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="content-language" content="en">
    <meta name="language" content="en">

    <title>@lang('app.login')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">

    <!-- Material design icons CSS -->
    <link rel="stylesheet" href="{{ asset('themes/mobile/vendor/materializeicon/material-icons.css') }}">

    <!-- Roboto fonts CSS -->
    <link href="{{ asset('css/font_Roboto_300_400_500_700_display_swap.css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Swiper CSS -->
    <link href="{{ asset('themes/mobile/vendor/swiper/css/swiper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('themes/mobile/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login2.css') }}" rel="stylesheet">

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
        var title_message = "{{ data_locale('Thông báo', 'Notification') }}";
    </script>

    <style>
        .btn, .btn:hover, .btn-primary:hover, .btn-info:hover, .btn-default:hover {
            background-color: #8c110a !important;
            border-color: #8c110a;
        }

        .choose_login .button-regular {
            background: rgba(3, 63, 136, 0.5) !important;
            border-radius: 50px;
            border-color: rgba(255, 255, 255, .5) !important;
            width: 100%;
            text-align: left;
        }
    </style>
</head>

<body>
@php
    $img = \App\LoginImage::where('status', '=', 1)->where('type',1)->get();
@endphp
<div class="wrapper">
    @php
        $get_infomation_company = \App\InfomationCompany::first();
    @endphp

    <div id="carouselExampleControls" class="carousel slide w-100" data-ride="carousel">
        <div class="carousel-inner vh-100">
            @foreach($img as $key => $slider)
                <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}" style="background:url({{ $img ? image_file($slider->image) : asset('/images/img-login.jpg')}}) no-repeat center; background-size:cover"></div>
            @endforeach
        </div>
    </div>

    <div class="row no-gutters login-row">
        <div class="col align-self-center px-3 text-center">
            <img src="{{ image_file(\App\Config::getLogo()) }}" alt="logo" width="250">
            <h5 class="welcome_e_learning">{{ data_locale('HỆ THỐNG LEARNING HUB', 'LEARNING HUB SYSTEM') }}</h5>
            {{-- <h6>Vui lòng nhập tên đăng nhập và mật khẩu để tham gia học tập</h6> --}}

            <div class="choose_login">
                <div class="font-weight-bold e_learning_viet_a">{{ data_locale('Vui lòng chọn tài khoản đăng nhập', 'Choose an account') }}</div>
                <div class="mt-3 form-group">
                    <a href="{{route('login.provider','azure')}}" class="btn button-regular text-white login_microsoft">
                        <div class="row">
                            <div class="col-2">
                                <img src="{{ asset('images/icon_microsoft.png') }}" alt="" class="mr-2" style="max-width: 40px; height: 40px;">
                            </div>
                            <div class="col-10 m-auto">
                                <span style="font-size: small"> Đăng nhập bằng tài khoản máy tính/Email cá nhân</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mt-3 form-group">
                    <a href="javascript:void(0)" class="btn button-regular text-white login_elearning">
                        <div class="row">
                            <div class="col-2">
                                <img src="{{ asset('images/icon_hub.png') }}" alt="" class="mr-2" style="max-width: 40px; height: 40px;">
                            </div>
                            <div class="col-10 m-auto">
                                <span style="font-size: small"> Đăng nhập bằng tài khoản Learning Hub</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <form action="{{ route('login') }}" method="post" class="form-signin mt-3 form-ajax" id="form_login_elearning">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" id="inputEmail" class="form-control form-control-lg text-center" placeholder="@lang('backend.user_name')" autofocus value="{{ session()->get('username') ? session()->get('username') : '' }}">
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="inputPassword" class="form-control form-control-lg text-center" placeholder="@lang('backend.pass')" value="{{ session()->get('password') ? session()->get('password') : '' }}">
                </div>
                <!-- login buttons -->
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-lg btn-rounded shadow btn-block">@lang('app.login')</button>
                </div>
                <div class="form-group">
                    <input type="checkbox" {{ session()->get('remember_login') ? 'checked' : '' }} name="remember_login">
                    <label for="" class="login_microsoft">
                        <h6>{{ data_locale('Ghi nhớ mật khẩu', 'Remember password') }}</h6>
                    </label>

                    <span class="btn ml-2 back_choose_login text-white">{{ data_locale('Chọn lại', 'Back') }}</span>
                </div>
                <!-- login buttons -->
            </form>

            {{--<div class="form-signin mt-3">
                <a href="javascript:void(0)" class="form-group btn text-white" data-toggle="modal" data-target="#userThird">
                    {{ data_locale('Đăng ký', 'Register Account') }}
                </a>
            </div>--}}
            {{--<p class="pt-2 text-center">
                <a href="javascript:void(0)" class="text-dark" id="reset-pass" data-url="{{ route('auth.modal_reset_pass') }}">{{ data_locale('Quên mật khẩu?', 'Forgot Password?') }}</a>
            </p>--}}
            {{--<div class="mt-3 form-group">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <span class="col-2"><img src="{{ asset('themes/mobile/img/vietnam.png') }}" alt="" class="avatar-40"> </span>
                            <span class="col-9 m-auto"><a href="{{ route('change_language', ['language' => 'vi']) }}">Vietnamese</a></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <span class="col-2"><img src="{{ asset('themes/mobile/img/english.png') }}" alt="" class="avatar-40"></span>
                            <span class="col-9 m-auto"><a href="{{ route('change_language', ['language' => 'en']) }}">English</a></span>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
        <div class="footer_login_mobile col-12">
            <div class="col-12 get_infomation_company">
                <p class="name_info_company">{{ $get_infomation_company ? $get_infomation_company->title : '' }}</p>
            </div>
        </div>
    </div>
</div>

@include('themes.mobile.modal.create_user_third')

<div id="app-modal"></div>

<!-- jquery, popper and bootstrap js -->
<script src="{{ asset('themes/mobile/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('themes/mobile/js/popper.min.js') }}"></script>
<script src="{{ asset('themes/mobile/js/load-ajax.js') }}"></script>
<script src="{{ asset('themes/mobile/js/form-ajax.js') }}"></script>
<script src="{{ asset('themes/mobile/js/moment.min.js') }}"></script>

<script src="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/sweetalert2/sweetalert2.js') }}" type="text/javascript"></script>
<script src="{{ asset('themes/mobile/vendor/swiper/js/swiper.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/cookie/jquery.cookie.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>

<script src="{{ asset('themes/mobile/js/main.js') }}"></script>
<script type="text/javascript">
    $('#form_login_elearning').hide();

    $('.login_elearning').on('click', function () {
        $('#form_login_elearning').show();
        $('.choose_login').hide();
    });

    $('.back_choose_login').on('click', function () {
        $('#form_login_elearning').hide();
        $('.choose_login').show();
    });

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

    $("#reset-pass").on('click', function () {
        let url = $(this).data('url');
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            data: {},
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #modal-reset-pass").modal();

        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
        return false;
    });

    $('.select2').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $('.datepicker').datetimepicker({
        locale: 'vi',
        format: 'DD/MM/YYYY'
    });
</script>

</body>

</html>
