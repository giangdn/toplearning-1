@extends('layouts.backend')

@section('page_title', trans('backend.roadmap'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.backend.user') }}">{{ trans('backend.user_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.backend.user.edit',['id'=>$user_id]) }}">{{ $full_name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Lộ trình đào tạo</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        @include('user::backend.layout.menu')
        <div class="table-responsive">
            <table class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th data-field="index" data-formatter="index_formatter">#</th>
                    <th data-field="subject_code">{{ trans('backend.subject_code') }}</th>
                    <th data-field="subject_name">{{ trans('backend.subject_name') }}</th>
                    <th data-field="course_code">{{ trans('backend.course_code') }}</th>
                    <th data-field="course_name">{{ trans('backend.course_name') }}</th>
                    <th data-field="start_date" data-align="center">{{ trans('backend.start_date') }}</th>
                    <th data-field="end_date" data-align="center">{{ trans('backend.end_date') }}</th>
                    <th data-field="course_type" data-align="center">{{ trans('app.training_form') }}</th>
                    <th data-field="score" data-align="center">{{ trans('backend.score') }}</th>
                    <th data-field="result" data-align="center">{{ trans('backend.result') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.training_by_title.getdata',['user_id'=>$user_id]) }}',
        });
    </script>

@endsection
