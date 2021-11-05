@php
    $arr = ['frontend.home', 'themes.mobile.frontend.chart', 'themes.mobile.frontend.profile', 'themes.mobile.frontend.approve_course.course', 'module.libraries', 'module.frontend.forums', 'themes.mobile.frontend.training_process', 'module.front.promotion', 'themes.mobile.frontend.manager', 'module.faq.frontend.index', 'frontend.calendar', 'themes.mobile.frontend.offline.index', 'module.quiz', 'module.survey', 'module.capabilities.review.user.view_course', 'themes.mobile.frontend.notify.index', 'module.daily_training.frontend', 'frontend.attendance', 'module.career_roadmap.frontend', 'frontend.guide', 'frontend.plan_app', 'themes.mobile.frontend.get_rank', 'module.suggest.index'];
    $routeName = Route::currentRouteName();
@endphp
<div class="header @if($routeName != 'frontend.home') bg-template text-white @endif">
    <div class="row no-gutters">
        @if(in_array($routeName, $arr))
            <div class="col-auto">
                <button class="btn btn-link @if($routeName != 'frontend.home') text-white @else text-dark @endif menu-btn"><i class="material-icons">menu</i></button>
            </div>
        @else
            <a href="javascript:void(0)" onclick="window.history.back(); return false;" class="mt-3 text-white">
                <i class="material-icons md-24 vm font-weight-bold">navigate_before</i> <span>@lang('app.back')</span>
            </a>
        @endif
        <div class="col text-center mt-3">
            <h5>
                @yield('page_title')
            </h5>
        </div>
        @if(isset($lay) && $lay == 'home')
            <div class="col-auto">
                {{--<a href="{{ route('module.daily_training.frontend.add_video') }}" class="btn btn-link text-dark position-relative">
                    <img src="{{ asset('themes/mobile/img/video-camera.png') }}" alt="" style="width: 25px; height: 25px;">
                </a>--}}
                <a href="{{ route('qrcode') }}" class="btn btn-link text-dark position-relative">
                    <img class="QR_code" src="{{ asset('themes/mobile/img/qrcode-user.png') }}" alt="qr-code" class="">
                </a>
                <a href="{{ route('themes.mobile.frontend.search.index') }}" class="btn btn-link text-dark position-relative">
                    <i class="material-icons">search</i>
                </a>
            </div>
        @endif
        @if(isset($lay) && $lay == 'online')
            <div class="col-auto">
                <a href="javascript:void(0)" class="btn btn-link text-white position-relative" data-toggle="modal" data-target="#filterOnline">
                    <img src="{{ asset('themes/mobile/img/filter.png') }}" alt="">
                </a>
            </div>
        @endif
        @if(isset($lay) && $lay == 'video')
            <div class="col-auto">
                <a href="{{ route('module.daily_training.frontend.add_video') }}" class="btn btn-link text-white position-relative">
                    <i class="material-icons vm">add_circle</i>
                </a>
                <a href="{{ route('module.daily_training.frontend.search') }}" class="text-white">
                    <i class="material-icons vm">search</i>
                </a>
            </div>
        @endif
        @if($routeName == 'frontend.attendance')
            <div class="col-auto">
                <a href="javascript:void(0)" class="btn btn-link text-white position-relative" data-toggle="modal" data-target="#seachCourseInAttendace">
                    <i class="material-icons">search</i>
                </a>
            </div>
        @endif
        @if($routeName == 'module.online.embed')
            <div class="col-auto">
                <button class="btn btn-link text-white position-relative" id="autorenew"><i class="material-icons">autorenew</i></button>
            </div>
        @endif
        @if($routeName != 'themes.mobile.frontend.notify.index')
            <div class="col-auto">
                <a href="{{ route('themes.mobile.frontend.notify.index') }}" class="btn btn-link @if($routeName != 'frontend.home') text-white @else text-dark @endif position-relative">
                    <i class="material-icons">notifications_none</i>
                    <span class="counts">{{ \Modules\Notify\Entities\NotifySend::countMessage() }}</span>
                </a>
            </div>
        @endif
    </div>
</div>
