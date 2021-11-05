@extends('layouts.backend')

@section('page_title', 'Cài đặt cấu hình email')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.configuration_settings_email') }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ trans('backend.configuration_generals_email') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('backend.config.email.save') }}" method="post" class="form-ajax">
                <div class="form-group">
                    <label>Email driver</label>
                    <input type="text" class="form-control" name="email_driver" placeholder="smtp" value="{{ get_config('email_driver') }}">
                </div>

                <div class="form-group">
                    <label>Email host</label>
                    <input type="text" class="form-control" name="email_host" placeholder="smtp.gmail.com" value="{{ get_config('email_host') }}">
                </div>

                <div class="form-group">
                    <label>Email port</label>
                    <input type="text" class="form-control" name="email_port" placeholder="587" value="{{ get_config('email_port') }}">
                </div>
                <div class="form-group">
                    <label>{{ trans('backend.send_from') }}</label>
                    <input type="text" class="form-control" name="email_from_name" value="{{ get_config('email_from_name') }}" placeholder="Hệ thống đào tạo">
                </div>
                <div class="form-group">
                    <label>{{ trans('backend.address_email_send') }}</label>
                    <input type="text" class="form-control" name="email_address" value="{{ get_config('email_address') }}" placeholder="hello@example.com">
                </div>
                <div class="form-group">
                    <label>User {{ trans('backend.login') }}</label>
                    <input type="text" class="form-control" name="email_user" value="{{ get_config('email_user') }}">
                </div>
                <div class="form-group">
                    <label>Email password</label>
                    <input type="password" class="form-control" name="email_password" value="{{ get_config('email_password') }}">
                </div>
                <div class="form-group">
                    <label>Email encryption</label>
                    <input type="text" class="form-control" name="email_encryption" placeholder="tls" value="{{ get_config('email_encryption') }}">
                </div>
                @can('config-email-save')
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('backend.save')</button>
                @endcan
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ trans('backend.test_configuration_email') }}</h5>
        </div>

        <div class="card-body">
            <p class="description">{{ trans('backend.save_configuration') }}</p>
            <form action="{{ route('backend.config.email.test') }}" method="post" class="form-ajax">
                <div class="form-group">
                    <label>Email {{ trans('backend.receive') }}</label>
                    <input type="text" class="form-control" name="email" placeholder="youmailtest@gmail.com">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Send mail test</button>
            </form>
        </div>
    </div>
</div>
@stop
