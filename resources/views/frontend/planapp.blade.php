@extends('layouts.app')

@section('page_title', trans('backend.plan_app'))

@section('header')

@endsection

@section('content')

    <div class="container-fluid" id="trainingroadmap">
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="ibox-content forum-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i>
                        <span class="font-weight-bold">@lang('backend.plan_app')</span>
                    </h2>
                </div>
            </div>
        </div>
        <p></p>
        <div class="planapp">
            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th data-sortable="true"  data-field="code">@lang('app.course_code')
                    <th data-sortable="true" data-formatter="course_url" data-field="name">@lang('app.course_name')
                    <th data-sortable="true" data-align="center" data-formatter="timetrainning" data-field="date">@lang('app.time_held')
                    <th data-sortable="true" data-align="center" data-field="course_type" data-formatter="course_type">@lang('app.training_form')
                    <th data-sortable="true" data-field="result" data-formatter="result_formatter">@lang('app.result')
                    <th data-sortable="true" data-align="center" data-formatter="planapp_formatter" >@lang('app.action_plan')
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        function timetrainning(value, row, index) {
            return row.start_date + ' - '+row.end_date;
        }

        function course_url(value,row,index) {
            return '<a href="'+row.course_url+'">'+row.name+'</a>';
        }
        function result_formatter(v,r,i) {
            if(r.result==1)
                return 'Đạt';
            else if(r.result==2)
                return 'Không dạt';
            return '';
        }
        function planapp_formatter(value, row, index) {
            if (row.status == 2)
                return '<a href="/plan-app/form-evaluation/' + row.id + '/' + row.course_type + '">{{ trans("backend.assessments") }}</a>';

            else if (row.action_plan == 1 && (row.status != 4 && row.status != 5))
                return '<a href="/plan-app/form/' + row.id + '/' + row.course_type + '">' + row.status_text + '</a>';

            else if (row.action_plan == 1 && (row.status == 4 || row.status == 5))
                return '<a href="/plan-app/form-evaluation/' + row.id + '/' + row.course_type + '">' + row.status_text + '</a>';

            return '-';
        }

        function course_type(value, row, index) {
            return row.course_type==1?'Offline':'Tập trung';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.plan_app.getdata') }}',
        });

    </script>
@stop
