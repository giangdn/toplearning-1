@extends('layouts.backend')

@section('page_title', 'Đánh giá hiệu quả đào tạo của nhân viên')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('module.plan_app.course')}}">Quản lý đánh giá Đánh giá hiệu quả đào tạo</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Đánh giá hiệu quả đào tạo của nhân viên</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{ trans('backend.enter_code_name_employee') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">

                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                {{--<th data-field="state" data-checkbox="true"></th>--}}
                <th data-width="10%" data-field="code">{{trans('backend.employee_code')}}</th>
                <th data-sort-name="full_name" data-width="20%" data-formatter="name_formatter" data-field="full_name" >{{trans('backend.fullname')}} </th>
                <th data-field="email">{{trans('backend.employee_email')}} </th>
                <th data-sort-name="title_name" data-field="title_name" >{{ trans('backend.title') }}</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent">{{ trans('backend.unit_manager') }}</th>
                <th data-field="status_text" data-formatter="status_text" data-align="center">{{trans('backend.status')}}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return (row.status_text == 'Lập kế hoạch') ? row.full_name : '<a href="'+ row.edit_url +'">'+row.full_name+'</a>';
        }
        function  status_text(value, row,index) {
            if (row.expired==1)
                return 'Hết hạn đánh giá';
            else
                return row.status_text;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.plan_app.user.getUsers',['course'=>$course_id,'type'=>$course_type]) }}',
        });

    </script>

@endsection
