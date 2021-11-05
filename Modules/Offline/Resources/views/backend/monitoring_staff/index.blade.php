@extends('layouts.backend')

@section('page_title', 'Quản lý cán bộ theo dõi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.management') }}">{{ trans('backend.offline_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span>Quản lý cán bộ theo dõi</span>
        </h2>
    </div>
    <div role="main" id="monitoring_staff_offline">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    @canany(['offline-course-create', 'offline-course-edit'])
                        <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
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
                        <a href="{{ route('module.offline.teacher', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-inbox"></i></div>
                            <div>{{ trans('backend.teacher') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-attendance'])
                        <a href="{{ route('module.offline.attendance', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>{{ trans('backend.attendance') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-result'])
                        <a href="{{ route('module.offline.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans('backend.training_result') }}</div>
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
            <div class="col-md-12">
                <form class="form-inline form-search mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('backend.enter_code_name') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if($course->lock_course == 0)
                        <a href="javascript:void(0)" id="import_monitoring_staff" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code">{{ trans('backend.employee_code') }}</th>
                    <th data-sortable="true" data-width="25%" data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="note" data-formatter="note_formatter">{{ trans('backend.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.save_monitoring_staff', ['id' => $course->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Cán bộ theo dõi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>Cán bộ <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="user_id" id="user_id" class="form-control load-user" data-placeholder="Chọn cán bộ" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        @if($course->lock_course == 0)
                        <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_monitoring_staff', ['id' => $course->id]) }}',
            remove_url: '{{ route('module.offline.remove_monitoring_staff', ['id' => $course->id]) }}'
        });

        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }

        function note_formatter(value, row, index) {
            return '<textarea type="text" name="note" data-id="'+ row.id +'" class="form-control change-note" {{ $course->lock_course == 0 ? '' : 'readonly' }}>'+ (row.note ? row.note : "") +'</textarea>';
        }

        $('#import_monitoring_staff').on('click', function() {
            $('#modal-import').modal();
        });

        $('#monitoring_staff_offline').on('change', '.change-note', function() {
            var note = $(this).val();
            var off_monitoring_staff_id = $(this).data('id');

            $.ajax({
                url: '{{ route('module.offline.monitoring_staff.save_note', ['id' => $course->id]) }}',
                type: 'post',
                data: {
                    note: note,
                    off_monitoring_staff_id : off_monitoring_staff_id,
                },

            }).done(function(data) {

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
