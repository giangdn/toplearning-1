{{-- @extends('layouts.backend')

@section('page_title', trans('backend.user_management'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content') --}}
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
                @php
                $notification->markAsRead();
                @endphp
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form_search_user" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit unit_search" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor

                    <div class="">
                        <select name="title" id="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="">
                        <select name="status" id="status" class="form-control select2" data-placeholder="-- {{ trans('backend.status') }} --">
                            <option value=""></option>
                            <option value="0">{{ trans('backend.inactivity') }}</option>
                            <option value="1">{{ trans('backend.doing') }}</option>
                            {{-- <option value="2">{{ trans('backend.probationary') }}</option> --}}
                            {{-- <option value="3">{{ trans('backend.pause') }}</option> --}}
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / username/ email/ tên nhân viên', 'Enter the staff name / code') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" id="btnsearch" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>

        <p></p>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('user-approve-change-info')
                        <a href="{{ route('module.backend.user.approve_info') }}" class="btn btn-primary"><i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}</a>
                    @endcan
                    <div class="btn-group">
                        @can('user-import')
                            <button class="btn btn-info" id="model-list-template-import"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</button>
                            <button class="btn btn-info" id="model-list-import"><i class="fa fa-upload"></i> Import</button>
                        @endcan
                        @can('user-export')
                            <form action="{{ route('module.backend.user.export_user') }}" method="get">
                                <input type="hidden" name="export_search" value="">
                                <input type="hidden" name="export_unit" value="">
                                <input type="hidden" name="export_area" value="">
                                <input type="hidden" name="export_status" value="">
                                <input type="hidden" name="export_title" value="">
                                <button class="btn btn-info" id="btnExport" type="submit"><i class="fa fa-download"></i> Export</button>
                            </form>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('user-create')
                            <a href="{{ route('module.backend.user.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('user-delete')
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
                <th data-field="avatar" data-formatter="avatar_formatter" data-width="5%">avatar</th>
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent_unit_name" data-with="5%">{{ trans('backend.unit_manager') }}</th>
                {{--<th data-field="area_name" data-formatter="area_formatter">{{ trans('backend.work_location') }}</th>--}}
                <th data-sortable="true" data-field="status_id" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
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
        function avatar_formatter(value, row, index) {

            var img = `<img src="${row.avatar}" />`;
            return `<a  class="opts_account" href="${row.edit_url}">${img}</a>`;
        }
        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }

        function status_formatter(value, row, index) {
            value = parseInt(row.status_id);
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
            url: '{{ route('module.backend.user.getdata') }}',
            remove_url: '{{ route('module.backend.user.remove') }}',
            field_id: 'user_id'
        });

        $('#btnsearch').on('click', function() {
            var latest_value = $(".unit_search option:selected:last").val();
            if(latest_value) {
                $('input[name=export_unit]').val(latest_value);
            }
            var area = $('#area').val();
            $('input[name=export_area]').val(area);
            var title = $('#title').val();
            $('input[name=export_title]').val(title);
            var status = $('#status').val();
            $('input[name=export_status]').val(status);
            var search = $('input[name=search]').val();
            $('input[name=export_search]').val(search);
        })
    </script>
{{-- @endsection --}}
