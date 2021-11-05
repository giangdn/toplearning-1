@extends('layouts.backend')

@section('page_title', 'Thêm mới')

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
        <a href="{{ route('module.online.edit', ['id' => $online->id]) }}">{{ $online->name }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.online.register', ['id' => $online->id]) }}">{{ trans('backend.enrollment_management') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ trans('backend.add_new') }}</span>
    </h2>
</div>
<div role="main">
        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('backend.enter_code_name') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @canany(['online-course-register-create', 'online-course-register-edit'])
                        <button type="submit" id="button-register" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.register') }}</button>
                        @endcanany
                        <a href="{{ route('module.online.register', ['id' => $online->id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500, ALL]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code">{{ trans('backend.employee_code') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name" data-width="20%">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager" data-width="20%">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        var ajax_get_user = "{{ route('module.online.register.save', ['id' => $online->id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.register.getDataNotRegister', ['id' => $online->id]) }}',
            field_id: 'user_id'
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/online/js/register.js') }}"></script>

@stop
