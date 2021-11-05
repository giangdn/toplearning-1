@extends('layouts.backend')

@section('page_title', trans('backend.setting'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }}
        </h2>
    </div>
@endsection

@section('content')
    <div class="row mb-5 ml-2">
        @can('config')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.config') }}"><i class="uil uil-cog"></i></a>
                <a href="{{ route('backend.config') }}">{{ trans('backend.generals_setting') }}</a>
            </div>
        </div>
        @endcan

        @can('config-email')
            <div class="col-md-2 mb-3">
                <div class="category-icon">
                    <a href="{{ route('backend.config.email') }}"><i class="fas fa-inbox"></i></a>
                    <a href="{{ route('backend.config.email') }}">{{ trans('backend.email_configuration') }}</a>
                </div>
            </div>
        @endcan

        @can('config-login-image')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.login_image') }}"><i class="fas fa-image"></i></a>
                <a href="{{ route('backend.login_image') }}">{{ trans('backend.login_wallpaper') }}</a>
            </div>
        </div>
        @endcan

        @can('config-logo')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.logo') }}"><i class="fab fa-atlassian"></i></a>
                <a href="{{ route('backend.logo') }}">Logo</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.logo_outside') }}"><i class="fab fa-atlassian"></i></a>
                <a href="{{ route('backend.logo_outside') }}">{{ trans('lacore.extenal_logo') }}</a>
            </div>
        </div>
        @endcan

        @can('config-favicon')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.logo.favicon') }}"><i class="fas fa-bezier-curve"></i></a>
                <a href="{{ route('backend.logo.favicon') }}">Favicon</a>
            </div>
        </div>
        @endcan

		@can('config-app-mobile')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.app_mobile') }}"><i class="fas fa-mobile-alt"></i></a>
                <a href="{{ route('backend.app_mobile') }}">App Mobile</a>
            </div>
        </div>
        @endcan

		@can('config-notify-send')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('module.notify_send') }}"><i class="fas fa-bell"></i></a>
                <a href="{{ route('module.notify_send') }}">{{trans('backend.notify')}}</a>
            </div>
        </div>
        @endcan

        @can('config-notify-template')
            <div class="col-md-2 mb-3">
                <div class="category-icon">
                    <a href="{{ route('module.notify.template') }}"><i class="fas fa-envelope-open-text"></i></a>
                    <a href="{{ route('module.notify.template') }}">{{ trans('lacore.notification_template') }}</a>
                </div>
            </div>
        @endcan

        @can('mail-template')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.mailtemplate') }}"><i class="fas fa-envelope-open-text"></i></a>
                <a href="{{ route('backend.mailtemplate') }}">{{ trans('backend.mailtemplate') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.mail_signature') }}"><i class="fas fa-envelope-open-text"></i></a>
                <a href="{{ route('backend.mail_signature') }}">{{ trans('lacore.email_signature') }}</a>
            </div>
        </div>
        @endcan

        @can('mail-template-history')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.mailhistory') }}"><i class="fas fa-history"></i></a>
                <a href="{{ route('backend.mailhistory') }}">{{ trans('backend.mailhistory') }}</a>
            </div>
        </div>
        @endcan

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.contact') }}"><i class="fas fa-phone"></i></a>
                <a href="{{ route('backend.contact') }}">{{ trans('lacore.contact') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.google.map') }}"><i class="fas fa-map-marker-alt"></i></a>
                <a href="{{ route('backend.google.map') }}">{{ trans('lacore.training_position') }}</a>
            </div>
        </div>

        @can('banner')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.slider') }}"><i class="far fa-images"></i></a>
                <a href="{{ route('backend.slider') }}">Banner</a>
            </div>
        </div>
        @endcan

        @can('banner')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.slider_outside') }}"><i class="far fa-images"></i></a>
                <a href="{{ route('backend.slider_outside') }}">{{ trans('lacore.extenal_banner') }}</a>
            </div>
        </div>
        @endcan

        @can('FAQ')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.infomation_company') }}"><i class="fas fa-info-circle"></i></a>
                <a href="{{ route('backend.infomation_company') }}">{{ trans('lacore.company_info') }}</a>
            </div>
        </div>
        @endcan

        @can('config-login-image')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.banner_login_mobile') }}"><i class="far fa-images"></i></a>
                <a href="{{ route('backend.banner_login_mobile') }}">Banner login mobile</a>
            </div>
        </div>
        @endcan

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.setting_color') }}"><i class="uil uil-cog"></i></a>
                <a href="{{ route('backend.setting_color') }}">{{ trans('lacore.button_setting_color') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.languages') }}"><i class="fas fa-globe-asia"></i></a>
                <a href="{{ route('backend.languages') }}">{{ trans('lacore.languages') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.setting_time') }}"><i class="uil uil-cog"></i></a>
                <a href="{{ route('backend.setting_time') }}">Thời gian </a>
            </div>
        </div>

        {{-- @can('footer')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.footer') }}"><i class="fas fa-bezier-curve"></i></a>
                <a href="{{ route('backend.footer') }}">Footer</a>
            </div>
        </div>
        @endcan --}}
    </div>
@endsection
