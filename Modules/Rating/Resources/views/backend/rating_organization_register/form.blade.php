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
        Đánh giá hiệu quả đào tạo <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.rating_organization') }}">Tổ chức đánh giá</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.rating_organization.edit', ['id' => $rating_levels->id]) }}">{{ $rating_levels->name }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.rating_organization.register', ['id' => $rating_levels->id]) }}">Nhân viên</a>
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
                        <button type="submit" id="button-register" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.register') }}</button>
                        <a href="{{ route('module.rating_organization.register', ['id' => $rating_levels->id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500, ALL]">
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
        var ajax_get_user = "{{ route('module.rating_organization.register.save', ['id' => $rating_levels->id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_organization.register.getdata_not_register', ['id' => $rating_levels->id]) }}',
            field_id: 'user_id',
        });

        $('#button-register').on('click', function() {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: ajax_get_user,
                dataType: 'json',
                data: {
                    ids: ids
                },
            }).done(function(data) {
                show_message(
                    'Ghi danh thành công',
                    'success'
                );
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {

                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });
    </script>

@stop
