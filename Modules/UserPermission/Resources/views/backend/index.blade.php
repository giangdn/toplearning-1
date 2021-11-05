@extends('layouts.backend')

@section('page_title', trans('backend.user_management'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> {{ trans('backend.user') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
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
        <p></p>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('permission-user-import')
                            <button class="btn btn-info" id="model-list-import"><i class="fa fa-upload"></i> Import</button>
                    @endcan
                    @can('permission-user-export')
                        <a class="btn btn-info" href="{{ route('module.backend.user.export_user') }}"><i class="fa fa-download"></i> Export</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="10%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-width="25%" data-formatter="fullname_formatter">{{ trans('backend.employee_name') }}</th>
                <th data-field="username" data-width="15%">{{ trans('backend.username') }}</th>
                <th data-field="email">{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-sortable="true" data-align="center"  data-width="10%">{{ trans('backend.role') }}</th>
                <th data-sortable="true" data-align="center" data-formatter="permission_formatter"  data-width="10%">{{ trans('backend.permisstion') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-import">IMPORT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('backend.user') }}</div>
                        <div class="col-md-5">
                            <button class="btn btn-info" id="import-user" type="submit" name="task" value="import"><i class="fa fa-upload"></i> Import</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('backend.working_process') }}</div>
                        <div class="col-md-5">
                            <button class="btn btn-info" id="import-working-process" type="submit" name="task" value="import"><i class="fa fa-upload"></i> Import</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('backend.training_program_learned') }}</div>
                        <div class="col-md-5">
                            <button class="btn btn-info" id="import-training-program-learned" type="submit" name="task" value="import"><i class="fa fa-upload"></i> Import</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-template-import" tabindex="-1" role="dialog" aria-labelledby="modal-template-import" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-template-import">{{ trans('backend.import_template') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('backend.user') }}</div>
                        <div class="col-md-5">
                            <a class="btn btn-info" href="{{ download_template('mau_import_nguoi_dung.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('backend.working_process') }}</div>
                        <div class="col-md-5">
                            <a class="btn btn-info" href="{{ download_template('mau_import_qua_trinh_cong_tac.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('backend.training_program_learned') }}</div>
                        <div class="col-md-5">
                            <a class="btn btn-info" href="{{ download_template('mau_import_chuong_trinh_dao_tao_da_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import-user" tabindex="-1" role="dialog" aria-labelledby="modal-import-user" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user.import_user') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-user">IMPORT {{ (trans('backend.user')) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-import-working-process" tabindex="-1" role="dialog" aria-labelledby="modal-import-working-process" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user.import_working_process') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-working-process">IMPORT {{ (trans('backend.working_process')) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-import-training-program-learned" tabindex="-1" role="dialog" aria-labelledby="modal-import-training-program-learned" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user.import_training_program_learned') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-training-program-learned">IMPORT {{ (trans('backend.training_program_learned')) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.lastname + ' ' + row.firstname + '</a>';
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }
        function permission_formatter(value, row, index) {
            if(row.is_permission)
                return `<a href="${row.permission_url}"><i class="fa fa-users"></i></a>`;
            else
                return `<i class="fa fa-users text-muted"></i>`;
        }
        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span>{{ trans('backend.inactivity') }}</span>';
                case 1:
                    return '<span>{{ trans('backend.doing') }}</span>';
                case 2:
                    return '<span>{{ trans('backend.probationary') }}</span>';
                case 3:
                    return '<span>{{ trans('backend.pause') }}</span>';
            }
        }

        $('#model-list-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#model-list-template-import').on('click', function () {
            $('#modal-template-import').modal();
        });

        $('#import-user').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-user').modal();
        });

        $('#import-working-process').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-working-process').modal();
        });

        $('#import-training-program-learned').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-training-program-learned').modal();
        });

        $('.close').on('click', function () {
           window.location = '';
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.userpermission.getdata') }}',
            remove_url: '{{ route('module.backend.user.remove') }}',
            field_id: 'user_id'
        });
    </script>
@endsection
