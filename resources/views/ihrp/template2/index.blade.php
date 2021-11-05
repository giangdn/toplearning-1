@extends('layouts.backend')

@section('page_title', 'Template 2 - Chi tiết khóa đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i><a href="{{ route('backend.ihrp') }}">IHRP</a> <i class="uil uil-angle-right"></i> Template 2 - Chi tiết khóa đào tạo</h2>
    </div>
@endsection
@section('content')
    <div role="main">
        <form class="form" id="form-search">
            <div class="row">
                <div class="col-sm-3 my-1">
                    <input name="start_date" type="text" class="datepicker form-control" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                </div>

                <span class="my-1"><i class="fa fa-arrow-right"></i></span>

                <div class="col-sm-3 my-1">
                    <input name="end_date" type="text" class="datepicker form-control" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                </div>

                <div class="col-sm-3 my-1">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                        <i class="fa fa-download"></i> Export
                    </a>
                </div>
            </div>
        </form>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="1%">#</th>
                <th data-field="code" data-width="5%" data-align="center">{{ trans('backend.course_code') }}</th>
                <th data-field="training_form" data-width="15%">{{trans('backend.training_program_form')}}</th>
                <th data-field="teachers">Hướng dẫn bởi</th>
                <th data-field="" data-width="5%">Lĩnh vực đào tạo</th>
                <th data-field="training_plan" data-width="1%" data-align="center">Nơi đào tạo</th>
                <th data-field="" data-width="1%" data-align="center">Cơ sở đánh giá</th>
                <th data-field="course_cost" data-width="10%" data-align="center">Tổng cộng</th>
            </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index+1);
        }

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('backend.ihrp.export_template2') }}?'+form_search;
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.ihrp.getdata_template2') }}',
        });
x``
    </script>
@endsection
