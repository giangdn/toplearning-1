@extends('layouts.backend')

@section('page_title', 'Quản lý banner ngoài')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Chỉnh thời gian</span>
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
                    <div class="btn-group">
                        <a href="{{ route('backend.setting_time.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
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
                    <th data-field="image" data-formatter="time_formatter" data-width="20%">Thời gian</th>
                    <th data-field="name" data-formatter="content_formatter">Nội dung</th>
                    <th data-field="name" data-formatter="object_formatter">Đơn vị</th>
                    <th data-field="name" data-align="center" data-formatter="edit_formatter">Chỉnh sửa</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function time_formatter(value, row, index) {
            var html = '<p><span>'+ row.start_time_morning +'</span><span class="fa fa-arrow-right" style="padding: 0 10px;"></span><span>'+ row.end_time_morning +'</span></p>';
            html += '<p><span>'+ row.start_time_noon +'</span><span class="fa fa-arrow-right" style="padding: 0 10px;"></span><span>'+ row.end_time_noon +'</span></p>';
            html += '<p><span>'+ row.start_time_afternoon +'</span><span class="fa fa-arrow-right" style="padding: 0 10px;"></span><span>'+ row.end_time_afternoon +'</span></p>';
            return html;
        }

        function content_formatter(value, row, index) {
            var html = '<p><span>'+ row.value_morning +'</span></p>';
            html += '<p><span>'+ row.value_noon +'</span></p>';
            html += '<p><span>'+ row.value_afternoon +'</span></p>';
            return html;
        }

        function object_formatter(value, row, index) {
            console.log(row);
            if (row.object != 'All') {
                let rhtml = '';
                $.each(row.object, function(i, item) {
                    rhtml += '<p><span>'+ item +'</span></p>';
                });
                return rhtml;
            } else {
                return '<p><span>Tất cả đơn vị</span></p>'
            }
        }

        function edit_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'"><i class="fas fa-edit"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.setting_time.getdata') }}',
            remove_url: '{{ route('backend.setting_time.remove') }}'
        });
    </script>
@endsection
