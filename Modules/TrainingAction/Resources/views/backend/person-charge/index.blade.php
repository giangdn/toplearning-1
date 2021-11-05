@extends('layouts.backend')

@section('page_title', trans('backend.person_charge'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">@lang('backend.categories')</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.person_charge') }}</span>
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
                    <a href="{{ route('module.training_action.person_charge.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> @lang('backend.add_new')</a>
                    <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> @lang('backend.delete')</button>
                </div>
            </div>
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover bootstrap-table">
        <thead>
            <tr>
                <th data-field="state" data-checkbox="true" data-width="5%"></th>
                <th data-sortable="true" data-field="code" data-width="10%">@lang('backend.code')</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="30%">@lang('backend.name')</th>
                <th data-sortable="true" data-field="max_support" data-width="10%" data-align="center">@lang('backend.max_support')</th>
                <th data-sortable="true" data-field="type" data-formatter="type_formatter" data-width="20%" data-align="center">@lang('backend.type')</th>
                <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-align="center" data-width="10%">@lang('backend.status')</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    function fullname_formatter(value, row, index) {
        return '<a href="'+ row.edit_url +'">'+ row.fullname +'</a>';
    }

    function type_formatter(value, row, index) {
        return row.type == 1 ? 'Người chính' : 'Người phụ';
    }

    function status_formatter(value, row, index) {
        return row.status == 1 ? 'Bật' : 'Tắt';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.training_action.person_charge.getdata') }}',
        remove_url: '{{ route('module.training_action.person_charge.remove') }}',
    });
</script>
@endsection
