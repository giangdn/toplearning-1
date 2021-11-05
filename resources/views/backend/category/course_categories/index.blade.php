@extends('layouts.backend')

@section('page_title', 'Khóa học')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.course') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_block')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('backend.category.course_categories.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
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
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.course_name') }}</th>
                    <th data-field="parent_name" data-width="20%">Cấp cha</th>
                    <th data-sortable="true" data-field="type" data-formatter="type_formatter" data-width="20%">Loại khóa học</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }
        function status_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">{{trans("backend.enable")}}</span>' : '<span style="color: red;">{{trans("backend.disable")}}</span>';
        }
        function type_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">Offline</span>' : '<span style="color: #060;">{{trans("backend.offline")}}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.course_categories.getdata') }}',
            remove_url: '{{ route('backend.category.course_categories.remove') }}'
        });
    </script>
@endsection
