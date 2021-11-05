@extends('layouts.app')

@section('page_title', 'Khóa học của tôi')

@section('header')
{{--<script language="javascript" src="{{ asset('styles/js/my.js') }}"></script>--}}
<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/my.css') }}">

@endsection

@section('content')
    <div class="container-fluid" id="trainingroadmap" style="background: white; ">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li>
                <a href="{{url('/')}}"><i class="glyphicon glyphicon-home"></i>Trang chủ</a>
            </li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»
            </li>
            <li>
                <span>Khóa học của tôi</span>
            </li>
        </ol>
        <div class="tab-content" >
            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true"  data-field="code">{{trans('backend.course_code')}}
                    <th data-sortable="true" data-formatter="course_name" data-field="name">{{trans('backend.course_name')}}
                    <th data-sortable="false" data-align="center" data-formatter="timetrainning" data-field="start_date">Thời gian tổ chức
                    <th data-sortable="false" data-field="course_type" data-align="center" data-formatter="course_type">{{trans('backend.training_program_form')}}
                    <th data-sortable="false" data-field="result">{{trans('backend.choose_training_program_form')}}
                    <th data-sortable="false" data-align="center" data-field="score">{{ trans('backend.score') }}
                    <th data-sortable="false" data-field="result">{{ trans('backend.result') }}
                    <th data-sortable="false" data-align="center" data-formatter="planapp_formatter" >Đánh giá hiệu quả đào tạo </th>
                    <th data-field="time_evaluation">Hạn đánh giá</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        function timetrainning(value, row, index) {
            return row.start_date + ' - '+row.end_date;
        }
        function course_name(value, row, index) {
            return '<a href="'+row.course_url+'"> '+row.name+'</a>';
        }
        function course_type(value, row, index) {
            return  row.course_type==1?'Offline':'Tập trung';
        }
        function planapp_formatter(value, row, index) {
            // return (row.action_plan==1?'<a href="#">thực hiện</a>':'-') ;
            if (row.evaluation==1)
                return '<a href="{{route('frontend.plan_app.form.evaluation',['course_id'=>'','course_type'=>''])}}/'+row.id+'/'+row.course_type+'">{{ trans("backend.assessments") }}</a>';
            else if(row.evaluation==2)
                return 'Hết hạn';
            else if(row.action_plan==1 && (row.plan_app_status!=4 && row.plan_app_status!=5))
                return '<a href="{{route('frontend.plan_app.form',['course_id'=>'','course_type'=>''])}}/'+row.id+'/'+row.course_type+'">'+row.status_text+'</a>';
            else if(row.action_plan==1 && (row.plan_app_status==4 || row.plan_app_status==5))
                return '<a href="{{route('frontend.plan_app.form.evaluation',['course_id'=>'','course_type'=>''])}}/'+row.id+'/'+row.course_type+'">'+row.status_text+'</a>';
            return '-' ;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.my_course.getdata') }}',
        });
    </script>
@stop
