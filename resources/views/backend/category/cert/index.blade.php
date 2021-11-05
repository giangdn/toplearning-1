@extends('layouts.backend')

@section('page_title', trans('backend.level'))

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
            <span class="">{{ trans('backend.level') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-cert-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('category-cert-delete')
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
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="certificate_code">{{ @trans('backend.level_code') }}</th>
                    <th data-sortable="true" data-field="certificate_name" data-formatter="name_formatter">{{ @trans('backend.level_name') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title" id="exampleModalLabel"></h5> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['category-cert-create', 'category-cert-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.certificate_name +'</a>' ;
        }
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.cert.getdata') }}',
            remove_url: '{{ route('backend.category.cert.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('backend.category.cert.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('Chỉnh sửa ' + data.certificate_name);
                $('#body_modal').html(`<input type="hidden" name="id" value="`+ data.id +`">
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="certificate_code">{{ @trans('backend.level_code') }}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="certificate_code" type="text" class="form-control" value="`+ data.certificate_code +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="certificate_name">{{ @trans('backend.level_name') }}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="certificate_name" type="text" class="form-control" value="`+ data.certificate_name +`" required>
                                            </div>
                                        </div>`)
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=certificate_name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=certificate_code]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.cert.save') }}",
                type: 'post',
                data: {
                    'certificate_name': name,
                    'certificate_code': code,
                    'id': id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
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

        function create() {
            $('#exampleModalLabel').html('Thêm trình độ');
            $('#body_modal').html(`<input name="id" type="hidden" class="form-control" value="">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="certificate_code">{{ @trans('backend.level_code') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="certificate_code" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="certificate_name">{{ @trans('backend.level_name') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="certificate_name" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>`)
            $('#modal-popup').modal();
        }
    </script>
@endsection
