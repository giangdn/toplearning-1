@extends('layouts.backend')

@section('page_title', 'Nhóm quyền')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main" id="permissions-type">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('permission-group-create')
                            <a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="{{ route('module.permission.type.get_modal') }}"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-width="5%" data-class="text-center"># </th>
                <th data-sortable="true" data-field="type" data-width="10%" data-formatter="type_formatter" data-class="text-center">{{ trans('backend.type') }}</th>
                <th data-sortable="true" data-field="name" data-width="20%" data-class="text-center">{{ trans('backend.name') }} </th>
                <th data-field="description" data-width="30%">{{trans('backend.description')}}</th>
                <th data-field="created_by" data-width="10%" data-class="text-center">{{ trans('backend.user_create') }}</th>
                <th data-field="updated_by" data-width="10%" data-class="text-center">{{ trans('backend.user_updated') }}</th>
                <th data-formatter="action_formatter" data-width="15%" data-class="text-center">{{ trans('backend.actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }
        function type_formatter(value, row, index) {
            return value==1? "{{trans('backend.system')}}" : '{{trans("backend.custom")}}';
        }
        function action_formatter(value, row, index) {
            if(row.type==1)
                return '';
            else{
                var html ='';
                if(row.permission_edit)
                    html+= '<a href="javascript:void(0)" data-id="'+row.id+'" class="btn btn-warning edit-item"><i class="fa fa-edit"></i> {{trans("backend.edit")}}</a>';
                if(row.permission_delete)
                    html+=' <a href="javascript:void(0)" data-id="'+row.id+'" class="btn btn-danger remove-item"><i class="fa fa-trash"></i> {{trans("backend.delete")}}</a>';
                return html;
            }

        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.permission.type.getdata') }}',
            sort_name: 'sort',
            sort_order: 'asc',
            remove_url: '{{ route('module.permission.type.delete') }}'
        });
        $("#permissions-type").on('click', '.edit-item', function () {
            let item = $(this);
            let oldtext = item.html();
            let id = item.data('id');
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.permission.type.get_modal') }}',
                dataType: 'html',
                data: {
                    'id': id,
                },
            }).done(function(data) {
                item.html(oldtext);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.html(oldtext);
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
