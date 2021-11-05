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
            <div class="col-md-12 form-inline">
                <form class="form-inline w-100 form-search mb-3" id="form-search">
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ route('backend.roles.title.unassign_role', ['role' => $role->id]) }}">
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
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
                    <th data-field="name">{{ trans('backend.title') }}</th>
                    <th data-field="position">Chức vụ</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.role.title.getdata.assign.role', ['role' => $role->id]) }}',
                remove_url: '{{ route('backend.roles.title.delete', ['role' => $role->id]) }}',
            });
        </script>
@endsection
