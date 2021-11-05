@extends('layouts.backend')

@section('page_title', 'Quản lý table')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main" id="table-manager">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('approved-process-create')
                        <div class="btn-group">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal-create-table"><i class="fa fa-plus-circle"></i> @lang('backend.create')</button>
                        </div>
                    @endif
                    <div class="btn-group">
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-align="center" data-formatter="stt_formatter" data-width="50px">STT</th>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-formatter="code_formatter">model code</th>
                    <th data-field="name" >name</th>
                </tr>
            </thead>
        </table>
    </div>
    @include('tablemanager::modal.create')
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function code_formatter(value, row, index) {
            return '<a href="javascript:void(0)" data-id="'+row.id+'" class="edit text-success">'+value+' <i></i></a>';
        }
        function edit_formatter(value, row, index) {
            // return '<a href="javascript:void(0)" class="btn edit" data-id="'+row.id+'"><i class="fa fa-edit"></i> sửa</a>';
            return '<a href="javascript:void(0)" class="btn delete" data-id="'+row.id+'"><i class="fa fa-trash"></i> Xóa</a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.tablemanager.index') }}',
            remove_url: '{{ route('module.tablemanager.delete') }}',
            delete_method: 'delete'
        });
        $('#save-table-manager').on('click',function () {
            var $this = $(this);
            var form = $(this).closest('form');
            var url = form.attr('action');
            var formData = new FormData(form[0]);
            let btn = $(this);
            let current_icon = btn.find('i').attr('class');
            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);
            $.ajax({
                url: url,
                type: 'post',
                data: formData,
                dataType: 'json',
                cache:false,
                contentType: false,
                processData: false
            }).done(function (result) {
                show_message(result.message,result.status);
                if (result.status=='success'){
                    table.refresh();
                    $this.closest('.modal').modal('hide');
                }
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
            })
                .fail(function(result) {
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                    return false;
                });
        });
        $(document).on('click','#update-table-manager',function (e) {
            e.preventDefault();console.log(44);
            var $this = $(this);
            var form = $(this).closest('form');
            var url = form.attr('action');
            var formData = new FormData(form[0]);
            let btn = $(this);
            let current_icon = btn.find('i').attr('class');
            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);
            $.ajax({
                url: url,
                type: 'put',
                data: form.serialize(),
                dataType: 'json',
                cache:false,
                processData: false
            }).done(function (result) {
                show_message(result.message,result.status);
                if (result.status=='success'){
                    table.refresh();
                    $this.closest('.modal').modal('hide');
                }
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
            })
                .fail(function(result) {
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                    return false;
                });
        });
        $(document).on('click','#table-manager .edit', function (e) {console.log(3);
            let btn = $(this);
            let id = $(this).data('id');
            let current_icon = btn.find('i').attr('class');
            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);
            var url ='/admin-cp/table-manager/edit/'+id;
            $.get(url, {}, function (result){
                $("#app-modal").html(result);
                $("#app-modal #modal-edit-table").modal();
                load_user_select2();
                load_title_select2();
                btn.find('i').attr('class', '');
                btn.prop("disabled", false);
            },'html').fail(function(data) {
                btn.find('i').attr('class', '');
                btn.prop("disabled", false);
                return false;
            });

            var level = $.trim($(this).closest('tr').find('td:nth-child(4)').text());
            $('#level').val(level);
            $('#idapproved').val($(this).data('id'));
        });
    </script>
@endsection
