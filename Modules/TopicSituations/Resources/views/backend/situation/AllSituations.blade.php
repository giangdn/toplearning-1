@extends('layouts.backend')

@section('page_title', trans('backend.situations_discuss'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('module.topic_situations')}}" class="">{{$model->name}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.situations_discuss') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-7">
                <form id="form-search" class="mb-3">
                    <div class="form-row align-items-center">
                        <div class="mr-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="Nhập Tên/Mã">
                        </div>
                        <div class="mr-1">
                            <input name="time_created" type="text" class="datepicker form-control" placeholder="Ngày tạo" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-5 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('situation-create')
                            <a style="cursor: pointer;" onclick="createSituation()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{trans('backend.add_new')}}</a>
                        @endcan
                        @can('situation-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="5%" data-checkbox="true"></th>
                    <th data-field="name" data-width="25%" data-formatter="name_formatter">Tên Thảo luận tình huống</th>
                    <th data-field="code" data-width="15%">Mã Thảo luận tình huống</th>
                    <th data-field="created_at" data-width="15%">Thời gian tạo</th>
                    <th data-field="image" data-align="center" data-formatter="comment_formatter" data-width="10%">Bình luận</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-situation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post" class="form-ajax" id="form_save_situation">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa Thảo luận tình huống</h5> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @can('situation-create')
                        <div class="btn-group act-btns">
                            <button type="button" onclick="saveEdit(event)" class="btn btn-primary save">{{ trans('lacore.save ') }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                        @endcan
                    </div>
                    <div class="modal-body" id="body_modal">
                        
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function comment_formatter(value, row, index) {
            return '<a href="' + row.commentSituation + '"><i class="fas fa-edit"></i></a>'
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.get.situations',['id' => $topic_id]) }}',
            remove_url: '{{ route('module.remove.situations',['id' => $topic_id]) }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            
            $.ajax({
                url: "{{ route('module.ajax.edit.situations') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#body_modal').html(`<input type="hidden" name="type" value="1">
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="name_situations">Tên Thảo luận tình huống</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input name="name_situations" type="text" class="form-control" value="`+ data.name +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="code_situations">Mã Thảo luận tình huống</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input name="code_situations" type="text" class="form-control" value="`+ data.code +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="description_situations">Mô tả</label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea id="content" name="description_situations" class="form-control" placeholder="Mô tả">`+ data.description +`</textarea>
                                            </div>
                                        </div>`)
                $('#modal-situation').modal();
                CKEDITOR.replace('content', {
                    filebrowserImageBrowseUrl: '/filemanager?type=image',
                    filebrowserBrowseUrl: '/filemanager?type=file',
                    filebrowserUploadUrl : null, //disable upload tab
                    filebrowserImageUploadUrl : null, //disable upload tab
                    filebrowserFlashUploadUrl : null, //disable upload tab
                });
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function saveEdit(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var form = $('#form_save_situation');
            var description_situations = CKEDITOR.instances['content'].getData();
            var name_situations =  $("input[name=name_situations]").val();
            var code_situations =  $("input[name=code_situations]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.save.situations', ['id' => $topic_id]) }}",
                type: 'post',
                data: {
                    'description_situations': description_situations,
                    'name_situations': name_situations,
                    'code_situations': code_situations,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                $('#modal-situation').modal('hide');
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function createSituation() {
            $('#body_modal').html(`<div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="name_situations">Tên Thảo luận tình huống</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="name_situations" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="code_situations">Mã Thảo luận tình huống</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="code_situations" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="description_situations">Mô tả</label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea id="content" name="description_situations" class="form-control" placeholder="Mô tả"></textarea>
                                        </div>
                                    </div>`)
            CKEDITOR.replace('content', {
                filebrowserImageBrowseUrl: '/filemanager?type=image',
                filebrowserBrowseUrl: '/filemanager?type=file',
                filebrowserUploadUrl : null, //disable upload tab
                filebrowserImageUploadUrl : null, //disable upload tab
                filebrowserFlashUploadUrl : null, //disable upload tab
            });
            $('#modal-situation').modal();
        }
    </script>
@endsection
