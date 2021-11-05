<div class="row mt-3">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{route('module.frontend.user.my_course',['type'=>1])}}">@lang('app.onl_course')</a></h5>
                <h2>{{ $countMyOnlineCourse->count() }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{route('module.frontend.user.my_course',['type'=>2])}}">@lang('app.off_course')</a></h5>
                <h2>{{ $countMyOfflineCourse->count() }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/training.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{ route('module.quiz') }}">@lang('app.quiz')</a></h5>
                <h2>{{ $count_quiz }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/online-course.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{route('module.frontend.user.point_hist')}}">@lang('app.your_accumulated_points')</a></h5>
                <h2>{{ $point }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/knowledge.svg') }}" alt="">
            </div>
        </div>
    </div>
</div>
