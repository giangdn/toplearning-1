@extends('layouts.backend')

@section('page_title', 'Phân quyền đơn vị')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.permission') }}">{{ trans('backend.permission') }}</a> / {{ trans('backend.unit_group') }}
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_name')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>

                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('backend.unit_permission.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
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
                    <th data-sortable="true" data-field="unit_code">{{ trans('backend.unit_code') }}</th>
                    <th data-field="unit_name" data-formatter="unit_name_formatter">{{ trans('backend.unit_name') }}</th>
                    <th data-field="name">{{ trans('backend.manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function unit_name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.unit_permission.getdata') }}',
            remove_url: '{{ route('backend.unit_permission.remove') }}'
        });
    </script>
@endsection
