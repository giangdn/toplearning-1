@extends('layouts.backend')

@section('page_title', 'Danh sách Đánh giá hiệu quả đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.code_name_course')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-width="15%" data-field="code" >{{ trans('backend.course_code') }}</th>
                <th data-width="25%" data-formatter="name_formatter" data-field="name" >{{ trans('backend.course_name') }}</th>
                <th data-width="10%" data-sortable="true" data-formatter="course_type" data-field="course_type" data-align="center">{{trans('backend.type_course')}}</th>
                <th data-width="15%" data-field="start_date" data-align="center">{{trans('backend.start_date')}}</th>
                <th data-width="15%" data-field="end_date" data-align="center">{{trans('backend.end_date')}}</th>
                <th data-width="10%" data-field="name" data-align="center" data-width="100px" data-formatter="export_formatter" >Export {{ trans('backend.assessments') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function course_type(value, row, index) {
            return row.course_type==1?'{{trans("backend.online")}}':'{{trans("backend.offline")}}';
        }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+row.name+'</a>';
        }
        function export_formatter(value, row, index) {
            return `<a href="${row.export_word}" class="export-word" data-course="${row.id}" data-type="${row.course_type}"><i class="fa fa-download"></i> Export</a>`;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.plan_app.course.getCourses') }}',
            {{--remove_url: '{{ route('module.plan_app.manager.remove') }}'--}}
        });

    </script>

@endsection
