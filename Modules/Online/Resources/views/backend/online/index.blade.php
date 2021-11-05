{{-- @extends('layouts.backend')

@section('page_title', trans('backend.online_course'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.online_course') }}</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        <div class="row">
            <div class="col-md-12 mb-2">
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
                            <select name="level_subject_id" id="level_subject" class="form-control select2 load-level-subject" data-placeholder="{{ trans('backend.type_subject') }}">
                            </select>
                        </div>
                        <div class="col-sm-2 my-1">
                            <select name="subject_id" id="subject" class="form-control select2 load-subject" data-training-program="" data-level-subject="" data-placeholder="{{ trans('backend.subject') }}">
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
                        <button class="btn btn-primary" onclick="lockCourse(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('lacore.disable') }}
                        </button>
                        <button class="btn btn-warning" onclick="lockCourse(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('lacore.enable') }}
                        </button>
                    </div>

                    @canany(['online-course-create', 'online-course-edit'])
                        <div class="btn-group">
                            <button class="btn btn-success" id="send-mail-approve">
                                <i class="fa fa-send"></i> {{ trans('backend.send_mail_approve') }}
                            </button>

                            <button class="btn btn-success" id="send-mail-change">
                                <i class="fa fa-send"></i> {{ trans('backend.send_mail_change') }}
                            </button>
                        </div>
                    @endcan

                    @can('online-course-approve')
                        <div class="btn-group">
                            <button class="btn btn-success approved" data-model="el_online_course" data-status="1">
                                <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                            </button>
                            <button class="btn btn-danger approved" data-model="el_online_course" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> {{ trans('backend.deny') }}
                            </button>
                        </div>
                    @endcan

                    @can('online-course-status')
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        </div>
                    @endcan

                    <div class="btn-group">
                        @can('online-course-duplicate')
                            <button class="btn btn-success copy">
                                <i class="fa fa-plus-circle"></i> {{ trans('lacore.copy') }}
                            </button>
                        @endcan
                        @can('online-course-create')
                            <a href="{{ route('module.online.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('online-course-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="isopen" data-sortable="true" data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('backend.open_off') }}</th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('backend.course') }}</th>
                    <th data-align="center" data-formatter="action_plan_formatter" data-width="5%">{{ trans('backend.plan') }}</th>
                    <th data-field="subject_name">{{ trans('backend.subject') }}</th>
                    <th data-field="register_deadline" data-sortable="true" data-align="center" data-width="5%">{{ trans('backend.register_deadline') }}</th>
                    <th data-formatter="date_formatter" data-align="center" data-width="18%">{{ trans('backend.time') }}</th>
                    <th data-field="created_at2" data-align="center" data-width="5%">{{ trans('backend.created_at') }}</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('backend.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('backend.status') }}</th>
                    <th data-field="lock_course" data-align="center" data-formatter="lock_formatter" data-width="5%">{{ trans('lacore.disable') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="register_formatter" data-width="5%">{{ trans('backend.register') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lacore.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lacore.editor') }}</th>
                </tr>
            </thead>
        </table>
    </div>
{{--    @include('online::modal.approved_step')--}}
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name + ' (' + row.code + ') </a>';
        }

        function date_formatter(value, row, index) {
            return row.start_date  + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
        }

        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_online_course" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }

        function isopen_formatter(value, row, index) {
            var status = row.isopen == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function action_plan_formatter(value, row, index) {
            return (row.in_plan) ? '{{ trans("backend.yes") }}' : '{{ trans("backend.no") }}';
        }

        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        function register_formatter(value, row, index) {
            let register = '';
            @can('online-course-register')
                register = '<a href="'+ row.register_url +'"><i class="fa fa-user"></i></a>';
            @endcan
            return register;
        }

        function lock_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<a style="cursor: pointer;" onclick=lockCourse('+row.id+',1)>' + '<i class="fa fa-lock-open"></i>' + '</a>';
                case 1:
                    return '<a style="cursor: pointer;" onclick=lockCourse('+row.id+',0)>' + '<i class="fa fa-lock"></i>' + '</a>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.getdata') }}',
            remove_url: '{{ route('module.online.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.online.ajax_isopen_publish') }}";

        $('#training_program').on('change', function () {
            var training_program_id = $('#training_program option:selected').val();
            $("#level_subject").empty();
            $("#level_subject").data('training-program', training_program_id);
            $('#level_subject').trigger('change');
        });

        $('#level_subject').on('change', function () {
            var training_program_id = $('#training_program option:selected').val();
            var level_subject_id = $('#level_subject option:selected').val();
            $("#subject").empty();
            $("#subject").data('training-program', training_program_id);
            $("#subject").data('level-subject', level_subject_id);
            $('#subject').trigger('change');
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacore.min_one_course') }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: ajax_isopen_publish,
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lacore.system_error') }}', 'error');
                return false;
            });
        };

        function lockCourse(id,status) {
            if (id) {
                var ids = id;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacore.min_one_course') }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: base_url +'/admin-cp/online/lock',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lacore.system_error') }}', 'error');
                return false;
            });
        };
    </script>
    <script src="{{ asset('styles/module/online/js/online.js?v='.time()) }}"></script>
{{-- @endsection --}}
