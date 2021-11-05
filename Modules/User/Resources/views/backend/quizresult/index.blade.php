@extends('layouts.backend')

@section('page_title', trans('backend.quiz_result'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.backend.user') }}">{{ trans('backend.user_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.backend.user.edit',['id'=>$user_id]) }}">{{ $full_name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.quiz_result') }}</span>
        </h2>
    </div>
    <div role="main">
        @include('user::backend.layout.menu')
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th data-width="40px" data-formatter="index_formatter">#</th>
                    <th data-field="code" data-width="5%">{{ trans('app.quiz_code') }}</th>
                    <th data-width="20%" data-field="name">{{ trans('app.quiz') }}</th>
                    <th  data-field="start_date" data-width="180px" data-align="center">{{ trans('backend.start_date') }}</th>
                    <th  data-field="end_date" data-width="180px" data-align="center">{{ trans('backend.end_date') }}</th>
                    <th  data-field="limit_time" data-width="150px" data-align="center">{{ trans('backend.exam_time_minutes') }}</th>
                    <th  data-align="center" data-width="80px"  data-field="grade">{{ trans('app.score') }}</th>
                    <th  data-align="center" data-width="160px" data-field="result" data-formatter="result_formatter">{{ trans('backend.result') }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function result_formatter(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : '{{ trans("backend.incomplete") }}';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.quizresult.getdata',['user_id'=>$user_id]) }}',
        });

    </script>

@endsection
