@extends('layouts.backend')

@section('page_title', __('Phân quyền phê duyệt'))
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title">
            <i class="uil uil-apps"></i>
            <a href="{{ route('backend.approved.process.index') }}">Quy trình phê duyệt</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Phân quyền phê duyệt</span>
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
        <form id="form-search" method="get">
            <div class="row">
                <div class="col-md-6">
                    
                    <select name="model_approved" id="model_approved" class="form-control">
                        @foreach($modelApproved as $item)
                            <option value="{{$item->model}}" {{$item->model==request()->query('model_approved')?'selected':''}} >{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="unit_id" id="unit_id" class="form-control" readonly="readonly">
                        <option value="{{$unit->id}}" selected >{{$unit->name}}</option>
                    </select>
                </div>
            </div>
            <br>

            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th  data-field="object_name" data-width="200" data-class="text-center">Cấp duyệt </th>
                    <th  data-field="full_name"  data-width="200"  >Nhân viên </th>
                    <th  data-field="title_name"  data-width="200" >Chức danh </th>
                    <th data-field="level" data-width="200" data-formatter="level_formatter" >Thứ tự duyệt</th>
                    @can('approved-process-edit')
                    <th data-width="80" data-formatter="action_formatter">Thao tác</th>
                    @endcan
                </tr>
                </thead>
            </table>
            <div class="row" style="padding-top: 5px">
                <div class="col-md-6 text-right">
                    @can('approved-process-create')
                    <button type="button" class="btn btn-primary add-approved" data-url="{{route('backend.permission.approved.create')}}" id="add-approved">
                        <i class="fa fa-plus"></i>
                    </button>
                    @endcan
                    @can('approved-process-delete')
                    <button type="button" class="btn btn-primary del-approved" data-url="{{route('backend.permission.approved.delete')}}" id="del-approved">
                        <i class="fa fa-minus"></i>
                    </button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
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
            function level_formatter(value, row, index) {
                return 'Phê duyệt cấp '+ value;
            }
            function type_formatter(value, row, index) {
                return value==1?'Hệ thống':'{{trans("backend.custom")}}';
            }
            function part_date_formatter(value, row, index) {
                return row.part_start_date + ' => ' + row.part_end_date;
            }
            function action_formatter(value, row, index) {
                return '<a href="javascript:void(0)" data-id="'+row.id+'" data-url="{{url('/admin-cp/permission-approved/edit')}}/'+row.id+'" class="btn edit-approved"><i class="fa fa-edit"></i> Sửa </a>';
            }
            $('#import-plan').on('click', function() {
                $('#modal-import').modal();
            });
            var locale = '{{ \App::getLocale() }}',
                url = '{{ route('backend.permission.approved.index', []) }}',
                remove_url = '{{route('backend.permissions.delete')}}';
        </script>
    <script src="{{ asset('styles/module/PermissionApproved/js/permissionapproved.js?v='.time()) }}"></script>
@endsection
