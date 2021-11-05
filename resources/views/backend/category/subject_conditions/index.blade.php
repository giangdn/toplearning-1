@extends('layouts.backend')

@section('page_title', trans('backend.subject_conditions'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a> <i class="uil uil-angle-right"></i> {{ trans('backend.subject_conditions') }}
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ data_locale('Nhập tên', 'Enter name') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('backend.category.subject_conditions.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code">{{ trans('backend.code_subject_conditions') }}</th>
                    <th data-sortable="true" data-formatter="name_formatter">{{ trans('backend.subject_conditions') }}</th>
                    <th data-sortable="true" data-field="name_en">{{ trans('backend.subject_conditions') }} (EN)</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }
        function status_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">{{ trans('backend.enable') }}</span>' : '<span style="color: red;">{{ trans('backend.disable') }}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.subject_conditions.getdata') }}',
        });
    </script>
@endsection
