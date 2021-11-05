@extends('layouts.backend')

@section('page_title', 'Kết quả đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ $course_type == 1 ? route('module.online.management') : route('module.offline.management') }}">{{ trans("backend.course") }} {{ $course_type == 1 ? 'online' : 'tập trung' }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ $course_type == 1 ? route('module.online.edit', ['id' => $course_id]) : route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_level.result.list_course_register', [$course_id, $course_type]) }}">Kết quả đánh giá cấp độ</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $full_name }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    <a href="{{ $course_type == 1 ? route('module.online.edit', ['id' => $course->id]) : route('module.offline.edit', ['id' => $course->id]) }}" class="btn btn-info">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans("backend.info") }}</div>
                    </a>
                    @if ($course_type == 1)
                        <a href="{{ route('module.online.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>Ghi danh nội bộ</div>
                        </a>
                        <a href="{{ route('module.online.register_secondary', ['id' => $course->id]) }}" class="btn
                        btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>Ghi danh bên ngoài</div>
                        </a>
                        <a href="{{ route('module.online.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans("backend.training_result") }}</div>
                        </a>

                        {{--<a href="{{ route('module.rating.result.index', [$course->id, 1]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>--}}
                    @endif
                    @if($course_type == 2)
                        <a href="{{ route('module.offline.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans("backend.register") }}</div>
                        </a>
                        <a href="{{ route('module.offline.teacher', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-inbox"></i></div>
                            <div>{{ trans("backend.teacher") }}</div>
                        </a>
                        <a href="{{ route('module.offline.monitoring_staff', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>Cán bộ theo dõi</div>
                        </a>
                        <a href="{{ route('module.offline.attendance', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>{{ trans("backend.attendance") }}</div>
                        </a>
                        <a href="{{ route('module.offline.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans("backend.training_result") }}</div>
                        </a>
                        {{--<a href="{{ route('module.rating.result.index', [$course->id, 2]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>--}}
                    @endif
                </div>
            @endif
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code">{{ trans("backend.employee_code") }}</th>
                    <th data-field="full_name">{{ trans("backend.fullname") }}</th>
                    <th data-field="unit_name">Đơn vị công tác</th>
                    <th data-field="parent_unit_name">Đơn vị quản lý</th>
                    <th data-field="rating_level" data-align="center" >Cấp độ đánh giá</th>
                    <th data-field="rating_time" data-align="center" >Thời gian đánh giá</th>
                    <th data-field="rating_status" data-align="center" >Trình trạng</th>
                    <th data-field="result" data-width="10%"  data-align="center" data-formatter="result_formatter">Kết quả</th>
                    <th data-field="export_word" data-width="10%"  data-align="center" data-formatter="export_word_formatter">Export</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function result_formatter(value, row, index) {
            return '<a href="'+ row.result +'" class="btn btn-info"> <i class="fa fa-eye"></i> Chi tiết </a>';
        }

        function export_word_formatter(value, row, index) {
            let str = '';
            if (row.export_word) {
                str += ' <a href="'+ row.export_word +'" class="btn btn-link"><i class="fa fa-download"></i> In Word</a>';
            }
            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_level.result.getdata', [$course_id, $course_type, $user_id]) }}',
        });
    </script>
@endsection
