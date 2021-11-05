@extends('layouts.backend')

@section('page_title', 'Các khóa học')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{trans('backend.enter_code_name')}}" autocomplete="off">
                    <input name="start_date" type="text" class="datepicker form-control d-inline-block" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                    <input name="end_date" type="text" class="datepicker form-control d-inline-block" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code" data-width="10%">{{trans('backend.course_code')}}</th>
                    <th data-field="name" data-width="20%" data-formatter="name_formatter">{{trans('backend.course_name')}}</th>
                    <th data-field="start_date" data-formatter="date_formatter" data-width="15%" data-align="center">{{trans('backend.time')}}</th>
                    <th data-field="register_deadline" data-width="5%" data-align="center">{{trans('backend.register_deadline')}}</th>
                    <th data-field="count_approved" data-width="5%" data-align="center">Số lượng đã duyệt</th>
                    <th data-field="count_quantity_approved" data-width="5%" data-align="center">Số lượng cần duyệt</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function date_formatter(value, row, index) {
            return row.start_date + ( row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : '') ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.approve_student_cost.getdata') }}',
        });

    </script>

@endsection
