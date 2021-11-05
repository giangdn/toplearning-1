@extends('layouts.app')

@section('page_title', 'Thông tin tài khoản')

@php
    $tabs = Request::segment(2);
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
@endphp
@section('header')
    @if ($tabs=='referer')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
    @endif

    @if ($agent->isMobile())
    <link href="{{ asset('themes/mobile/vendor/swiper/css/swiper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/mobile/vendor/swiper/js/swiper.min.js') }}"></script>
    @endif
    
    @if($tabs == 'training-by-title')
        <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    @endif
    <style>
        #my-course .tab_crse .nav-link{
            padding: 0.5rem 0.5rem !important;
        }

        #training-by-title .img-info{
            width: 30px;
        }
        #training-by-title .progress{
            border-radius: 1rem !important;
        }
        #training-by-title .progress2{
            height: 2rem !important;
        }
    </style>
@endsection
@section('content')
    <div class="_215b15 infomation_of_user">
        <div class="container-fluid pr-1">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="course_tabs" id="my-course">
                        <nav>
                            @if($user_type == 1)
                            <div class="nav nav-pills mb-4 tab_crse" id="nav-tab" role="tablist">
                                @if ($agent->isMobile())
                                    @include('user::frontend.mobile')
                                @else
                                    @include('user::frontend.web')
                                @endif
                            </div>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="_215b17">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="course_tab_content">
                        <div class="tab-content" id="nav-tabContent">
                            @switch(Request::segment(2))
                                @case('info')
                                    @include('user::frontend.info.index')
                                    @break
                                @case('referer')
                                    @include('user::frontend.referer.index')
                                    @break
                                @case('trainingprocess')
                                    @include('user::frontend.trainingprocess.index')
                                    @break
                                @case('quizresult')
                                    @include('user::frontend.quizresult.index')
                                    @break
                                @case('roadmap')
                                    @include('user::frontend.roadmap.index')
                                    @break
                                @case('subjectregister')
                                    @include('user::frontend.subjectregister.index')
                                    @break
                                @case('my-course')
                                    @include('user::frontend.my_course.index')
                                    @break
                                @case('my-promotion')
                                    @include('user::frontend.my_promotion.index')
                                    @break
                                @case('point-hist')
                                    @include('user::frontend.point_hist.index')
                                    @break
                                @case('training-by-title')
                                    @include('user::frontend.training_by_title.index')
                                    @break
                                @case('my-career-roadmap')
                                    @include('user::frontend.my_career_roadmap.index2')
                                    @break
                                @case('violate-rules')
                                    @include('user::frontend.violate_rules.index')
                                    @break
                                @case('student-cost')
                                    @include('user::frontend.students_cost.index')
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        $('.infomation_of_user').attr('style', 'background: '+ color +' !important');
        var nav_item = $('.nav-link').hasClass('active');
        if (nav_item) {
            $('.nav-link.active').attr('style', 'background: '+ get_hover_color +' !important');
        }
        $(".nav-link").mouseover(function() {
            this.setAttribute('style', 'background: '+ get_hover_color +' !important');
        });
        $(".nav-link").mouseout(function() {
            if (!$(this).hasClass("active")) {
                this.setAttribute('style', 'background: unset');
            }
        });
    </script>
@endsection
