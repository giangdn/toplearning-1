@extends('layouts.backend')

@section('page_title', 'Mẫu chứng chỉ')

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
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_code_name')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('certificate-template-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
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
                    <th data-field="code" data-width="10%" data-align="center">{{trans('backend.certificate_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.certificate_name')}}</th>
                    <th data-field="image" data-formatter="image_formatter" data-align="center">{{trans('backend.certificate')}}</th>
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
                            @canany(['certificate-template-create', 'certificate-template-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save ') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="code">{{trans('backend.certificate_code')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="code" type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{trans('backend.certificate_name')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.picture')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5">
                                <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                <div id="image-review">
                                </div>
                                <input name="image" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function image_formatter(value, row, index) {
            return '<img src="'+ row.image +'" class="img-responsive" width="100" height="100">';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.certificate.getdata') }}',
            locale: '{{ \App::getLocale() }}',
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('module.certificate.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('Chỉnh sửa ' + data.model.name);
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("input[name=image]").val(data.model.image);
                $("#image-review").html('<img class="w-100" src="'+ data.image +'" alt="">');
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var image = $("input[name=image]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.certificate.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'image': image,
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
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=image]").val('');
            $('#exampleModalLabel').html('Thêm mới');
            $('#modal-popup').modal();
            $("#image-review").html('<img src="" alt="">');
        }

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img class="w-100" src="'+ path +'">');
                $("#image-select").val(path);
            });
        });
    </script>
@endsection
