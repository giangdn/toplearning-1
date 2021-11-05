@extends('layouts.backend')

@section('page_title', 'Nhóm phần trăm')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a> <i class="uil uil-angle-right"></i> Nhóm phần trăm
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("backend.enter_group_name")}} %'>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-capabilities-group-percent-create')
                        <a href="{{ route('module.capabilities.group_percent.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('category-capabilities-group-percent-delete')
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
                    <th data-field="percent" data-align="center">Phần trăm</th>
                    <th data-field="percent_group" data-formatter="percent_group_formatter" data-align="center"> Thuộc nhóm</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function percent_group_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.percent_group +'</a>';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.capabilities.group_percent.getdata') }}',
            remove_url: '{{ route('module.capabilities.group_percent.remove') }}'
            locale: '{{ \App::getLocale() }}',
        });
    </script>
@endsection
