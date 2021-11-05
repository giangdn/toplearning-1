{{-- @extends('layouts.backend')

@section('page_title', 'Kết quả lộ trình đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> Kết quả lộ trình đào tạo</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form_search_user" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="">
                        <select name="status" class="form-control select2" data-placeholder="-- {{ trans('backend.status') }} --">
                            <option value=""></option>
                            <option value="0">{{ trans('backend.inactivity') }}</option>
                            <option value="1">{{ trans('backend.doing') }}</option>
                            <option value="2">{{ trans('backend.probationary') }}</option>
                            <option value="3">{{ trans('backend.pause') }}</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên/ email', 'Enter the staff name / code/ email') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>

        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                <th data-field="complete" data-align="center" data-width="10%">{{ trans('backend.completed') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.lastname + ' ' + row.firstname + '</a>';
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_by_title.result.getdata_user') }}',
            field_id: 'user_id'
        });
    </script>
{{-- @endsection --}}
