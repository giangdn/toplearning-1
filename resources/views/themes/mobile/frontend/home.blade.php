@extends('themes.mobile.layouts.app')

@section('page_title', 'Home')

@section('content')
    <div class="container">
        <div class="row">
            <div id="carouselExampleSlidesOnly" class="carousel slide mb-3" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($sliders as $key => $slider)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ image_file($slider->image) }}" alt="" class="w-100" style="height: 100%;"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container">
        <div class="card bg-template shadow mt-2 mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto pr-0 text-center">
                        <a href="{{ route('qrcode') }}">
                            <img src="{{ asset('themes/mobile/img/qr-code.png') }}" alt="qr-code" class="">
                        </a> <br>
                        <p class="text-mute"> scan </p>
                    </div>
                    <div class="col p-0 text-center">
                        <p class="mb-0 font-weight-normal">{{ $total_learners }}</p>
                        <p class="text-mute"> @lang('app.total_learners') </p>
                    </div>
                    <div class="col-auto pl-0 text-center">
                        <p>{{ \App\User::countUsersOnline() }} <br>
                            <span class="text-mute">@lang('app.onlining')</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="container">
        <div class="row bg-white p-2 shadow">
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('themes.mobile.frontend.online.index') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/online-learning.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Khóa học', 'Online Courses') }} <br> {{ data_locale('Online', ' ') }}</p>
                    </a>
                </div>
            </div>
            @if(\App\Profile::usertype() != 2)
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('themes.mobile.frontend.offline.index') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/offline.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Khóa học', 'Inhouse') }} <br> {{ data_locale('Tập trung', ' ') }}</p>
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('themes.mobile.frontend.my_course') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay gradient-primary"></div>
                            <img src="{{ asset('themes/mobile/img/my_course.png') }}" alt="">
                        </div>
                        <p class="mt-1 small mb-0">{{ data_locale('Khóa học', 'My Courses') }} <br> {{ data_locale('của tôi', ' ') }}</p>
                        @if ( $count_my_course > 0)
                            <span class="count_my_course">{{ $count_my_course }}</span>
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('module.quiz.mobile') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/exam.png') }}" alt="">
                        </div>
                        <p class="mt-1 small mb-0">@lang('app.quiz_mobile')</p>
                        @if ($count_quiz > 0)
                            <span class="count_my_course">{{ $count_quiz }}</span>
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('module.survey') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/survey.png') }}" alt="">
                        </div>
                        <p class="mt-1 small mb-0">@lang('app.survey')</p>
                        @if ($count_survey > 0)
                            <span class="count_my_course">{{ $count_survey }}</span>
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('module.rating_level') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/evaluate.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Đánh giá đào tạo', 'Evaluate') }}</p>
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('module.frontend.training_by_title') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/roadmap.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Lộ trình đào tạo', 'Training Roadmap') }}</p>
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('themes.mobile.frontend.training_process') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/history.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Lịch sử học tập', 'Learning History') }}</p>
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ userThird() ? 'javascript:void(0)' : route('module.news') }}" class="{{ userThird() ? 'userThird' : '' }} text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/news.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Tin tức', 'New') }}</p>
                    </a>
                </div>
            </div>
            @endif
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('module.libraries') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/library.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">@lang('app.library')</p>
                    </a>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ route('module.frontend.forums') }}" class=" text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/forum.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">@lang('app.forum')</p>
                    </a>
                </div>
            </div>
            @if(\App\Profile::usertype() != 2)
            <div class="col-4 mb-2">
                <div class="wrapped_item_mobile">
                    <a href="{{ userThird() ? 'javascript:void(0)' : route('module.front.promotion') }}" class="{{ userThird() ? 'userThird' : '' }} text-center">
                        <div class="avatar avatar-40 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <img src="{{ asset('themes/mobile/img/promotion.png') }}" alt="">
                        </div>
                        <p class="mt-1 small">{{ data_locale('Quà tặng', 'Gift') }}</p>
                    </a>
                </div>
            </div>
            @endif
            {{--@php
                $review = \Modules\Capabilities\Entities\CapabilitiesResult::getLastReviewUser(\Auth::id());
            @endphp
            @if($review)
                <a href="{{ route('module.capabilities.review.user.view_course', ['user_id' => \Auth::id()]) }}"
                    class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay bg-template"></div>
                        <img src="{{ asset('themes/mobile/img/capabilities.png') }}" alt="">
                    </div>
                    <p class="mt-2 small">Competence</p>
                </a>
            @endif--}}
        </div>
    </div>

    {{-- CHƯƠNG TRÌNH THI ĐUA --}}
    @if(\App\Profile::usertype() != 2)
    @if (!$emulation_programs->isEmpty())
        <div class="container">
            <div class="row bg-white p-2 shadow mt-3" id="emulation_program">
                <div class="col-12 px-0 border-bottom">
                    <h6 class="">{{ data_locale('Chương trình thi đua', 'Competition Program') }}
                        <a href="{{ route('frontend.emulation_program') }}" class="float-right small">
                            <i class="material-icons">more_horiz</i>
                        </a>
                    </h6>
                </div>
                <!-- Swiper -->
                <div class="container pt-2">
                    <div class="offer-slide mb-0">
                        <div class="swiper-wrapper">
                            @foreach($emulation_programs as $emulation_program)
                                <div class="swiper-slide item_emulation p-0">
                                    <a href="{{ route('frontend.emulation_program.detail',['id' => $emulation_program->id]) }}">
                                        <img class="image_emulation" src="{{ image_file($emulation_program->image) }}" alt="" class="w-100" height="155px">
                                    </a>
                                    <h6 class="font-weight-normal px-2 name_emulation">
                                        <a href="{{ route('frontend.emulation_program.detail',['id' => $emulation_program->id]) }}">
                                            {{ $emulation_program->name }}
                                        </a>
                                    </h6>
                                    <p class="px-2 mb-0">Mã: {{$emulation_program->code}}</p>
                                    <p class="px-2 mb-0 time_emulation">
                                        <span>{{\Carbon\Carbon::parse($emulation_program->time_start)->format('Y-m-d')}} </span>
                                        <span>đến</span>
                                        <span>{{\Carbon\Carbon::parse($emulation_program->time_end)->format('Y-m-d')}}</span>
                                    </p>
                                    <span class="small float-right mr-2">
                                        <a href="{{ route('frontend.emulation_program.detail',['id' => $emulation_program->id]) }}">
                                            <i class="material-icons vm">arrow_forward</i>
                                        </a>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif

    {{-- <div class="container">
        <div class="row bg-white p-2 shadow mt-3">
            <div class="col-12 px-0 border-bottom">
                <h6 class="">@lang('app.intereste_infor_of_the_year')</h6>
            </div> --}}

            {{--<div class="row text-center pt-2">--}}
                {{-- <div class="col-6 col-md-3 text-center p-1">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="avatar avatar-60 no-shadow border-0">
                                <div class="overlay bg-template"></div>
                                <img src="{{ asset('themes/mobile/img/online-learning.png') }}" alt="">
                            </div>
                            <h3 class="mt-3 mb-0 font-weight-normal">{{ $count_online }}</h3>
                            <p class="text-secondary text-mute">@lang('app.online_course')</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 text-center p-1">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="avatar avatar-60 no-shadow border-0">
                                <div class="overlay bg-template"></div>
                                <img src="{{ asset('themes/mobile/img/offline.png') }}" alt="">
                            </div>
                            <h3 class="mt-3 mb-0 font-weight-normal">{{ $count_offline }}</h3>
                            <p class="text-secondary text-mute">@lang('app.in_house')</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 text-center p-1">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="avatar avatar-60 no-shadow border-0">
                                <div class="overlay bg-template"></div>
                                <img src="{{ asset('themes/mobile/img/ebook.png') }}" alt="">
                            </div>
                            <h3 class="mt-3 mb-0 font-weight-normal">{{ $count_ebook }}</h3>
                            <p class="text-secondary text-mute">@lang('app.ebook')</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 text-center p-1">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="avatar avatar-60 no-shadow border-0">
                                <div class="overlay bg-template"></div>
                                <img src="{{ asset('themes/mobile/img/course.png') }}" alt="">
                            </div>
                            <h3 class="mt-3 mb-0 font-weight-normal">{{ $course_beling_held }}</h3>
                            <p class="text-secondary text-mute">{{ data_locale('Đang tổ chức', 'Courses (on going)') }}</p>
                        </div>
                    </div>
                </div> --}}
           {{-- </div>--}}
        {{-- </div> --}}
    {{-- </div> --}}
@endsection
