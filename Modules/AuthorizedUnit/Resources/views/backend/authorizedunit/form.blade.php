@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Đơn vị
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.authorized_unit') }}">Trưởng đơn vị ủy quyền</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> {{ $page_title }} </span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 form-inline">
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
                        <a href="javascript:void(0)" class="btn btn-primary" id="save"><i class="fa fa-plus-circle"></i> {{ trans('backend.save') }}</a>
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
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return  row.lastname + ' ' + row.firstname;
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
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

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.authorized_unit.getdata_nomanager') }}',
            field_id: 'user_id'
        });

        $('#save').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân sự', 'error');
                return false;
            }

            $.ajax({
                url: "{{ route('module.authorized_unit.save') }}",
                type: 'post',
                data: {
                    ids: ids,
                }
            }).done(function(data) {
                show_message(data.message);

                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
