@extends('layouts.backend')

@section('page_title', 'Báo cáo mới')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main" id="report">
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('module.report_new.history_export')}}" class="btn btn-info report-title"> Lịch sử export </a>
            </div>
            <div class="col-md-12">
            @foreach($reports as $key => $report)
                @can('report-'.($loop->iteration < 10 ? '0'.$loop->iteration : $loop->iteration))
                <div class="card card-report" style="margin:5px;padding:5px">
                    <div class="">
                        <a href="{{route('module.report_new.review',['id'=>$key])}}" class="report-title">{{ ($loop->iteration < 10 ? '0'.$loop->iteration : $loop->iteration) }}. {{ $report }}</a>
                    </div>
                </div>
                @endcan
            @endforeach
            </div>
        </div>
    </div>
@stop
