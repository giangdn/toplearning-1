@php
    $tabs = Request::segment(1);
@endphp
<nav class="vertical_nav vertical-fontend">
    <div class="left_section menu_left menu-left-frontend" id="js-menu" >
        <div class="left_section left-menu-frontend" id="nav-courses">
            <ul>
                <li class="menu--item">
                    <a href="{{route('module.online.detail_online',['id' => $course_id])}}" class="menu--link @if ($tabs == "")
                            active
                        @endif" title="Home">
                        <i class='uil uil-home-alt menu--icon'></i>
                        <span class="menu--label">Trở lại</span>
                    </a>
                </li>
                @foreach($lessons_course as $lesson_course)
                    @php
                        $get_lesson_activities = $item->getActivitiesOfLesson($lesson_course->id);
                    @endphp
                    @foreach ($get_lesson_activities as $activity)
                        @php
                            $bbb = \Modules\VirtualClassroom\Entities\VirtualClassroom::find($activity->subject_id);
                            $parts = $part($activity->subject_id);
                        @endphp
                        <li class="menu--item">
                            <div class="row">
                                <div class="col-10 pr-0">
                                    @if($activity->activity_id == 1)
                                        <a
                                            {{-- target="_blank" --}}
                                            href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id,'lesson' => $lesson_course->id]) }}"
                                            class="menu--link"
                                            data-turbolinks="false"
                                        >
                                            <div class="section-header-left">
                                                <span class="section-title-wrapper">
                                                    <i class="uil uil-suitcase-alt crse_icon menu--icon"></i>
                                                    <span class="section-title-text menu--label">{{ $activity->name }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                                    @if($activity->activity_id == 2)
                                        @if(is_null($parts))
                                            <a href="#" class="menu--link">
                                                <div class="section-header-left">
                                                    <span class="section-title-wrapper">
                                                        <i class="uil uil-file-check crse_icon menu--icon"></i>
                                                        <span class="section-title-text menu--label">
                                                            {{ $activity->name .' ('. data_locale('Kỳ thi đã kết thúc hoặc Bạn chưa đăng kí kỳ thi', 'The exam has ended or You have not registered for it')  .')' }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </a>
                                        @elseif(isset($parts) && $parts->start_date > date('Y-m-d H:i:s'))
                                            <a href="#" class="menu--link">
                                                <div class="section-header-left">
                                                    <span class="section-title-wrapper">
                                                        <i class="uil uil-file-check crse_icon menu--icon"></i>
                                                        <span class="section-title-text menu--label">
                                                            {{ $activity->name .' ('. data_locale('Kỳ thi chưa tới giờ', 'Quiz is not yet time') .')' }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                            class="menu--link" data-turbolinks="false">
                                                <div class="section-header-left">
                                                    <span class="section-title-wrapper">
                                                        <i class="uil uil-file-check crse_icon menu--icon"></i>
                                                        <span class="section-title-text menu--label">{{ $activity->name }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        @endif
                                    @endif
                                    @if($activity->activity_id == 3)
                                        <a href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                            class="menu--link" data-turbolinks="false">
                                            <div class="section-header-left">
                                                <span class="section-title-wrapper">
                                                    <i class="uil uil-file crse_icon menu--icon"></i>
                                                    <span class="section-title-text menu--label">{{ $activity->name }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                                    @if($activity->activity_id == 4)
                                        <a href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                            class="menu--link" data-turbolinks="false">
                                            <div class="section-header-left">
                                                <span class="section-title-wrapper">
                                                    <i class="uil uil-link crse_icon menu--icon"></i>
                                                    <span class="section-title-text menu--label">{{ $activity->name }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                                    @if($activity->activity_id == 5)
                                        <a href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                            class="menu--link" data-turbolinks="false">
                                            <div class="section-header-left">
                                                <span class="section-title-wrapper">
                                                    <i class="uil uil-video crse_icon menu--icon"></i>
                                                    <span class="section-title-text menu--label">{{ $activity->name }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                                    @if($activity->activity_id == 6 && isset($bbb))
                                        @if($bbb->start_date > date('Y-m-d H:i:s'))
                                            <a href="#" class="menu--link">
                                                <div class="section-header-left">
                                                    <span class="section-title-wrapper">
                                                        <i class="uil uil-skip-forward crse_icon menu--icon"></i>
                                                        <span class="section-title-text menu--label">
                                                            {{ $activity->name .' ('. data_locale('Lớp học chưa tới giờ', 'Class is not yet time') .')' }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </a>
                                        @elseif($bbb->end_date < date('Y-m-d H:i:s'))
                                            <a href="#" class="menu--link">
                                                <div class="section-header-left">
                                                    <span class="section-title-wrapper">
                                                        <i class="uil uil-skip-forward crse_icon menu--icon"></i>
                                                        <span class="section-title-text menu--label text-black-50">
                                                            {{ $activity->name .' ('. data_locale('Lớp học đã kết thúc', 'Class has ended') .')' }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </a>
                                        @else
                                            <a href="javascript:void(0)"
                                                class="go-bbb menu--link"
                                                data-turbolinks="false" data-url="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}">
                                                <div class="section-header-left">
                                                    <span class="section-title-wrapper">
                                                        <i class="uil uil-skip-forward crse_icon menu--icon"></i>
                                                        <span class="section-title-text menu--label">{{ $activity->name }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-2 p-0">
                                    <span class="opts_account_course">
                                        @if($activity->isComplete(getUserId(), getUserType()))
                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="h-auto ml-0 mt-2" style="width: 30px;">
                                        @else
                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="h-auto ml-0 mt-2" style="width: 30px;">
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<script>
    $('#nav-courses').on('click', '.go-bbb', function () {
            var url = $(this).data('url');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Thông báo',
                text: "Anh/Chị có chắc chắn muốn tham gia khóa học này?",
                showCancelButton: true,
                confirmButtonText: 'Tham gia',
                cancelButtonText: 'Không tham gia',
            }).then((result) => {
                if (result.value) {
                    window.open(url, "_blank");
                }
            })
        });
</script>
