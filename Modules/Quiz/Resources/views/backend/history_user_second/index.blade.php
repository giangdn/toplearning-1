{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử thi tuyển thí sinh bên ngoài')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.quiz') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Lịch sử thi tuyển thí sinh bên ngoài</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_examinee')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right">
                <a class="btn btn-info" href="javascript:void(0)" id="export-history-user-second">
                    <i class="fa fa-download"></i> Export
                </a>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-sortable="true" data-width="10px" data-field="code">{{trans('backend.employee_code')}}</th>
                <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{trans('backend.fullname')}}</th>
                <th data-field="dob" data-align="center">{{ trans('backend.dob') }}</th>
                <th data-field="identity_card" data-align="center">{{ trans('backend.identity_card') }}</th>
                <th data-field="email" data-align="center">Email</th>
                <th data-field="created_at2" data-align="center">{{ trans('backend.created_at') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function name_formatter(value, row, index) {
            return '<a href="'+ row.quiz_history_url +'">'+ value +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.history_user_second.getdata') }}',
        });

        $('#export-history-user-second').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.quiz.history_user_second.export') }}?'+form_search;
        });

    </script>
{{-- @endsection --}}
