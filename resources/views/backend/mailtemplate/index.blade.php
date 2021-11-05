@extends('layouts.backend')

@section('page_title', 'Quản lý mẫu email')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.mailtemplate_manager') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{trans('backend.enter_name_title_code')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter" data-width="20%">{{ trans('backend.name') }}</th>
                    <th data-field="title" data-sortable="true" data-width="25%">{{trans('backend.titles')}}</th>
                    <th data-field="content">{{ trans('backend.content') }}</th>
                    <th data-field="note" data-width="15%">{{ trans('backend.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.mailtemplate.getdata') }}',
            sort_order: 'asc'
        });
    </script>
@endsection
