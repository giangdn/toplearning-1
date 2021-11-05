@extends('layouts.backend')

@section('page_title', __('Quản lý vai trò'))
@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection
@section('content')
    <div role="main" id="role">
        <form id="frm-role">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4 text-right act-btns">
                    <div class="pull-right">
                        @can('role-create')
                            <a class="btn btn-info" href="{{ route('backend.roles.create') }}"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('role-export')
                                <a class="btn btn-info" href="{{ route('backend.roles.export') }}"><i class="fa fa-download"></i> Export</a>
                        @endcan
                    </div>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="tDefault table table-bordered bootstrap-table">
                    <thead>
                    <tr class="tbl-heading">
                        <th data-field="index" data-formatter="index_formatter" width="40px" rowspan="2" style="vertical-align: middle;">#</th>
                        <th rowspan="2" data-sortable="true" data-width="180px" data-field="code" data-class="text-center">{{ trans('backend.code') }} </th>
                        <th rowspan="2" data-sortable="true" data-width="180px" data-field="name" data-formatter="name_formatter" data-class="text-center">{{ trans('backend.name') }} </th>
{{--                        <th rowspan="2" data-field="type" data-width="180px" data-formatter="type_formatter"   data-class="text-center">{{ trans('backend.classify') }}</th>--}}
                        <th rowspan="2" data-field="description" data-width="200px">{{trans('backend.description')}}</th>
                        <th rowspan="2" data-field="created_by" data-width="180px" data-class="text-center">{{ trans('backend.user_create') }}</th>
                        @can('role-permission')
                        <th colspan="2" data-width="150px" data-class="text-center">{{ trans('backend.permission') }}</th>
                        @endcan
                        <th rowspan="2" data-width="380px" data-field="group_permission" data-formatter="group_permission_formatter" data-class="text-center">{{ trans('backend.permission_group') }}</th>
                        <th rowspan="2" data-width="380px" data-formatter="action_formatter" data-class="text-center">{{ trans('backend.manipulation') }}</th>
                    </tr>
                    <tr>
                        <th data-class="text-center" data-width="180px" data-formatter="permisstion_formatter">{{ trans('backend.user') }}</th>
                        <th data-class="text-center" data-width="180px" data-formatter="permisstion_title_formatter">{{ trans('backend.title') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </form>
        <div class="modal fade" id="modal-group-permission" >
            <div class="modal-dialog ">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <form action="{{route('backend.roles.group.permission.save')}}" method="post" id="form-group-permission" >
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans('backend.select_permission_group') }}</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                <label>{{ trans('backend.select_permission_group') }}</label>
                                <select name="group_permission" class="form-control load-group-permission" data-placeholder="-- {{ trans('backend.select_permission_group') }} --">
                                    <option value=""></option>
                                </select>
                                <input type="hidden" name="role_id" value="" />
                                <input type="hidden" name="redirect" value="true" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary button-save"><i class="fa fa-plus-circle"></i> {{ trans('backend.save') }}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function index_formatter(value, row, index) {
                return (index + 1);
            }
            function name_formatter(value, row, index) {
                return '<a href="javascript:void(0)" data-id="'+row.id+'" class="edit-item">'+value+'</a>';
            }
            function permisstion_formatter(value, row, index) {
                return '<a href="'+ row.user_role +'" ><i class="fa fa-users"></i></a>';
            }
            function permisstion_title_formatter(value, row, index) {
                return '<a href="'+ row.title_role +'" ><i class="fa fa-sitemap"></i></a>';
            }
            function type_formatter(value, row, index) {
                return value==1? '{{trans("backend.system")}}' : '{{trans("backend.custom")}}';
            }
            function part_date_formatter(value, row, index) {
                return row.part_start_date + ' => ' + row.part_end_date;
            }
            function group_permission_formatter(value, row, index) {
                if(row.permission_type_id)
                    return row.group_permission+' <a href="javascript:void(0)" class="permission_group" data-redirect="false" data-id='+row.id+'><i class="fa fa-edit"></i></a>';
                return  '-';
            }
            function action_formatter(value, row, index) {
                let html = '';

                @can('role-edit')
                    if(row.permission_type_id)
                        html += ' <a href="/admin-cp/role/edit/'+row.id+'" class="btn btn-warning"><i class="fa fa-cog"></i> {{trans("backend.permission")}}</a>';
                    else
                        html += ' <a href="javascript:void(0)" data-redirect="true" data-id="'+row.id+'" class="btn btn-warning permission_group"><i class="fa fa-cog"></i> {{trans("backend.permission")}}</a>';

                @endcan

                if(row.type == 2){
                    @can('role-delete')
                        html += ' <a href="javascript:void(0)" data-id="'+row.id+'"  class="btn btn-danger remove-item"><i class="fa fa-remove"></i> {{trans("backend.delete")}}</a>';
                    @endcan
                }
                return html;
            }
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.roles.getdata') }}',
                remove_url: '{{ route('backend.roles.delete') }}'
            });
            $(document).on('click','.permission_group',function () {
                $('#modal-group-permission').modal();
                let $id = $(this).data('id');
                let $redirect = $(this).data('redirect');
                $('#form-group-permission input[name="role_id"]').val($id);
                $('#form-group-permission input[name="redirect"]').val($redirect);
            });
            $('#form-group-permission .button-save').on('click',function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var formData = new FormData(form[0]);
                var btnsubmit = form.find("button:focus");
                var oldText = btnsubmit.text();
                var currentIcon = btnsubmit.find('i').attr('class');
                var submitSuccess = form.data('success');
                var exists = btnsubmit.find('i').length;
                if (exists>0)
                    btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
                else
                    btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);

                btnsubmit.prop("disabled", true);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false
                }).done(function(data) {

                    show_message(
                        data.message,
                        data.status
                    );

                    if (data.redirect) {
                        setTimeout(function () {
                            window.location = data.redirect;
                        }, 1000);
                        return false;
                    }else{
                        table.refresh();
                        $('#modal-group-permission').modal('hide');
                    }
                    if (exists>0)
                        btnsubmit.find('i').attr('class', currentIcon);
                    else
                        btnsubmit.html(oldText);
                    btnsubmit.prop("disabled", false);

                    if (data.status === "error") {
                        return false;
                    }

                    if (submitSuccess) {
                        eval(submitSuccess)(form);
                    }

                    return false;
                }).fail(function(data) {
                    if (exists>0)
                        btnsubmit.find('i').attr('class', currentIcon);
                    else
                        btnsubmit.html(oldText);
                    btnsubmit.prop("disabled", false);

                    show_message('{{ trans('lageneral.data_error ') }}', 'error');
                    return false;
                });
            });

        </script>

@endsection
