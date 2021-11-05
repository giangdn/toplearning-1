{{-- @extends('layouts.backend')

@section('page_title', 'Kế hoạch đào tạo tháng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Kế hoạch đào tạo tháng</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form id="form-search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('backend.code_name_course') }}">
                        </div>
                        <div class="col-sm-2 my-1">
                            <select name="training_program_id" id="training_program" class="form-control select2 load-training-program" data-placeholder="{{ trans('backend.training_program') }}">
                            </select>
                        </div>
                        <div class="col-sm-2 my-1">
                            <select name="level_subject_id" id="level_subject" class="form-control select2 load-level-subject" data-placeholder="Khóa học">
                            </select>
                        </div>

                        <div class="col-sm-2 my-1">
                            <select name="subject_id" id="subject" class="form-control select2 load-subject" data-training-program="" data-level-subject="" data-placeholder="Mảng nghiệp vụ">
                            </select>
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('course-plan-approved')
                            <button class="btn btn-success approved" data-model="el_course_plan" data-status="1">
                                <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                            </button>
                            <button class="btn btn-danger approved" data-model="el_course_plan" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> {{ trans('backend.deny') }}
                            </button>
                        @endcan
                    </div>

                    {{--<div class="btn-group">
                        <button class="btn btn-primary publish" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning publish" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>--}}

                    <div class="btn-group">
                        @can('course-plan-create')
                            <a href="{{ route('module.course_plan.create', ['course_type' => 1]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') .' '. trans("backend.onlines") }}</a>
                            <a href="{{ route('module.course_plan.create', ['course_type' => 2]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') .' '. trans("backend.offline") }}</a>
                        @endcan
                        @can('course-plan-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table-course-plan">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                   {{-- <th data-field="isopen" data-sortable="true" data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('backend.open') }}</th>--}}
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('backend.course') }}</th>
                    <th data-field="course_type" data-sortable="true" data-formatter="course_type_formatter">{{ trans('backend.type_course') }}</th>
                    {{--<th data-align="center" data-formatter="action_plan_formatter" data-width="5%">{{ trans('backend.plan') }}</th>--}}
                    <th data-field="subject_name">{{ trans('backend.document') }}</th>
                    <th data-field="register_deadline" data-sortable="true" data-align="center" data-width="5%">{{ trans('backend.register_deadline') }}</th>
                    <th data-formatter="date_formatter" data-align="center" data-width="18%">{{ trans('backend.time') }}</th>
                    <th data-align="center" data-formatter="created_by_formatter">{{ trans('backend.code_user_create') }}</th>
                    <th data-field="created_at2" data-align="center" data-width="5%">{{ trans('backend.created_at') }}</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('backend.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('backend.status') }}</th>
                    <th data-align="center" data-formatter="convert_formatter">Chuyển đổi</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_course_plan" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function date_formatter(value, row, index) {
            return row.start_date  + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
        }

        function isopen_formatter(value, row, index) {
            return row.isopen == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-exclamation-triangle text-warning"></i> ';
        }

        function action_plan_formatter(value, row, index) {
            return (row.in_plan) ? '{{ trans("backend.yes") }}' : '{{ trans("backend.no") }}';
        }

        function created_by_formatter(value, row, index) {
            return row.user_name;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2 || null: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        function course_type_formatter(value, row, index) {
            return (row.course_type == 1) ? 'Trực tuyến' : 'Tập trung';
        }

        function convert_formatter(value, row, index) {
            if (row.status == 1){
                if(row.status_convert == 1){
                    return 'Đã chuyển';
                }
                return '<a href="javascript::void(0)" class="form-control convert" data-course_id="'+ row.id +'" data-course_type="'+ row.course_type +'"> <i class="fa fa-exchange-alt"></i></a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.course_plan.getdata') }}',
            remove_url: '{{ route('module.course_plan.remove') }}'
        });

        $('#training_program').on('change', function () {
            var training_program_id = $('#training_program option:selected').val();
            $("#level_subject").empty();
            $("#level_subject").data('training-program', training_program_id);
            $('#level_subject').trigger('change');

            $("#subject").empty();
            $("#subject").data('training-program', training_program_id);
            $("#subject").data('level-subject', '');
            $('#subject').trigger('change');
        });

        $('#level_subject').on('change', function () {
            var training_program_id = $('#training_program option:selected').val();
            var level_subject_id = $('#level_subject option:selected').val();
            $("#subject").empty();
            $("#subject").data('training-program', training_program_id);
            $("#subject").data('level-subject', level_subject_id);
            $('#subject').trigger('change');
        });

        $('.publish').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.course_plan.ajax_isopen_publish') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.course_plan.approve') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });

        $('#table-course-plan').on('click', '.convert', function () {
            var course_id = $(this).data('course_id');
            var course_type = $(this).data('course_type');

            $.ajax({
                url: '{{ route('module.course_plan.convert') }}',
                type: 'post',
                data: {
                    course_id: course_id,
                    course_type: course_type
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>
{{-- @endsection --}}
