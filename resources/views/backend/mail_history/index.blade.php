@extends('layouts.backend')

@section('page_title', 'Quản lý lịch sử gửi email')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.manage_email_history') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{ trans('backend.enter_code_name_mail') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-sortable="true" data-width="5%">{{ trans('backend.code') }}</th>
                    <th data-field="name" data-sortable="true" data-width="20%">{{ trans('backend.email_name') }}</th>
                    <th data-field="content">{{ trans('backend.content') }}</th>
                    <th data-field="emails" data-width="15%">{{ trans('backend.list_mail_send') }}</th>
                    <th data-field="send_time" data-width="10%">{{ trans('backend.time_send_mail') }}</th>
                    <th data-field="status" data-width="5%" data-formatter="status_formatter" data-align="center">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function status_formatter(value, row, index) {
            switch (value) {
                case '0': return '<span class="text-muted">Chưa gửi</span>';
                case '1': return '<span class="text-success">Đã gửi</span>';
                case '2': return '<span class="text-success">Chưa cấu hình mail server</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.mailhistory.getdata') }}',
            sort_order: 'desc'
        });
    </script>
@endsection
