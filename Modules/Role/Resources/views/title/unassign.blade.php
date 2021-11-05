@extends('layouts.backend')

@section('page_title', __('Quản lý vai trò'))
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.permission') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.roles') }}">{{ trans('backend.role_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.roles.title.assign_role', ['role' => $role->id]) }}">{{ trans('backend.role') }} {{ $role->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Chức danh</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main" id="role">
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline w-100 form-search mb-3" id="form-search">
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" id="btnSave" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code">{{ trans('backend.code') }}</th>
                <th data-field="name">{{ trans('backend.title') }}</th>
                <th data-field="position">Chức vụ</th>
            </tr>
            </thead>
        </table>
        <input type="hidden" name="role" value="{{ $role->id }}">

        <script>
            $(function() {
                $('#btnSave').on('click', function () {
                    var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                    var role = $('input[name=role]').val();
                    var btn = $(this),
                        btn_text = btn.html();
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
                    if (ids.length <= 0) {
                        show_message('Vui lòng chọn chức danh cần phân quyền', 'error');
                        btn.prop('disabled', false).html(btn_text);
                        return false;
                    }

                    $.ajax({
                        url: '{{ route('backend.roles.title.save', ['role' => $role->id]) }}',
                        type: 'post',
                        dataType:'json',
                        data: {
                            ids: ids,
                        }
                    }).done(function(data) {
                        btn.prop('disabled', false).html(btn_text);
                        show_message(data.message, data.status);
                        if(data.status=='success')
                            $(table.table).bootstrapTable('refresh');
                    }).fail(function(data) {
                        btn.prop('disabled', false).html(btn_text);
                        show_message('Lỗi hệ thống', 'error');
                        return false;
                    });
                });
            })
        </script>
        <script type="text/javascript">
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.role.title.getdata.unassign.role', ['role' => $role->id]) }}',
            });
        </script>
@endsection
