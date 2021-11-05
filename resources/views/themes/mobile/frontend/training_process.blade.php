@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.history'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush" id="training-process">
                    @if(count($get_history_course) > 0)
                        @foreach($get_history_course as $item)
                            @php
                                if ($item->course_type == 1){
                                    $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->course_id, \Auth::id());
                                    $result = \Modules\Online\Entities\OnlineCourseComplete::where('course_id', '=', $item->course_id)->where('user_id', '=', Auth::id())->first();
                                    $type = 'Online';
                                    $url = route('themes.mobile.frontend.online.detail',['course_id' => $item->course_id]);
                                }else{
                                    $percent = \Modules\Offline\Entities\OfflineCourse::percent($item->course_id, Auth::id());
                                    $result = \Modules\Offline\Entities\OfflineCourseComplete::where('course_id', '=', $item->course_id)->where('user_id', '=', Auth::id())->first();
                                    $type = 'Offline';
                                    $url = route('themes.mobile.frontend.offline.detail',['course_id' => $item->course_id]);
                                }
                            @endphp
                            <div class="row mb-1 bg-white shadow border">
                                <div class="col-auto align-self-center">
                                    @if($result)
                                        <img src="{{ asset('themes/mobile/img/course_icon.png') }}" alt="" class="avatar-40 is-completed" data-course_id="{{ $item->course_id }}" data-course_type="{{ $item->course_type }}">
                                    @else
                                        <img src="{{ asset('themes/mobile/img/desktop-pc.png') }}" alt="" class="avatar-40 studying">
                                    @endif
                                </div>

                                <div class="col pl-0">
                                    <a href="{{ $url }}">
                                        {{ $item->name }}
                                    </a>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                                    </p>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ round($percent, 2) }} %
                                        <span class="float-right">
                                            {{ $result ? trans('app.completed') : trans('app.uncomplete') }}
                                        </span>
                                    </p>
                                    <p class="text-mute mt-1">
                                        {{ $result ? get_date($result->created_at, 'H:i d/m/Y') : '' }}
                                        <span class="float-right">
                                            {{ $type }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $get_history_course->links('themes/mobile/layouts/pagination') }}
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#training-process').on('click', '.studying', function () {
            Swal.fire({
                title: 'Thông báo',
                width: '100%',
                position: 'center',
                html: 'Bạn chưa hoàn thành khóa học <br> <div class="border-bottom pt-5" style="margin: -15px;"></div>',
                focusConfirm: false,
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.value) {
                    return false;
                }
            });
        });

        $('#training-process').on('click', '.is-completed', function () {
            var course_id = $(this).data('course_id');
            var course_type = $(this).data('course_type');

            Swal.fire({
                title: 'Thông báo',
                width: '100%',
                position: 'center',
                html: 'Chúc mừng bạn đã hoàn thành khóa học. Bạn muốn xem chứng chỉ? <br> <div class="border-bottom pt-5" style="margin: -15px;"></div>',
                showCancelButton: true,
                confirmButtonText: "OK",
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        width: '100%',
                        position: 'center',
                        imageUrl: '/user/trainingprocess/certificate/'+course_id+'/'+course_type+'/{{ Auth::id() }}',
                        imageWidth: '100%',
                        imageHeight: '100%',
                        imageAlt: 'Custom image',
                    });
                    return false;
                }else {
                    return false;
                }
            });
        });
    </script>
@endsection
