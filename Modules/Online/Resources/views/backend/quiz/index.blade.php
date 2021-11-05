@extends('layouts.backend')

@section('page_title', trans('backend.quiz_list'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.management') }}">{{ trans('backend.online_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.quiz_list') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-6 form-inline">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_exam')}}">

                    <input name="start_date" class="form-control datepicker w-25" placeholder="{{trans('backend.start_date')}}">

                    <input name="end_date" class="form-control datepicker w-25" placeholder="{{trans('backend.end_date')}}">

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('module.online.quiz.create', ['course_id' => $course_id]) }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    <th data-field="is_open" data-width="3%" data-formatter="is_open_formatter" data-align="center">{{trans('backend.status')}}</th>
                    <th data-field="code" data-width="5%" data-align="center">{{trans('backend.quiz_code')}}</th>
                    <th data-field="name" data-width="15%" data-formatter="name_formatter">{{trans('backend.quiz_name')}}</th>
                    <th data-field="quiz_type" data-width="7%" data-align="center">{{trans('backend.quiz_form')}}</th>
                    <th data-field="quiz_time" data-width="21%" data-formatter="quiz_time_formatter">{{trans('backend.time')}}</th>
                    <th data-field="limit_time" data-align="center" data-formatter="limit_time_formatter">{{trans('backend.time')}} <br> {{trans('backend.do_quiz')}}</th>
                    <th data-field="view_result" data-formatter="view_result_formatter" data-align="center" data-width="7%">{{trans('backend.see_result')}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.approved')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="register_formatter">{{trans('backend.action')}}</th>
                    <th data-field="quantity_quiz_attempts" data-width="10%" data-align="center" data-formatter="number_candidates_submission">
                        {{trans('backend.number_candidates_submission')}}
                    </th>
                    <th data-field="created_at2" data-align="center">{{trans('backend.create_time')}}</th>
                    <th data-field="user" data-align="center" data-formatter="created_formatter">{{trans('backend.user_create')}}</th>
                    <th data-field="time_approved" data-align="center">Thời gian duyệt</th>
                    <th data-field="user_approved" data-align="center" data-formatter="user_approved_formatter">Người duyệt</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function quiz_time_formatter(value, row, index) {
            return row.start_date + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : '');
        }

        function number_candidates_submission(value, row, index) {
            return row.quantity_quiz_attempts + ' / ' + row.quantity;
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " phút";
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approved") }}</span>' : (value == 2 ? '<span class="text-warning">Chưa ' +
                'duyệt</span>' : '<span class="text-danger">{{ trans("backend.deny") }}</span>');
        }

        function view_result_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.viewed") }}</span>' : '<span class="text-danger">{{ trans("backend.not_seen") }}</span>';
        }

        function is_open_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{trans("backend.enable")}}</span>' : '<spanclass="text-danger">{{trans("backend.disable")}}</span>';
        }

        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_url+'"><i class="fa fa-user"></i></a>';
        }

        function user_approved_formatter(value, row, index) {
            if (row.user_approved_url){
                return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_approved_url+'"><i class="fa fa-user"></i></a>';
            }
            return '';
        }

        function register_formatter(value, row, index) {
            let str = '';
            if (row.question) {
                str += '<a href="'+ row.question +'" class="btn btn-primary"><i class="fa fa-question-circle"></i> {{ trans("backend.question") }}</a> ';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_quiz', ['course_id' => $course_id]) }}',
        });
    </script>
@endsection
