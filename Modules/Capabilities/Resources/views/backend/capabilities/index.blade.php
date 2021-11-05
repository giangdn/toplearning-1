@extends('layouts.backend')

@section('page_title', 'Khung năng lực')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a> <i class="uil uil-angle-right"></i> Khung năng lực
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("backend.enter_capabilities_sympol_name")}}'>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-capabilities-create')
                        <a href="{{ route('module.capabilities.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('category-capabilities-delete')
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
                    <th data-field="code" data-width="10%">{{ trans('backend.frame_capacity_code') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.capability_name') }}</th>
                    <th data-field="category_name" data-width="15%">{{ trans('backend.capacity_category') }}</th>
                    <th data-field="group_name" data-width="15%">{{ trans('backend.capabilities_group') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.capabilities.getdata') }}',
            remove_url: '{{ route('module.capabilities.remove') }}'
            locale: '{{ App::getLocale() }}',
        });
    </script>
@endsection
