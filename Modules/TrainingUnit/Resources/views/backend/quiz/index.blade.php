@extends('layouts.backend')

@section('page_title', 'Các kỳ thi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_unit') }}">{{ trans('backend.training_unit') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.quiz_list') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('error') }}
        </div>
        @php
            session()->forget('error');
        @endphp
    @endif
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_exam')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @can('training-unit-quiz-view-result')
                    <div class="btn-group">
                        <button class="btn btn-primary result" data-status="1">
                            <i class="fa fa-check-circle"></i> Cho xem kết quả
                        </button>
                        <button class="btn btn-danger result" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> Tắt xem kết quả
                        </button>
                    </div>
                    @endcan
                    @can('training-unit-quiz-status')
                    <div class="btn-group">
                        <button class="btn btn-primary publish" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning publish" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    @endcan
                    <p></p>
                    <div class="btn-group">
                        @can('training-unit-quiz-copy')
                        <button class="btn btn-warning copy">
                            <i class="fa fa-copy"></i> &nbsp;Copy
                        </button>
                        @endcan
                        @can('training-unit-quiz-create')
                        <a href="{{ route('module.training_unit.quiz.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('training-unit-quiz-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="is_open" data-width="5%" data-formatter="is_open_formatter" data-align="center">{{ trans('lacore.open') }}</th>
                    <th data-field="code" data-width="10%">{{trans('backend.quiz_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.quiz_name')}}</th>
                    <th data-field="limit_time" data-align="center" data-width="10%" data-formatter="limit_time_formatter">{{trans('backend.time_quiz')}}</th>
                    <th data-field="view_result" data-formatter="view_result_formatter" data-align="center" data-width="10%">{{trans('backend.see_result')}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{trans('backend.status')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="register_formatter" data-width="20%"></th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " phút";
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approved") }}</span>' : (value == 2 ? '<span class="text-warning">Chưa ' +
                'duyệt</span>' : '<span class="text-danger">{{ trans("backend.deny") }}</span>');
        }

        function view_result_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">Được xem</span>' : '<span style="color: red;">Không được xem</span>';
        }

        function is_open_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">{{trans("backend.enable")}}</span>' : '<span style="color: red;">{{trans("backend.disable")}}</span>';
        }

        function register_formatter(value, row, index) {
            let str = '';
            if (row.question) {
                str += '<a href="'+ row.question +'" class="btn btn-primary"> <i class="fa fa-question-circle"></i> {{ trans("backend.question") }}</a> ';
            }
            if (row.register_url){
                str += '<a href="'+ row.register_url +'" class="btn btn-info"> <i class="fa fa-users"></i> {{ trans("backend.internal_contestant") }}</a> <p></p> ';
            }
            if (row.result){
                str += '<a href="'+ row.result +'" class="btn btn-success"><i class="fa fa-eye"></i></i> {{ trans("backend.result") }}</a> ';
            }
            if (row.export_url) {
                str += ' <a href="'+ row.export_url +'" class="btn btn-link"><i class="fa fa-download"></i> In đề thi</a>';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.quiz.getdata') }}',
            remove_url: '{{ route('module.training_unit.quiz.remove') }}'
        });
        var ajax_isopen_publish = "{{ route('module.training_unit.quiz.ajax_is_open') }}";
        var ajax_status = "{{ route('module.training_unit.quiz.ajax_status') }}";
        var ajax_view_result = "{{ route('module.training_unit.quiz.ajax_view_result') }}";
        var ajax_copy_quiz = "{{ route('module.training_unit.quiz.ajax_copy_quiz') }}";
    </script>
    <script src="{{ asset('styles/module/training_unit/js/quiz.js') }}"></script>
@endsection
