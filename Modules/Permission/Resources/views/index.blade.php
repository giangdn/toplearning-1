@extends('layouts.backend')

@section('page_title', __('Quản lý quyền'))
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Quản lý quyền</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main" id="role">
        @if(isset($errors))

            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach

        @endif
        <form id="frm-role">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right act-btns">
                    <div class="pull-right">
                        <div class="btn-group">
                            <a class="btn btn-info" href="{{ route('backend.permissions.create') }}"><i class="fa fa-plus-circle"></i> @lang('backend.create')</a>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter" data-width="180" data-class="text-center">Tên </th>
                    <th data-field="description" >{{trans('backend.description')}}</th>
                    <th data-width="230" data-formatter="action_formatter">Thao tác</th>
                </tr>
                </thead>
            </table>
        </form>
        <script>
            $(function() {
                // $("#role").on('shown.bs.modal', '.edit-item', function () {
                $('#role').on('click','.permission',function () {
                    var role =$(this).data('role');
                    var roleName = $(this).closest('tr').find('td:first-child').text();
                    $('#role-name').html(roleName);
                    $('input[name=role]').val(role);
                    $('#table').bootstrapTable(
                        'refresh', {
                            url: '{{ url('/admin-cp/user/getdata/unassign-role')}}/'+role,
                        });
                });
            })
        </script>
        <script type="text/javascript">

            function name_formatter(value, row, index) {
                return '<a href="javascript:void(0)" data-id="'+row.id+'" class="edit-item">'+value+'</a>';
            }
            function type_formatter(value, row, index) {
                return value==1?'Hệ thống':'{{trans("backend.custom")}}';
            }
            function part_date_formatter(value, row, index) {
                return row.part_start_date + ' => ' + row.part_end_date;
            }
            function action_formatter(value, row, index) {
                if(row.type==1)
                    return '<a href="/admin-cp/role/edit/'+row.id+'" class="btn btn-warning"><i class="fa fa-edit"></i> Sửa quyền</a>';
                return '<a href="/admin-cp/role/edit/'+row.id+'" class="btn btn-warning"><i class="fa fa-edit"></i> Sửa quyền</a> ' +
                    ' <a href="javascript:void(0)" data-id="'+row.id+'"  class="btn btn-danger remove-item"><i class="fa fa-remove"></i> Xóa</a>';
            }
            $('#import-plan').on('click', function() {
                $('#modal-import').modal();
            });
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.permissions.getdata', []) }}',
                remove_url: '{{route('backend.permissions.delete')}}'
            });
        </script>
@endsection
