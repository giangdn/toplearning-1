@extends('layouts.backend')

@section('page_title', 'Nhóm quyền')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.permission') }}">Phân quyền</a> / {{ trans('backend.permission_group') }}
        </h2>
    </div>
@endsection

@section('content')
    <div role="main" id="permission-group">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>

                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="javascript:void(0)" class="btn btn-primary" id="add-permissin-group"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">Tên nhóm quyền</th>
                    <th data-field="created_date" data-width="20%">{{trans('backend.created_at')}}</th>
                    <th data-field="created_name" data-width="20%">{{ trans('backend.created_by') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-permission-group">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('backend.permission_group.save') }}" method="post" class="form-ajax form-validate" data-success="save_success">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Tên nhóm quyền</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="edit-item" data-id="'+ row.id +'">'+ value +'</a>';
        }

        function save_success(form) {
            $('.bootstrap-table').bootstrapTable('refresh');
            $("#modal-permission-group").modal('hide');
        }

        $(function () {

            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: "{{ route('backend.permission_group.getdata') }}",
                remove_url: '{{ route('backend.permission_group.remove') }}',
            });

            $("#add-permissin-group").on('click', function () {
                $("#modal-permission-group .modal-title").html('<i class="fa fa-edit"></i> Thêm nhóm quyền');
                $("#modal-permission-group input").val("");
                $("#modal-permission-group").modal();
            });

            $("#permission-group").on('click', '.edit-item', function () {
                let id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('backend.permission_group.getjson') }}',
                    dataType: 'json',
                    data: {
                        'id': id
                    }
                }).done(function(data) {
                    $("#modal-permission-group .modal-title").html('<i class="fa fa-edit"></i> '+ data.name);
                    $("#modal-permission-group #id").val(data.id);
                    $("#modal-permission-group #name").val(data.name);
                    $("#modal-permission-group").modal();
                    return false;
                }).fail(function(data) {
                    return false;
                });

            });
        });
    </script>
@stop
