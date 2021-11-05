@extends('layouts.app')

@section('page_title', trans('app.attendance'))

@section('header')
    <style>
        .datepicker {
            box-sizing: border-box;
        }
    </style>
@endsection

@section('content')
    <div class="attendance_body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i> <span class="font-weight-bold">@lang('app.attendance')</span></h2>
                                </div>
                            </div>
                        </div>
                        <p></p>
                        <div class="row search-course pb-2">
                            <div class="col-md-12 form-inline">
                                <form class="form-inline" id="form-search">
                                    <input type="text" class="form-control" name="search" placeholder="{{ trans('app.enter_course_code_name') }}" />
                                    <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                                    <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                                    <button id ="btnSearch" class="btn btn-primary ml-2"><i class="fa fa-search"></i> {{ trans('app.search') }}</button>
                                </form>
                            </div>
                        </div>
                        <div>
                            <table class="tDefault table table-hover bootstrap-table table-bordered" >
                                <thead>
                                <tr>
                                    <th data-formatter="index_formatter" data-align="center">{{ trans('app.stt') }}</th>
                                    <th data-field="code" data-sortable="true">{{ trans('app.course_code') }}</th>
                                    <th data-field="name" data-sortable="true">{{ trans('app.course') }}</th>
                                    <th data-field="start_date"  data-sortable="true">{{ trans('app.start_date') }}</th>
                                    <th data-field="end_date" data-sortable="true">{{ trans('app.end_date') }}</th>
                                    <th data-field="review" data-align="center" data-formatter="attendance_formatter">{{ trans('app.attendance') }}</th>
                                    <th data-align="center" data-formatter="classify_formatter">{{ trans('app.classify') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function attendance_formatter(value, row, index) {
            return '<a href="'+ row.attendance_formatter +'">{{ trans('app.attendance') }}</a>';
        }

        function classify_formatter(value, row, index) {
            if (row.check_attendance > 0){
                return 'Đã điểm danh <br>' + row.check_attendance + '/' + row.total_register;
            }else{
                return 'Chưa điểm danh <br>' + row.check_attendance + '/' + row.total_register;
            }
        }
        var table = new LoadBootstrapTable({
            url: '{{ route('frontend.attendance.getData' ) }}',
            locale: '{{ \App::getLocale() }}',
            sort_name: 'start_date'
        });
    </script>
@stop
