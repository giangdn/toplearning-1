@extends('layouts.backend')

@section('page_title', 'Quản lý logo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Logo</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('config-logo')
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
                        @can('config-logo')
                            <a href="{{ route('backend.logo.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('config-logo')
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
                    <th data-field="image" data-formatter="image_formatter" data-width="20%">{{trans('backend.picture')}}</th>
                    <th data-field="object" data-formatter="object_formatter">{{ trans('backend.object') }}</th>
                    <th data-field="status" data-formatter="status_formatter" data-width="5%" data-align="center">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function image_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'"><img src="'+ row.image_url +'" class="w-100"> </a>';
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function object_formatter(value, row, index) {
            var html = '';
            $.each(row.objects, function(i, item) {
                html += '<p class="m-1">'+ item +'</p>';
            });

            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.logo.getdata') }}',
            remove_url: '{{ route('backend.logo.remove') }}'
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
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };
    </script>
@endsection
