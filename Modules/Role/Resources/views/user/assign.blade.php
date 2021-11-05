@extends('layouts.backend')

@section('page_title', __('Quản lý vai trò'))
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.permission') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.roles') }}">{{ trans('backend.role_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.role') }} {{ $role->name }}</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main" id="role">
        <div class="row">
            <div class="col-md-12 ">
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
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('backend.enter_code_name') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ route('backend.roles.user.unassign_role', ['role' => $role->id]) }}"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
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
                    <th data-field="code">{{ trans('backend.code') }}</th>
                    <th data-field="full_name">{{ trans('backend.fullname') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title">{{ trans('backend.title') }}</th>
                    <th data-field="unit">{{ trans('backend.unit') }}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.role.user.getdata.assign.role', ['role' => $role->id]) }}',
                remove_url: '{{ route('backend.roles.user.delete', ['role' => $role->id]) }}',
                field_id: 'user_id'
            });
        </script>
@endsection
