@extends('layouts.backend')

@section('page_title', 'Khung năng lực')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.capabilities.review') }}">Khung năng lực</a> <i class="uil uil-angle-right"></i> Xây dựng kế hoạch đào tạo tháng
        </h2>
    </div>
@endsection

@section('content')

    <div role="main" id="capabilities-result">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control">
                    <button class="btn btn-primary"><i class="fa fa-search"></i> {{trans('backend.search')}}</button>
                </form>
            </div>

            <div class="col-md-4 text-right">
                @can('capabilities-result-send')
                <button class="btn btn-success" id="send" disabled><i class="fa fa-send"></i> Gửi lên đào tạo</button>
                @endcan
                <div class="btn-group">
                    @can('capabilities-result-create')
                    <a href="{{ route('module.capabilities.review.result.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                    @endcan
                    @can('capabilities-result-delete')
                    <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter" data-width="20%">Tên kế hoạch</th>
                    <th data-field="fullname" data-formatter="fullname_formatter" data-width="10%">{{ trans('backend.user_create') }}</th>
                    <th data-field="created_date">{{ trans('backend.create_time') }}</th>
                    <th data-field="updated_date">{{trans('backend.last_updated')}}</th>
                    <th data-field="status" data-formatter="status_formatter" data-align="center">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            if (row.status == 1) {
                return '<a href="'+ row.edit_url +'">'+ value +'</a>';
            }

            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function fullname_formatter(value, row, index) {
            return row.lastname +' '+ row.firstname;
        }

        function status_formatter(value, row, index) {
            if (row.status == 1) {
                return '<span class="text-success">Đã gửi</span>';
            }
            return '<span class="text-danger">Chưa gửi</span>';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.capabilities.review.result.getdata') }}',
            remove_url: '{{ route('module.capabilities.review.result.remove') }}',
            locale: '{{ \App::getLocale() }}',
        });

        $("#capabilities-result").on('change', 'input[name=btSelectItem]:checked', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                $("#send").prop('disabled', true);
            }
            else {
                $("#send").prop('disabled', false);
            }
        });

        $("#send").on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn kế hoạch muốn gửi', 'warning');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.capabilities.review.result.send') }}',
                dataType: 'json',
                data: {
                    'ids': ids
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                if (data.status === "success") {
                    table.refresh();
                }

                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });

    </script>

@endsection
