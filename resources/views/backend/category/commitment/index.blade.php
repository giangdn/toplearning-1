@extends('layouts.backend')

@section('page_title', trans('backend.commit'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title">
            <a href="{{ route('backend.category') }}">
                <i class="far fa-arrow-alt-circle-left"></i>
                {{ trans('backend.category') }}
            </a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.commit') }}</span>
        </h2>
    </div>
    <div role="main" id="daily-training-category">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('commit-month-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </button>
                        @endcan
                        @can('commit-month-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="group" data-formatter="edit_formatter">Nhóm</th>
                    <th data-field="titles" >{{ trans('backend.title_rank') }}</th>
                    <th data-align="center" data-width="140px" data-formatter="button_formatter"> Khung cam kết</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="btn-group act-btns">
                            @canany(['commit-month-create', 'commit-month-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-4 pr-0 control-label">
                                        <label>Nhóm <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input name="group" required type="text" placeholder="Nhập tên nhóm" class="form-control" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4 pr-0 control-label">
                                        <label>{{ trans('backend.title_rank') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8" id="title-rank">
                                        <select name="titles[]" class="load-title-rank" required multiple data-placeholder="{{trans('backend.choose_title')}}">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>
                </form>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        function button_formatter(value, row, index) {
            return '<button class="btn btn-primary btnCommit" data-id='+row.id+'><i class=""></i> Khung cam kết</button>';
        }
        function edit_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.group +'</a>' ;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.commit_month.getdata') }}',
            remove_url: '{{ route('backend.category.commit_month.remove') }}'
        });
        $(document).on('click','.btnCommit',function (e) {
            let item = $(this);
            let icon = item.find('i').attr('class');
            let id = $(this).data('id');
            let url = '{{route('backend.category.commit_month.modal')}}';
            item.find('i').attr('class', 'fa fa-spinner fa-spin');
            item.prop("disabled", true);
            item.addClass('disabled');
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'html',
                data: {'commit_group':id},
            }).done(function(data) {
                item.find('i').attr('class', icon);
                item.prop("disabled", false);
                item.removeClass('disabled');
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.find('i').attr('class', icon);
                item.prop("disabled", false);
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        })

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.commit_month.save_group') }}",
                type: 'post',
                data: $("#form_save").serialize(),
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#myModal2').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('backend.category.commit_month.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $("input[name=id]").val(data.model.id);
                $("input[name=group]").val(data.model.group);
                
                $("#title-rank select").html('');
                if (data.titles) {
                    $.each(data.titles, function (index, value) { 
                        $("#title-rank select").append('<option value="'+ value.id +'" selected>'+ value.name +'</option>');
                    });
                }

                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#form_save').trigger("reset");
            $("input[name=id]").val('');
            $("input[name=group]").val('');
            $("#title-rank select").html('');
            $('#myModal2').modal();
        }
    </script>
@endsection
