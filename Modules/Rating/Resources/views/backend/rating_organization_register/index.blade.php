@extends('layouts.backend')

@section('page_title', 'Quản lý Ghi danh')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Đánh giá hiệu quả đào tạo <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_organization') }}">Tổ chức đánh giá</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_organization.edit', ['id' => $rating_levels->id]) }}">{{ $rating_levels->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span>Nhân viên</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search-user mb-3" id="form-search-user">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('backend.enter_code_name__email_username_employee') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('module.rating_organization.register.create', ['id' => $rating_levels->id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500, ALL]" id="list-user-registed">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5px">{{ trans('backend.employee_code') }}</th>
                    <th data-field="full_name" data-width="20%">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_organization.register.getdata', ['id' => $rating_levels->id]) }}',
            remove_url: '{{ route('module.rating_organization.register.remove', ['id' => $rating_levels->id]) }}',
            table: '#list-user-registed',
            form_search: '#form-search-user'
        });
    </script>
@endsection
