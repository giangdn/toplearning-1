@extends('layouts.backend')

@section('page_title', 'Kết quả đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.offline.management') }}">Khóa học tập trung</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span>Kết quả đánh giá</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
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

        <br>
        <table class="tDefault table table-hover bootstrap-table" id="table-list-user-rating">
            <thead>
            <tr>
                <th data-field="code">{{ trans("backend.employee_code") }}</th>
                <th data-field="full_name">{{ trans("backend.fullname") }}</th>
                <th data-field="unit_name">Đơn vị công tác</th>
                <th data-field="parent_unit_name">Đơn vị quản lý</th>
                <th data-field="object_type">Vai trò</th>
                <th data-field="object_rating">Đối tượng đánh giá</th>
                <th data-field="rating_level" data-align="center">Cấp độ đánh giá</th>
                <th data-field="rating_time" data-align="center">Thời gian đánh giá</th>
                <th data-field="rating_status" data-align="center">Trình trạng</th>
                <th data-field="result" data-width="10%" data-align="center" data-formatter="result_formatter">Chi tiết</th>
                <th data-field="export_word" data-width="10%" data-align="center" data-formatter="export_word_formatter">Export</th>
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

        function export_word_formatter(value, row, index) {
            let str = '';
            if (row.export_word) {
                str += ' <a href="'+ row.export_word +'" class="btn btn-link"><i class="fa fa-download"></i> In Word</a>';
            }
            return str;
        }

        function result_formatter(value, row, index) {
            if (row.result_url){
                return '<a href="javascript:void(0)" class="btn load-modal" data-url="'+ row.result_url +'"> <i class="fa fa-eye"></i> </a>';
            }
            return '';
        }

        var table_list_report = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.rating_level.list_report.getdata', [$course_id]) }}',
            table: '#table-list-report',
        });

        $('#table-list-user-rating').hide();
        $('#table-list-report').on('click', '.get-list-user-rating', function () {
            var course_rating_level_id = $(this).data('course_rating_level_id');

            $('#table-list-user-rating').show();
            $('#table-list-user-rating').bootstrapTable('destroy');

            var table_list_user_rating = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: base_url + '/admin-cp/offline/rating-level/{{ $course_id }}/list-user-rating/'+course_rating_level_id+'/getdata',
                table: '#table-list-user-rating',
            });
        });
    </script>
@endsection
