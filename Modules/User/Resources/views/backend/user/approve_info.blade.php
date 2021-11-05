@extends('layouts.backend')

@section('page_title', trans('backend.user_management'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.user') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline" id="form-search">
                    <div class="w-50">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
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
                        <button class="btn btn-success approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                        </button>
                        <button class="btn btn-danger approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans("backend.deny")}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="user_code" data-width="10%">{{ trans('backend.employee_code') }}</th>
                <th data-field="full_name">{{ trans('backend.employee_name') }}</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="value_old">Giá trị cũ</th>
                <th data-field="value_new">Giá trị mới</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function value_old_formatter(value, row, index) {
            if(row.key == 'avatar'){
                return '<img src="" alt="">';
            }
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span>{{trans("backend.deny")}}</span>';
                case 1:
                    return '<span>{{trans("backend.approve")}}</span>';
                case 2:
                    return '<span>{{ trans("backend.pending") }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.getdata_history_change_info') }}',
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.backend.user.approve_info_change') }}',
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
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
