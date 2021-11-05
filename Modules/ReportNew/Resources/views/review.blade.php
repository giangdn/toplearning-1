@extends('layouts.backend')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    {{--<script src="{{asset('styles/js/BootstrapTable.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('styles/module/report/js/report.js')}}" type="text/javascript"></script>

    <style>
        .table > thead > tr > .th-second{
            top: 40px;
        }

        table video {
            width: 50%;
            height: auto;
        }

        table img {
            width: 50% !important;
            height: auto !important;
        }

        .table-bordered > thead > tr > th{
            border: 1px solid #b9b5b5 !important;
        }
    </style>
@endsection

@section('page_title', 'Xem báo cáo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{route('module.report_new')}}">{{trans('backend.new_report')}}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{trans('backend.view_report')}}</span>
        </h2>
    </div>
    <div role="main" id="report" class="pt-5">
        <div class="text-center mb-5 text-uppercase"><h3>{{$name}}</h3></div>
        @include('reportnew::'. strtolower($report) .'.review')
    </div>
@stop
