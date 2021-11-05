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
            <a href="{{ route('module.backend.user') }}">{{ trans('backend.user_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.backend.user.edit',['id'=>$user_id]) }}">{{$full_name}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.roadmap') }}</span>
        </h2>
    </div>
    <div role="main">
        @include('user::backend.layout.menu')
        <div class="table-responsive">
            <table id="tableroadmap" class="tDefault table table-hover table-bordered bootstrap-table">
            <thead>
            <tr class="tbl-heading">
                <th data-field="index" data-formatter="index_formatter" width="40px;" rowspan="2" style="vertical-align: middle;">#</th>
                <th data-field="training_program_code" rowspan="2" style="vertical-align: middle;">{{ trans('backend.training_program_code') }}</th>
                <th data-field="training_program_name" rowspan="2" style="vertical-align: middle;">{{ trans('backend.training_program_name') }}</th>
                <th data-field="subject_code" rowspan="2" style="vertical-align: middle;">{{ trans('app.subject_code') }}</th>
                <th data-field="subject_name" rowspan="2" >{{ trans('app.subject') }}</th>
                <th rowspan="2" data-field="process_type" data-align="center" style="vertical-align: middle;">{{ trans('app.training_form') }}</th>
                <th style="vertical-align: middle;text-align: center;" colspan="2" data-align="center">{{ trans('app.time_held') }}</th>
                <th colspan="2" style="text-align: center; vertical-align: middle;">{{ trans('backend.date_effect') }}</th>
                <th colspan="2" data-align="center"  >{{ trans('backend.result') }}</th>
                <th rowspan="2" data-field="cert">{{ trans('backend.certificates') }}</th>
                <th rowspan="2" data-field="status">{{ trans('app.status') }}</th>
                <th rowspan="2" data-field="note">{{ trans('backend.note') }}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="start_date" data-align="center">{{ trans('app.from_date') }}</th>
                <th data-field="end_date" data-align="center">{{ trans('app.to_date') }}</th>

                <th data-field="start_effect" data-align="center">{{ trans('app.from_date') }}</th>
                <th data-field="end_effect" data-align="center">{{ trans('app.to_date') }}</th>

                <th data-field="score" data-align="center">{{ trans('app.score') }}</th>
                <th data-field="result" data-align="center" >{{ trans('backend.passed') }}</th>

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
            url: '{{ route('module.backend.user.roadmap.getdata',['user_id'=>$user_id]) }}',
        });
        function mergeRows(index, rowspan, field) {
            $('#tableroadmap').bootstrapTable('mergeCells', {
                index: index,
                field: field,
                rowspan: rowspan
            });
        }
        $(function () {
            $('#btnclick').on('click',function () {
                var table = document.getElementById('tableroadmap');
                var rowLength = table.rows.length - 1;
                var rowspan = 1;
                var start = 2;
                var row = table.rows[start].cells[2].innerHTML;
                var saveIndex = 0;
                var $y=1;
                for (var i = start+1; i <= rowLength; i += 1) {
                    if (row == table.rows[i].cells[2].innerHTML) {
                        rowspan++;
                        mergeRows(saveIndex, rowspan,'training_program_code');
                        mergeRows(saveIndex, rowspan,'training_program_name');
                    }else{
                        rowspan=1;
                        row = table.rows[i].cells[2].innerHTML;
                        saveIndex = $y;
                    }
                    $y++;
                }
            });
            $('.bootstrap-table').on('load-success.bs.table', function (e, name, args) {
                var table = document.getElementById('tableroadmap');
                var rowLength = table.rows.length - 1;
                if(rowLength<=2)
                    return false;
                var rowspan = 1;
                var start = 2;
                var row = table.rows[start].cells[2].innerHTML;
                var saveIndex = 0;
                var result ='';
                var $y=1;
                for (var i = start+1; i <= rowLength; i += 1) {
                    if (row == table.rows[i].cells[2].innerHTML) {
                        rowspan++;
                        result = table.rows[i].cells[13].innerHTML;
                        if(result=='Hoàn thành')
                            table.rows[i-1].cells[13].innerHTML='Hoàn thành';
                        mergeRows(saveIndex, rowspan,'training_program_code');
                        mergeRows(saveIndex, rowspan,'training_program_name');
                        mergeRows(saveIndex, rowspan,'status');
                    }else{
                        rowspan=1;
                        row = table.rows[i].cells[2].innerHTML;
                        saveIndex = $y;
                    }
                    $y++;
                }
            });
        });
    </script>

@endsection
