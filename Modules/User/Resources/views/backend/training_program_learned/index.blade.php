@extends('layouts.backend')

@section('page_title', trans('backend.training_program_learned'))

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
            <span class=""> {{ trans('backend.training_program_learned') }}</span>
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
                        <a href="{{ route('module.backend.training_program_learned.create', ['user_id' => $user_id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
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
                <th data-field="training_program">{{ trans('backend.training_program') }}</th>
                <th data-field="time" data-align="center">{{ trans('backend.time') }}</th>
                <th data-field="note">{{ trans('backend.note') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.fullname + '</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.training_program_learned.getdata', ['user_id' => $user_id]) }}',
            remove_url: '{{ route('module.backend.training_program_learned.remove', ['user_id' => $user_id]) }}',
        });

    </script>
@endsection
