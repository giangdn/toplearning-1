@extends('layouts.backend')

@section('page_title', 'Khóa học offline')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_unit') }}">{{ trans('backend.training_unit') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.online_course') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8 ">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{trans('backend.code_name_course')}}">
                    <div class="w-25">
                        <select name="category_id" id="category_id" class="form-control select2" data-placeholder="-- Chọn danh mục --">
                            <option value=""></option>
                            @foreach($course_categories as $course_category)
                                <option value="{{ $course_category['id'] }}"> {{ $course_category['name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{trans('backend.time')}} </label>
                        <div>
                            <span><input name="start_date" type="text" class="datepicker form-control d-inline-block date-custom" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value=""></span>
                            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                            <span><input name="end_date" type="text" class="datepicker form-control d-inline-block date-custom" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value=""></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn btn-success approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{trans("backend.approve")}}
                        </button>
                        <button class="btn btn-danger approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}
                        </button>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-primary publish" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning publish" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    <p></p>
                    <div class="btn-group">
                        <a href="{{ route('module.training_unit.online.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="isopen" data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('lacore.open') }}</th>
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.course_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.course_name') }}</th>
                    <th data-field="action_plan" data-align="center" data-formatter="action_plan_formatter">{{trans('backend.plan')}}</th>
                    <th data-field="category_name">{{ trans('backend.category') }}</th>
                    <th data-field="unit_name" data-width="15%">{{ trans('backend.unit') }}</th>
                    <th data-field="subject_name">Tên học phần</th>
                    <th data-sortable="true" data-field="start_date" data-formatter="date_formatter" data-width="13%">{{trans('backend.time')}}</th>
                    <th data-field="created_at2" data-align="center">{{trans('backend.created_at')}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="7%">{{trans('backend.status')}}</th>
                    <th data-align="center" data-formatter="register_formatter" data-width="5%">{{ trans('backend.register') }}</th>
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
            let register = '';

            if (row.register_url) {
                register = '<a href="'+ row.register_url +'"><i class="fa fa-user"></i></a>';
            }

            return register;
        }
        var ajax_isopen_publish = "{{ route('module.training_unit.online.ajax_isopen_publish') }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.online.getdata') }}',
            remove_url: '{{ route('module.training_unit.online.remove') }}'
        });

    </script>
    <script src="{{ asset('styles/module/training_unit/js/online.js') }}"></script>
@endsection
