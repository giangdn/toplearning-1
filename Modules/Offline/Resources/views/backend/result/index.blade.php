@extends('layouts.backend')

@section('page_title', 'Kết quả đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection
<style>
    .th-second{
        height: 40px;
    }
</style>
@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.management') }}">{{ trans('backend.offline_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span> {{ trans('backend.training_result') }}</span>
        </h2>
    </div>
    <div role="main" id="result">

        @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

        @endif
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    @canany(['offline-course-create', 'offline-course-edit'])
                        <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}" class="btn  btn-info"> <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('backend.info') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-register'])
                        <a href="{{ route('module.offline.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('backend.register') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-teacher'])
                        <a href="{{ route('module.offline.teacher', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-inbox"></i></div>
                            <div>{{ trans('backend.teacher') }}</div>
                        </a>
                    @endcanany
                        <a href="{{ route('module.offline.monitoring_staff', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>Cán bộ theo dõi</div>
                        </a>
                    @canany(['offline-course-attendance'])
                        <a href="{{ route('module.offline.attendance', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>{{ trans('backend.attendance') }}</div>
                        </a>
                    @endcanany
                    {{--@can('offline-course-rating-result')
                        <a href="{{ route('module.rating.result.index', ['course_id' => $course->id, 'type' => 2]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>
                    @endcan--}}
                    @can('offline-course-rating-level-result')
                        <a href="{{ route('module.offline.rating_level.list_report', [$course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>Kết quả đánh giá</div>
                        </a>
                    @endcan
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 ">
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
            <div class="col-md-12 text-right act-btns">
                @if($course->lock_course == 0)
                @canany(['offline-course-result-create'])
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_ket_qua_dao_tao.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-result" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="{{ route('module.offline.result.export_result', ['id' => $course->id]) }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                @endcanany
                @endif
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th rowspan="2" data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th rowspan="2" data-field="code">{{trans('backend.employee_code')}}</th>
                    <th rowspan="2" data-field="name" data-formatter="name_formatter">{{ trans('backend.fullname') }}</th>
                    <th rowspan="2" data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th rowspan="2" data-field="percent" data-align="center" data-formatter="percent_formatter" data-width="5%">{{ trans('backend.join') }}</th>
                    <th colspan="3" data-width="15%">{{ trans('backend.test_score') }}</th>
                    <th rowspan="2" data-field="survey_course" data-width="3%" data-formatter="survey_course_formatter" data-align="center">{{ trans('backend.assessments') }} <br> {{ trans('backend.course') }}</th>
                    <th rowspan="2" data-field="result" data-formatter="result_formatter" data-align="center">{{ trans('backend.result') }}</th>
                    <th rowspan="2" data-field="note" data-formatter="note_formatter">{{ trans('backend.note') }}</th>
                </tr>
                <tr>
                    <th data-field="score_1" class="th-second">Điểm lần 1</th>
                    <th data-field="score_2" data-formatter="score_formatter" class="th-second">Điểm lần 2</th>
                    <th data-field="score" class="th-second">Điểm lần cuối</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.offline.result.import_result', ['id' => $course->id]) }}" method="post" class="form-ajax">
                    <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.training_result') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1)
        }

        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }

        function result_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case -1: return '<span class="text-muted">{{trans("backend.incomplete")}}</span>';
                case 0: return '<span class="text-danger">{{trans("backend.not_completed")}}</span>';
                case 1: return '<span class="text-success">{{trans("backend.finish")}}</span>';
            }
        }

        function percent_formatter(value, row, index) {
            return '<input name="percent" type="text" class="form-control" value="'+ row.percent +'" disabled>';
        }

        function survey_course_formatter(value, row, index) {
            return '<input name="survey_course" type="checkbox" disabled class="check-item" value="" '+ (row.rating_send == 1 ? "checked": "") +'>';
        }

        function score_formatter(value, row, index) {
            return '<input type="text" name="score" {{ (\App\Permission::isUnitManager() || userCan(['offline-course-result-create'])) && $course->lock_course == 0 ? '' : 'disabled' }} data-id="'+ row.id +'" value="" class="form-control is-number change-score">';
        }

        function note_formatter(value, row, index) {
            return '<textarea type="text" name="note" {{ (\App\Permission::isUnitManager() || userCan(['offline-course-result-create'])) && $course->lock_course == 0 ? '' : 'disabled' }} data-id="'+ row.id +'" class="form-control change-note">' + (row.note ? row.note : "") + '</textarea>';
        }

        $('#import-result').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_result', ['id' => $course->id]) }}',
        });

        var ajax_save_score = "{{ route('module.offline.save_score', ['id' => $course->id]) }}";
        var ajax_result_save_note = "{{ route('module.offline.result.save_note', ['id' => $course->id]) }}";
    </script>

    <script src="{{ asset('styles/module/offline/js/result.js') }}"></script>
@endsection
