@extends('layouts.backend')

@section('page_title', 'Kết quả đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.management') }}">{{ trans('backend.online_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.edit', ['id' => $course->id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans("backend.training_result") }}</span>
        </h2>
    </div>
    <div role="main" id="result">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    @canany(['online-course-create', 'online-course-edit'])
                    <a href="{{ route('module.online.edit', ['id' => $course->id]) }}" class="btn  btn-info"> <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans("backend.info") }}</div>
                    </a>
                    @endcanany
                    @can('online-course-register')
                        <a href="{{ route('module.online.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>Ghi danh nội bộ</div>
                        </a>
                        <a href="{{ route('module.online.register_secondary', ['id' => $course->id]) }}" class="btn
                        btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>Ghi danh bên ngoài</div>
                        </a>
                    @endcan
                    {{--@can('online-course-rating-result')
                    <a href="{{ route('module.rating.result.index', ['course_id' => $course->id, 'type' => 1]) }}" class="btn btn-info">
                        <div><i class="fa fa-star"></i></div>
                        <div>{{ trans("backend.result_of_evaluation") }}</div>
                    </a>
                    @endcan--}}
                    @can('online-course-rating-level-result')
                        <a href="{{ route('module.online.rating_level.list_report', [$course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>Kết quả đánh giá</div>
                        </a>
                    @endcan
                </div>
            @endif
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}"
                                class="form-control load-unit"
                                data-placeholder="-- ĐV cấp {{$i}} --"
                                data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                            </select>
                        </div>
                    @endfor

                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <input name="start_date" type="text" class="datepicker form-control w-100" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                    </div>
                    <div class="w-25">
                        <input name="end_date" type="text" class="datepicker form-control w-100" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                    </div>
                    <div class="w-25">
                        <select name="status" class="form-control select2" data-placeholder="-- {{ trans('backend.status') }} --">
                            <option value=""></option>
                            <option value="0">{{ trans('backend.inactivity') }}</option>
                            <option value="1">{{ trans('backend.doing') }}</option>
                            <option value="2">{{ trans('backend.probationary') }}</option>
                            <option value="3">{{ trans('backend.pause') }}</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ route('module.online.export_result', ['id' => $course->id]) }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="code" data-width="5%">{{ trans("backend.employee_code") }}</th>
                    <th data-field="email" data-width="25%">{{ trans("backend.employee_email") }}</th>
                    <th data-field="name" data-width="25%">{{ trans("backend.fullname") }}</th>
                    @foreach($activities as $activity)
                        <th data-field="activity_{{$activity->id}}" data-width="10%" data-align="center">
                            {{ trans("backend.activiti") }} {{ $activity->name }}
                        </th>

                        @if ($activity->activity_id == 1)
                            <th data-field="score_{{$activity->id}}" data-width="10%" data-align="center">{{ trans("backend.score") }} {{ $activity->name }}</th>
                        @endif
                    @endforeach
                    <th data-field="score" data-width="7%" data-align="center">{{ trans("backend.test_score") }}</th>
                    <th data-field="result" data-width="10%" data-align="center">{{ trans("backend.result") }}</th>
                    <th data-align="center" data-formatter="view_history_learning_formatter" >Lịch sử học</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1)
        }

        function result_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case -1: return '<span class="text-muted">{{ trans("backend.incomplete") }}</span>';
                case 0: return '<span class="text-danger">{{ trans("backend.not_complete") }}</span>';
                case 1: return '<span class="text-success">{{ trans("backend.complete") }}</span>';
            }
        }

        function survey_course_formatter(value, row, index) {
            return '<input name="survey_course" type="checkbox" disabled class="check-item" value="" '+ (row.rating_send == 1 ? "checked": "") +'>';
        }

        function view_history_learning_formatter(value, row, index) {
            if(row.view_history_learning){
                return '<a href="'+ row.view_history_learning +'" class="btn btn-info"> <i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_result', ['id' => $course->id]) }}',
        });

        $('#result').on('click', '.check-complete', function () {
            var activity_id = $(this).data('activity_id');
            var user_id = $(this).data('user_id');
            var user_type = $(this).data('user_type');

            $.ajax({
                url: '{{ route('module.online.result.update_activity_complete', ['id' => $course->id]) }}',
                type: 'post',
                data: {
                    activity_id: activity_id,
                    user_id : user_id,
                    user_type: user_type
                },

            }).done(function(data) {
                table.refresh();
                return false;

            }).fail(function(data) {

                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });

    </script>
@endsection
