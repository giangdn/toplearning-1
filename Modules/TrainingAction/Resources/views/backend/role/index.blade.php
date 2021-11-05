@extends('layouts.backend')

@section('page_title', trans('backend.training_action_category'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">@lang('backend.categories')</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.role') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;@lang('backend.search')</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('module.training_action.role.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> @lang('backend.add_new')</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> @lang('backend.delete')</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-role="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code" data-width="10%">@lang('backend.code')</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">@lang('backend.name')</th>
                    <th data-sortable="true" data-field="status" data-formatter="status_formatter" data-width="10%" data-align="center">@lang('backend.status')</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function status_formatter(value, row, index) {
            if (parseInt(value) === 0) {
                return '<span class="text-danger">@lang('backend.disable')</span>';
            }
            return '<span class="text-success">@lang('backend.enable')</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_action.role.getdata') }}',
            remove_url: '{{ route('module.training_action.role.remove') }}',
        });
    </script>
@endsection
