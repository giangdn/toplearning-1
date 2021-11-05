@extends('layouts.backend')

@section('page_title', 'Chấm điểm kỳ thi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

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

                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code" data-width="5%">{{trans('backend.quiz_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter" data-width="25%">{{trans('backend.quiz_name')}}</th>
                    <th data-field="limit_time" data-align="center" data-width="10%" data-formatter="limit_time_formatter">{{trans('backend.time_quiz')}}</th>
                    <th data-field="quantity" data-width="10%" data-align="center">{{trans('backend.number_student')}}</th>
                    <th data-field="quantity_quiz_attempts" data-width="10%" data-align="center">{{trans('backend.number_submission')}}</th>
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

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.grading.data_quiz') }}',
        });
    </script>

@endsection
