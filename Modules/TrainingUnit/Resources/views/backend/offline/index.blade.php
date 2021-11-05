@extends('layouts.backend')

@section('page_title', 'Khóa học tập trung')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_unit') }}">{{ trans('backend.training_unit') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.offline_course') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-9">
                <form id="form-search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('backend.code_name_course') }}">
                        </div>

                        <div class="col-sm-3 my-1">
                            <select name="training_program_id" class="form-control select2" data-placeholder="{{trans('backend.training_program')}}">
                                <option value=""></option>
                                @foreach($training_program as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-3 my-1">
                            <select name="subject_id" class="form-control select2" data-placeholder="{{trans('backend.choose_subject')}}">
                                <option value=""></option>
                                @foreach($subject as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-2 my-1">
                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                        </div>

                        <div class="col-sm-2 my-1">
                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
{{--                    <th data-field="state" data-checkbox="true"></th>--}}
                    <th data-sortable="true" data-field="isopen" data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('lacore.open') }}</th>
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.course_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.course_name') }}</th>
                    <th data-field="action_plan" data-align="center" data-formatter="action_plan_formatter">{{trans('backend.plan')}}</th>
                    <th data-field="unit_name">{{ trans('backend.unit') }}</th>
                    <th data-field="subject_name">Tên học phần</th>
                    <th data-sortable="true" data-field="start_date" data-formatter="date_formatter" data-width="13%">{{trans('backend.time')}}</th>
                    <th data-field="created_at2" data-align="center">{{trans('backend.created_at')}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.status')}}</th>
{{--                    <th data-field="regist" data-align="center" data-formatter="register_formatter" data-width="5%">{{ trans('backend.register') }}</th>--}}
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name+'</a>';
        }
        function date_formatter(value, row, index) {
            return row.start_date +' <i class="fa fa-long-arrow-right"></i> ' + row.end_date;
        }
        function isopen_formatter(value, row, index) {
            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-exclamation-triangle text-warning"></i> ';
        }
        function action_plan_formatter(value, row, index) {
            return value == 1 ? 'Có' : 'Không';
        }
        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{trans("backend.deny")}}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        function register_formatter(value, row, index) {
            if (row.register_url){
                return '<a href="'+ row.register_url +'">'+'<i class="fa fa-user"></i>'+'</a>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.offline.getdata') }}',
            remove_url: '{{ route('module.training_unit.offline.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.training_unit.offline.ajax_isopen_publish') }}";
    </script>
    <script src="{{ asset('styles/module/training_unit/js/offline.js') }}"></script>
@endsection
