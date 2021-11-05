@extends('layouts.backend')

@section('page_title', 'Thông báo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.notify') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{ trans('backend.enter_titles') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @can('notify-status')
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
                        @can('notify-create')
                        <a href="{{ route('module.notify_send.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('notify-delete')
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
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{trans('backend.titles')}}</th>
                    <th data-field="created_at2" data-align="center">{{ trans('backend.create_time') }}</th>
                    <th data-field="created_by2" data-align="center">{{ trans('backend.created_by') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.subject+'</a>';
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.notify_send.getdata') }}',
            remove_url: '{{ route('module.notify_send.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('backend.logo.ajax_isopen_publish') }}",
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
<<<<<<< HEAD
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
=======
                show_message('Lỗi hệ thống', 'error');
>>>>>>> 113e505dd4fa3c4d68ca5b7f871180cb02bbc3a2
                return false;
            });
        };
    </script>
@endsection
