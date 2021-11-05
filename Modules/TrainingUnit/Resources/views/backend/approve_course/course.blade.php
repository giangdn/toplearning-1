@extends('layouts.backend')

@section('page_title', 'Các khóa học')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }}
            <i class="uil uil-angle-right"></i>
            {{ trans('app.approve_register') }}
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_unit.approve_course') }}">{{trans('backend.all_course')}}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ $course->name }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">

                    <input type="text" name="search" class="form-control" placeholder="{{trans('backend.code_name_course')}}" autocomplete="off">

                    <input name="start_date" type="text" class="datepicker form-control d-inline-block" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off">

                    <input name="end_date" type="text" class="datepicker form-control d-inline-block" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off">

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success approved" data-model="{{$model}}" data-status="1"><i class="fa fa-check-circle"></i> {{trans('backend.approve')}}</button>
                        <button type="button" class="btn btn-danger approved" data-model="{{$model}}" data-status="0"><i class="fa fa-times-circle"></i> {{trans('backend.deny')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                    <th data-field="code" data-width="10%">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter" data-width="20%">{{trans('backend.employee_name')}}</th>
                    <th data-field="email" >{{trans('backend.employee_email')}}</th>
                    <th data-field="title_name" data-width="15%">{{trans('backend.title')}}</th>
                    <th data-field="unit_name" data-width="15%">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('backend.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' '+row.firstname;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2 || null: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="{{$model_register}}" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.approve_course.course.getdata', ['id' => $course->id, 'type' => $type]) }}',
        });

    </script>

@endsection
