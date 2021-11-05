@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.account'))

@section('content')
    <div class="container">
        <div class="card bg-template shadow mt-1 mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto">
                        <figure class="avatar avatar-60">
                            <a href="javascript:void(0)" class="" data-toggle="modal" data-target="#modalChangeAvatar">
                                <img src="{{ \App\Profile::avatar() }}" alt="" class="avatar-60">
                            </a>
                        </figure>
                    </div>
                    <div class="col pl-0 align-self-center">
                        <p class="mb-1" id="info-user">
                            {{ \App\Profile::fullname() }}
                            <span class="float-right">
                                {{ $user_point ? $user_point->point : '' }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                            </span>
                        </p>
                        <p class="text-mute">{{ \App\Profile::usercode() }}</p>
                    </div>
                </div>
                <div class="text-mute text-center">
                    {{ @$title->name }}
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto pr-0">
                        <a href="{{ route('themes.mobile.frontend.profile.qr_code') }}"><img src="{{ asset('themes/mobile/img/qrcode-user.png') }}" alt="" class="avatar-40"></a>
                    </div>
                    <div class="col p-0 text-center">
                        <span class="font-weight-bold">@lang('app.rank')</span> (@lang('app.learning_points')) <br>
                        <span class="font-weight-bold">{{ $user_rank }}</span>
                    </div>
                    <div class="col-auto pl-0 text-center">
                        <span class="font-weight-bold">@lang('app.total')</span> <br>
                        <b>{{ $total_user->count() }}</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row bg-white shadow p-2 mt-3">
            <div class="col-12 px-0">
                <h6>@lang('career.career_roadmap')
                    <a href="{{ route('module.career_roadmap.frontend') }}" class="float-right small">
                        <i class="material-icons">more_horiz</i>
                    </a>
                </h6>
            </div>
            <div class="col-12 px-0 pt-2">
                <ul class="list-group list-group-flush border-top">
                    @foreach($roadmaps as $roadmap)
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col align-self-center">
                                    <h6 class="font-weight-normal mb-1">{{ $roadmap->name }}</h6>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row bg-white shadow p-2 mt-3">
            <div class="col-12 px-0 border-bottom">
                <h6 class="">@lang('app.top_students_high_scores')</h6>
            </div>
            <div class="container px-1 pt-2">
                <!-- Swiper -->
                <div class="swiper-container offer-slide">
                    <div class="swiper-wrapper">
                        @foreach($five_user_max_point as $item)
                        <div class="swiper-slide">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto pr-0">
                                            <img src="{{ \App\Profile::avatar($item->user_id) }}" alt="" class="avatar avatar-50 border-0">
                                        </div>
                                        <div class="col align-self-center">
                                            <p class="mb-1">
                                                {{ \App\Profile::fullname($item->user_id) }}
                                            </p>
                                            <p class="text-mute">
                                                ({{ \App\Profile::usercode($item->user_id) }})
                                                <span class="float-right">
                                                    {{ $item->point }}
                                                    <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row bg-white shadow p-2 mt-3">
            <div class="col-12 px-0">
                <h6 class="">
                    @lang('app.point_accumulation_history')
                </h6>
            </div>
            <div class="col-12 px-0 pt-2">
                <ul class="list-group list-group-flush border-top">
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col align-self-center">
                                <h6 class="font-weight-normal">
                                    <a href="{{ route('themes.mobile.frontend.accumulated_from_course') }}" class="">
                                        @lang('app.accumulated_from_course')
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col align-self-center">
                                <h6 class="font-weight-normal">
                                    <a href="{{ route('themes.mobile.frontend.accumulated_from_video') }}" class="">
                                        @lang('app.accumulated_from_video')
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col align-self-center">
                                <h6 class="font-weight-normal">
                                    <a href="{{ route('themes.mobile.frontend.accumulated_from_bonus_points') }}" class="">
                                        @lang('app.accumulated_from_bonus_points')
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row bg-white shadow p-2 mt-3">
            <div class="col-12 px-0">
                <h6 class="">
                    @lang('app.learning_history')
                    <a href="{{ route('themes.mobile.frontend.training_process') }}" class="float-right small">
                        <i class="material-icons">more_horiz</i>
                    </a>
                </h6>
            </div>
            <div class="col-12 px-0 pt-2">
                <ul class="list-group list-group-flush border-top">
                    @foreach($get_history_course as $course)
                        @php
                            if ($course->course_type == 1){
                                $type = 'Online';
                                $url = route('themes.mobile.frontend.online.detail',['course_id' => $course->course_id]);
                            }else{
                                $type = 'Offline';
                                $url = route('themes.mobile.frontend.offline.detail',['course_id' => $course->course_id]);
                            }
                        @endphp
                    <li class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto pr-0">
                                <img src="{{ image_file($course->image) }}" alt="" class="avatar avatar-50 no-shadow border-0">
                            </div>
                            <div class="col align-self-center">
                                <a href="{{ $url }}">
                                    <h6 class="font-weight-normal mb-1">{{ $course->name }}</h6>
                                </a>
                                <p class="text-mute text-secondary">
                                    {{ $course->code }}
                                    <span class="float-right">{{ $type }}</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-12 px-0 text-right">
                {{ $get_history_course->links('themes/mobile/layouts/pagination') }}
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div id="modalInfoUser" class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('app.info') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="_ttl123_custom mt-0">
                        <b>@lang('app.login_code'):</b>
                        <span class="_ttl122_custom">
                            {{ $user_name }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.employee_code'):</b>
                        <span class="_ttl122_custom">
                            {{ $user->code }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.full_name'):</b>
                        <span class="_ttl122_custom">
                            {{ $user->lastname .' '. $user->firstname }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.dob'):</b>
                        <span class="_ttl122_custom">
                            {{ get_date($user->dob) }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.gender'):</b>
                        <span class="_ttl122_custom">
                            {{ $user->gender }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.title'):</b>
                        <span class="_ttl122_custom">
                            @if(isset($title->name)) {{ $title->name }} @endif
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.unit'):</b>
                        <span class="_ttl122_custom">
                            @if(isset($unit->name)) {{ $unit->name }} @endif
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('backend.day_work'):</b>
                        <span class="_ttl122_custom">
                            {{ get_date($user->join_company) }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.permanent_residence'):</b>
                        <span class="_ttl122_custom">
                            {{ $user->address }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <a href="javascript:void(0)" class="change-info" data-key="current_address" data-value-old="{{ !is_null($user_meta('current_address')) ? $user_meta('current_address')->value : '' }}"><i class="material-icons">edit</i></a>
                        <b>@lang('app.current_address'):</b>
                        <span class="_ttl122_custom">
                            {{ !is_null($user_meta('current_address')) ? $user_meta('current_address')->value : '' }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <a href="javascript:void(0)" class="change-info" data-key="phone" data-value-old="{{ $user->phone }}"><i class="material-icons">edit</i></a>
                        <b>@lang('app.phone'):</b>
                        <span class="_ttl122_custom">
                            {{ $user->phone }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <a href="javascript:void(0)" class="change-info" data-key="email" data-value-old="{{ $user->email() }}"><i class="material-icons">edit</i></a>
                        <b>Email:</b>
                        <span class="_ttl122_custom">
                            {{ $user->email() }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <a href="javascript:void(0)" class="change-info" data-key="name_contact_person" data-value-old="{{ !is_null($user_meta('name_contact_person')) ? $user_meta('name_contact_person')->value : '' }}"><i class="material-icons">edit</i></a>
                        <b>@lang('app.name_contact_person'):</b>
                        <span class="_ttl122_custom">
                            {{ !is_null($user_meta('name_contact_person')) ? $user_meta('name_contact_person')->value : '' }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <a href="javascript:void(0)" class="change-info" data-key="phone_contact_person" data-value-old="{{ !is_null($user_meta('phone_contact_person')) ? $user_meta('phone_contact_person')->value : '' }}"><i class="material-icons">edit</i></a>
                        <b>@lang('app.phone_contact_person'):</b>
                        <span class="_ttl122_custom">
                            {{ !is_null($user_meta('phone_contact_person')) ? $user_meta('phone_contact_person')->value : '' }}
                        </span>
                    </div>
                    <div class="_ttl123_custom">
                        <b>@lang('app.code'):</b>
                        <span class="_ttl122_custom">
                            {{ $user->id_code }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-change-info" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('module.frontend.user.change_info') }}" method="post" id="form-change-info" enctype="multipart/form-data" class="form-ajax">
                @csrf
                <input type="hidden" name="key" value="">
                <input type="hidden" name="value_old" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Đổi thông tin</h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="value_new">Giá trị cũ</label>
                            </div>
                            <div class="col-md-8" id="value-old"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="value_new">Giá trị thay đổi</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="value_new" id="value-new" type="text" class="form-control" placeholder="Giá trị thay đổi" autocomplete="off" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="value_new">{{ trans('backend.note') }}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="note" id="note" type="text" class="form-control" placeholder="{{ trans('backend.note') }}" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('lacore.save') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#info-user').on('click', function() {
            $('#modalInfoUser').modal();
        });

        $('.change-info').on('click', function (event) {
            event.preventDefault();
            var key = $(this).data('key');
            var value_old = $(this).data('value-old');

            $('#modal-change-info #value-old').text(value_old).trigger('change');
            $('#modal-change-info input[name=value_old]').val(value_old).trigger('change');
            $('#modal-change-info input[name=key]').val(key).trigger('change');

            $('#modalInfoUser .close').trigger('click');
            $('#modal-change-info').modal();
            return false;
        });
    </script>
@endsection
