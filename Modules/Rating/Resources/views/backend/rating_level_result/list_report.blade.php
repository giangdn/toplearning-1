@extends('layouts.backend')

@section('page_title', 'Kết quả đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ $course_type == 1 ? route('module.online.management') : route('module.offline.management') }}">{{ trans("backend.course") }} {{ $course_type == 1 ? 'online' : 'tập trung' }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ $course_type == 1 ? route('module.online.edit', ['id' => $course_id]) : route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Kết quả đánh giá</span>
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

                        @can('online-course-rating-level-result')
                            <a href="{{ route('module.rating_level.list_report', [$course->id, 1]) }}" class="btn btn-info">
                                <div><i class="fa fa-star"></i></div>
                                <div>Kết quả đánh giá</div>
                            </a>
                        @endcan
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

                        @can('offline-course-rating-level-result')
                            <a href="{{ route('module.rating_level.list_report', [$course->id, 2]) }}" class="btn btn-info">
                                <div><i class="fa fa-star"></i></div>
                                <div>Kết quả đánh giá</div>
                            </a>
                        @endcan
                    @endif
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="Tên đánh giá">
                    </div>

                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" id="table-list-report">
            <thead>
                <tr>
                    <th data-field="rating_name" data-formatter="rating_name_formatter" >Tên đánh giá</th>
                    <th data-field="level" data-align="center">Cấp độ</th>
                    <th data-field="count_user" data-align="center">{{trans('backend.join')}} / {{trans('backend.object')}}</th>
                    <th data-field="export" data-formatter="export_formatter" data-align="center">Báo cáo</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function export_formatter(value, row, index) {
            return '<a href="'+ row.export +'" class="btn btn-info"> <i class="fa fa-download"></i> Tải về </a>';
        }

        function rating_name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="get-list-user-rating text-primary" data-course_rating_level_id="'+ row.id +'">'+ row.rating_name +'</a>';
        }

        var table_list_report = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_level.list_report.getdata', [$course_id, $course_type]) }}',
            table: '#table-list-report',
        });
    </script>
@endsection
