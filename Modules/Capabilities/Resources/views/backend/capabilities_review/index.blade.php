@extends('layouts.backend')

@section('page_title', trans('backend.capabilities'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.capabilities') }}
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-50">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                        <input type="text" name="join_company" class="form-control datepicker" placeholder="{{ trans('backend.day_work') }}" autocomplete="true">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right">
                <div class="pull-right">
                    @can('capabilities-result')
                        <a href="{{ route('module.capabilities.review.result.index') }}" class="btn btn-primary">{{ trans('backend.dev_training_plan') }}</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="10%">{{ trans('backend.employee_code') }}</th>
                    <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="join_company">{{ trans('backend.day_work') }}</th>
                    <th data-field="action" data-align="center" data-formatter="action_formatter">{{ trans('backend.action') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return row.lastname +' '+ row.firstname;
        }

        function action_formatter(value, row, index) {
            var html = '';
            html += '<a href="'+ row.review_url +'" class="btn btn-info btn-sm"><i class="fa fa-list"></i> {{ trans('backend.assessments') }}</a> ';
            @can('capabilities-review-create')
                html += ' <a href="'+ row.create_url +'" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> {{ trans('backend.new_review') }}</a> ';
            @endcan
            html += ' <a href="'+ row.course_url +'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> {{ trans('backend.capabilities') }}</a>';

            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.capabilities.review.getdata') }}',
        });

    </script>

@endsection
