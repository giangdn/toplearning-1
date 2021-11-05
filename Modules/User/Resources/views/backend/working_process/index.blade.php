@extends('layouts.backend')

@section('page_title', trans('backend.working_process'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.backend.user') }}">{{ trans('backend.user_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.backend.user.edit',['id' => $user_id]) }}">{{ $full_name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.working_process') }}</span>
        </h2>
    </div>
    @if($user_id)
        @include('user::backend.layout.menu')
    @endif
    <div role="main">
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @if(!\App\Permission::isUnitManager())
                    <div class="btn-group">
                        <a href="{{ route('module.backend.working_process.create', ['user_id' => $user_id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-width="20%" data-formatter="fullname_formatter">{{ trans('backend.employee_name') }}</th>
                <th data-field="email">{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-formatter="time_formatter" data-align="center">{{ trans('backend.time') }}</th>
                <th data-field="note">{{ trans('backend.note') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.fullname + '</a>';
        }

        function time_formatter(value, row, index) {
            return row.start_date + '<i class="uil uil-arrow-right"></i>' + row.end_date;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.working_process.getdata', ['user_id' => $user_id]) }}',
            remove_url: '{{ route('module.backend.working_process.remove', ['user_id' => $user_id]) }}',
        });

    </script>
@endsection
