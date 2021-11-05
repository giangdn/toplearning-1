@extends('layouts.app')

@section('page_title', trans('backend.virtual_classroom'))

@section('header')
    <style>
        .datepicker {
            box-sizing: border-box;
        }
    </style>
@endsection

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        @lang('app.course') <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">@lang('backend.virtual_classroom')</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <p></p>
                        <div class="row search-course pb-2">
                            <div class="col-md-12 form-inline">
                                <form class="form-inline" id="form-search">
                                    <input type="text" class="form-control" name="q" placeholder="{{ trans('app.enter_course_code_name') }}" />
                                    <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                                    <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                                    <button id ="btnSearch" class="btn btn-primary ml-2"><i class="fa fa-search"></i> {{ trans('app.search') }}</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="tDefault table table-hover bootstrap-table">
                                    <thead>
                                    <tr>
                                        <th data-field="stt" data-formatter="stt_formatter" data-align="center" data-width="5%">{{ trans('app.stt') }}</th>
                                        <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('app.course') }}</th>
                                        <th data-formatter="date_formatter" data-align="center">{{ trans('app.time') }}</th>
                                        <th data-formatter="quantity_formatter" data-align="center" data-width="5%">{{ trans('app.quantity') }}</th>
                                        <th data-field="status" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                                        <th data-field="teacher" data-align="center">{{ trans('app.teacher') }}</th>
                                        <th data-field="first_time_join" data-align="center" data-width="5%">{{ trans('app.first_time_access') }}</th>
                                        <th data-field="last_time_join" data-align="center" data-width="5%">{{ trans('app.last_time_access') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }

        function name_formatter(value, row, index) {
            if(row.url_bbb){
                return '<a href="'+ row.url_bbb +'" target="_blank">'+ row.course_name + ' (' + row.course_code + ') </a> <br> + ' + row.bbb_name + ' - ' + row.bbb_code;
            }else{
                return row.course_name + ' (' + row.course_code + ') <br> + ' + row.bbb_name + ' - ' + row.bbb_code;
            }
        }

        function date_formatter(value, row, index) {
            return row.start_date +' <i class="uil uil-arrow-right"></i> ' + row.end_date;
        }

        function quantity_formatter(value, row, index) {
            return row.quantity + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.quantity_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.online.getdata.virtualclassroom') }}',
            locale: '{{ \App::getLocale() }}'
        });
    </script>
@stop
