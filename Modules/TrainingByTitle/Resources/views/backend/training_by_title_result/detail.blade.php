@extends('layouts.backend')

@section('page_title', trans('backend.roadmap'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_by_title.result') }}"> Kết quả lộ trình đào tạo</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ $full_name }}</span>
        </h2>
    </div>
    <div role="main">
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
            url: '{{ route('module.training_by_title.result.getdata_detail',['user_id'=>$user_id]) }}',
        });
    </script>

@endsection
