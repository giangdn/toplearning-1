@extends('layouts.backend')

@section('page_title', trans('backend.virtual_classroom'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.virtual_classroom') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form id="form-search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('backend.code_name_course') }}">
                        </div>

                        <div class="col-sm-2 my-1">
                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                        </div>

                        <div class="col-sm-2 my-1">
                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn btn-success approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                        </button>
                        <button class="btn btn-danger approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{ trans('backend.deny') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('module.virtualclassroom.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-sortable="true" data-formatter="name_formatter">{{ trans('backend.course_name') }}</th>
                <th data-sortable="true" data-formatter="date_formatter" data-align="center">{{ trans('backend.time') }}</th>
                <th data-align="center" data-formatter="created_by_formatter">{{ trans('backend.code_user_create') }}</th>
                <th data-field="created_at2" data-align="center">{{ trans('backend.created_at') }}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter">{{ trans('backend.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.name + ' (' + row.code + ') </a>';
        }

        function date_formatter(value, row, index) {
            return row.start_date + ' <i class="uil uil-arrow-right"></i> ' + row.end_date;
        }
        
        function created_by_formatter(value, row, index) {
            return row.user_name;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span class="text-danger">{{ trans('backend.deny') }}</span>';
                case 1:
                    return '<span class="text-success">{{ trans('backend.approved') }}</span>';
                case 2:
                    return '<span class="text-warning">{{ trans('backend.not_approved') }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.virtualclassroom.getdata') }}',
            remove_url: '{{ route('module.virtualclassroom.remove') }}'
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 lớp học', 'error');
                return false;
            }

            $.ajax({
                url: base_url +'/admin-cp/virtualclassroom/approve',
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
    </script>
@endsection
