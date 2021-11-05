<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, public">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@lang('app.login')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>    <!-- Stylesheets -->
    <link href='{{ asset('css/font_roboto_400_700_500.css') }}' rel='stylesheet'>
    <link href="{{ mix('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/theme.js') }}"></script>
    <style>
        body {
            overflow: hidden;
        }
        .sign_form{
            width: 50%;
        }
        .text-choose-login, #form_login_elearning{
            width: 75%;
            margin: auto;
        }
        .datepicker {
            box-sizing: border-box;
        }
        h3 {
            font-size: 1.28571429rem;
        }

        .title-with-border{
            position: relative;
            font-family: 'Josefin Sans','Nexa-Regular', sans-serif !important;
            margin: 0;
            text-align: center;
            text-transform: uppercase;
            line-height: 70px;
            letter-spacing: 0;
            font-weight: bold;
            color: #014392;
            font-size: 30px;
            padding-top: 15px
        }
        .e_learning_viet_a{
            font-family: 'Nexa-Regular', sans-serif !important;
            font-weight: 500;
            font-size: 20px;
            margin: 0px;
            line-height: 28px;
            letter-spacing: normal;
        }

        .button-regular {
            background: rgba(3, 63, 136, 0.5) !important;
            border-radius: 50px;
            border-color: rgba(255, 255, 255, .5) !important;
            width: 100%;
            text-align: left;
        }
        .btn:hover{
            background: #E53336 !important;
        }
    </style>
    {{--<script>
        const isMobile = () => {
            const vendor = navigator.userAgent || navigator.vendor || window.opera;

            return !!(
                /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(
                    vendor
                ) ||
                /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw-(n|u)|c55\/|capi|ccwa|cdm-|cell|chtm|cldc|cmd-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc-s|devi|dica|dmob|do(c|p)o|ds(12|-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(-|_)|g1 u|g560|gene|gf-5|g-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd-(m|p|t)|hei-|hi(pt|ta)|hp( i|ip)|hs-c|ht(c(-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i-(20|go|ma)|i230|iac( |-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|-[a-w])|libw|lynx|m1-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|-([1-8]|c))|phil|pire|pl(ay|uc)|pn-2|po(ck|rt|se)|prox|psio|pt-g|qa-a|qc(07|12|21|32|60|-[2-7]|i-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h-|oo|p-)|sdk\/|se(c(-|0|1)|47|mc|nd|ri)|sgh-|shar|sie(-|m)|sk-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h-|v-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl-|tdg-|tel(i|m)|tim-|t-mo|to(pl|sh)|ts(70|m-|m3|m5)|tx-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas-|your|zeto|zte-/i.test(
                    vendor.substr(0, 4)
                )
            );
        };
        if(isMobile()==true){
            window.location = 'https://{{config('app.mobile_url')}}';
        }
    </script>--}}
</head>

<body>
@php
    $img = \App\LoginImage::where('status', '=', 1)->where('type',1)->get();
    $app_android = \App\AppMobile::where('type', '=', 1)->first();
    $app_apple = \App\AppMobile::where('type', '=', 2)->first();
    $url = session()->get('url_previous') ? session()->get('url_previous') : '';
@endphp
<!-- Signup Start -->
    <div id="carouselExampleControls" class="carousel slide w-100" data-ride="carousel">
        <div class="carousel-inner vh-100">
            @foreach($img as $key => $slider)
                <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}" style="background:url({{ $img ? image_file($slider->image) : asset('/images/img-login.jpg')}}) no-repeat center; background-size:cover"></div>
            @endforeach
        </div>
    </div>
    <div class="row" id="page-login">
        {{-- <div class="sign_form2"></div> --}}
        <div class="col-12">
            <div class="sign_form">
                <div class="main_logo25" id="logo">
                    <a href="">
                        <img src="{{ image_file(\App\Config::getLogoOutside()) }}" alt="" class="img_logo_login">
                    </a>
                </div>
                <div class="info-login">
                    <div class="title-with-border">HỆ THỐNG LEARNING<span class="highlight"> HUB</span></div>
                    <div class="e_learning_viet_a mb-3">Vui lòng chọn tài khoản đăng nhập</div>
                    <div class="text-choose-login">
                        <div>
                        <a href="{{route('login.provider','azure')}}" class="btn button-regular text-black login_microsoft">
                            <div class="row">
                                <div class="col-2">
                                    <img src="{{ asset('images/icon_microsoft.png') }}" alt="" class="mr-2" style="width: 40px; height: 40px">
                                </div>
                                <div class="col-10 title_login pl-0">
                                    <span style="font-size: medium">Đăng nhập bằng tài khoản máy tính/Email cá nhân</span>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="mt-3">
                        <a href="javascript:void(0)" class="btn button-regular text-black login_elearning">
                            <div class="row">
                                <div class="col-2">
                                    <img src="{{ asset('images/icon_hub.png') }}" alt="" class="mr-2" style="width: 40px; height: 40px">
                                </div>
                                <div class="col-10 title_login pl-0">
                                    <span style="font-size: medium">Đăng nhập bằng tài khoản Learning Hub</span>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                    <form action="{{ route('login') }}" method="post" class="form-ajax" autocomplete="off" id="form_login_elearning">
                        @csrf
                        <div class="ui search focus mt-15">
                            <div class="">
                                <input class="user_name_login" type="text" name="username" value="" id="id_email" required maxlength="64" placeholder="@lang('backend.user_name')" onfocusout="outUsername()">
                                <i class="uil uil-user icon icon2 icon_username" style="color: #ca9500; opacity: 1;"></i>
                            </div>
                        </div>
                        <div class="ui search focus mt-15">
                            <div class="">
                                <input class="password_login" type="password" name="password" value="" id="id_password" required maxlength="64" placeholder="@lang('backend.pass')" onfocusout="outPassword()">
                                <i class="uil uil-key-skeleton-alt icon icon2 icon_password" style="color: #ca9500; opacity: 1;"></i>
                            </div>
                        </div>
                        <div class="pt-2 text-center">
                            <button class="btn login-btn pl-4 pr-4" type="submit">
                                <h4>ĐĂNG NHẬP</h4>
                            </button>
                        </div>
                    </form>
                   {{-- <p class="pt-2 text-center">
                        <a href="javascript:void(0)" class="text-dark" id="reset-pass" data-url="{{ route('auth.modal_reset_pass') }}">{{ data_locale('Quên mật khẩu?', 'Forgot Password?') }}</a>
                    </p>--}}
                    {{-- <div class="row">
                        <div class="col-6 text-center">
                            @if($app_android)
                                <a href="{{ $app_android->link }}" target="_blank" class="w-100">
                                    <img src="{{ image_file($app_android->image) }}" alt="">
                                </a>
                            @endif
                        </div>
                        <div class="col-6">
                            @if($app_apple)
                                <a href="{{ $app_apple->link }}" target="_blank" class="w-100">
                                    <img src="{{ image_file($app_apple->image) }}" alt="">
                                </a>
                            @endif
                        </div>
                    </div> --}}

                </div>
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        @if($app_android)
                            <a href="{{ $app_android->link }}" target="_blank" class="" style="background: #313131 url({{ image_file($app_android->image) }}); display: inline-block; width: 132px; height: auto; min-height: 42px; vertical-align: middle; margin: 0 auto; margin-right: 7px; -webkit-border-radius: 10px"></a>
                        @endif
                        @if($app_apple)
                            <a href="{{ $app_apple->link }}" target="_blank" class="" style="background: #313131 url({{ image_file($app_apple->image) }}); display: inline-block; width: 132px; height: auto; min-height: 42px; vertical-align: middle; margin: 0 auto; margin-right: 7px; -webkit-border-radius: 10px"></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Signup End -->

<div id="app-modal"></div>

<script type="text/javascript">
    var url = '<?php echo $url ?>';
    console.log(url);

    $('#form_login_elearning').hide();

    $('.login_elearning').on('click', function () {
        $('#form_login_elearning').show();
    });

    $("#id_email").on('click',function(){
        $('.icon_username').hide();
        $('.user_name_login').attr('style', 'padding-left: 2em !important');
    });

    $("#id_password").on('click',function(){
        $('.icon_password').hide();
        $('.password_login').attr('style', 'padding-left: 2em !important');
    });

    function outUsername() {
        $('.icon_username').show();
        $('.user_name_login').attr('style', 'padding-left: 4em !important');
    }

    function outPassword() {
        $('.icon_password').show();
        $('.password_login').attr('style', 'padding-left: 4em !important');
    }

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

    $('.datepicker').datetimepicker({
        locale: 'vi',
        format: 'DD/MM/YYYY'
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
</script>

<script type="text/javascript" src="{{ asset('js/theme3.js') }}"></script>

</body>
</html>
