@extends('layouts.backend')

@section('page_title', trans('backend.faq'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_question')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('FAQ-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('FAQ-delete')
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
                    <th data-field="check" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.question') }}</th>
                    <th data-field="content">{{ trans('backend.content') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div class="modal fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="title">{{ trans('backend.enter_question') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="content">{{ trans('backend.enter_content') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <textarea name="content" id="content" placeholder="{{ trans('backend.content') }}" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @canany(['FAQ-create','FAQ-edit'])
                            <button type="button" onclick="save(event)" class="btn btn-primary">{{ trans('lacore.save ') }}</button>
                        @endcan
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/admin/filemanager?type=Image',
            filebrowserBrowseUrl: '/admin/filemanager?type=Files',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
        
        function name_formatter(value, row, index) {
            return '<a style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.faq.getdata') }}',
            remove_url: '{{ route('module.faq.remove') }}'
        });

        function edit(id){
            $.ajax({
                url: "{{ route('module.faq.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                $('#exampleModalLabel').html('Chỉnh sửa ' + data.name);
                $("input[name=id]").val(data.id);
                CKEDITOR.instances.content.setData(data.content);
                $("input[name=name]").val(data.name);
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function save(event) {
            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var content =  CKEDITOR.instances['content'].getData();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.faq.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'content': content,
                    'id': id,
                }
            }).done(function(data) {
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function create() {
            CKEDITOR.instances.content.setData('');
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("#content").val('');
            $('#exampleModalLabel').html('Thêm câu hỏi');
            $('#modal-popup').modal();
        }
    </script>
@endsection
