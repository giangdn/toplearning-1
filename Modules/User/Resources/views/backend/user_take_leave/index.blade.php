{{-- @extends('layouts.backend')

@section('page_title', 'Nhân viên nghỉ phép')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> Nhân viên nghỉ phép</span>
        </h2>
    </div>
@endsection --}}

{{-- @section('content') --}}
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
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
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
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ route('module.backend.user_take_leave.export') }}"><i class="fa fa-download"></i> Export</a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_nhan_vien_nghi_phep.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import_user_take_leave" type="button" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                    <div class="btn-group">
                        @can('user-take-leave-create')
                            <a href="{{ route('module.backend.user_take_leave.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('user-take-leave-delete')
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
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="full_name" data-formatter="fullname_formatter">{{ trans('backend.employee_name') }}</th>
                <th data-field="email">Email</th>
                <th data-field="unit_name" data-formatter="unit_formatter">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager" data-with="5%">{{ trans('backend.unit_manager') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="position_name">Chức vụ</th>
                <th data-field="absent">Lý do</th>
                <th data-field="date_take_leave">Ngày</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user_take_leave.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Import danh sách nhân viên nghỉ phép
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="unit_id" value=" ">
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
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.full_name + '</a>';
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user_take_leave.getdata') }}',
            remove_url: '{{ route('module.backend.user_take_leave.remove') }}',
            // field_id: 'user_id'
        });

        $('#import_user_take_leave').on('click', function() {
            $('#modal-import').modal();
        });
    </script>
{{-- @endsection --}}
