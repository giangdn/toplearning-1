@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.action_plan'))

@section('content')
    <div class="container" id="trainingroadmap">
        <div class="planapp">
            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                    <tr>
                        <th data-sortable="true" data-align="center" data-width="5%" data-formatter="index_formatter">@lang('app.stt')
                        <th data-formatter="info_formatter">@lang('app.info')
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('footer')
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function info_formatter(value, row, index) {
            var btn = '';
            if (row.evaluation==1)
                btn = '<a href="/plan-app/form-evaluation/'+row.id+'/'+row.course_type+'" class="btn btn-info text-white">{{ trans("backend.assessments") }}</a>';
            else if(row.evaluation==2)
                btn = 'Hết hạn';
            else if(row.action_plan==1 && (row.status!=4 && row.status!=5))
                btn = '<a href="/plan-app/form/'+row.id+'/'+row.course_type+'" class="btn btn-info text-white">'+row.status_text+'</a>';
            else if(row.action_plan==1 && (row.status==4 || row.status==5))
                btn = '<a href="/plan-app/form-evaluation/'+row.id+'/'+row.course_type+'" class="btn btn-info text-white">'+row.status_text+'</a>';

            return row.name + '<br>' + '(' + row.code + ')' + '<br>' + row.start_date + (row.end_date ? ' - '+ row.end_date : '') + '<br>' + (row.course_type==1?'offline':'Tập trung') + '<br>' + btn;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.plan_app.getdata') }}',
        });

    </script>
@endsection
