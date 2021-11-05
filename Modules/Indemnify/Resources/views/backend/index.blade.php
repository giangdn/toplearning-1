@extends('layouts.backend')

@section('page_title', 'Quản lý bồi hoàn')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- @lang('backend.title') --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right">
                <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                    <i class="fa fa-download"></i> Export
                </a>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-width="1%" data-align="center" data-field="code">@lang('backend.employee_code')</th>
                <th data-sortable="true" data-field="firstname" data-formatter="name_formatter" data-width="20%">@lang('backend.fullname')</th>
                <th data-field="email" data-width="20%">{{ trans('backend.employee_email') }}</th>
                <th data-sortable="true" data-field="title_name">@lang('backend.title')</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent">{{ trans('backend.unit_manager') }}</th>
                <th data-field="num_course" data-align="center" data-width="5%">@lang('backend.number_committed_keys')</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.detail_url +'">'+ row.lastname +' '+row.firstname+'</a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.indemnify.getdata') }}',
            locale: '{{ App::getLocale() }}',
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.indemnify.export') }}?'+form_search;
        });
    </script>

@endsection
