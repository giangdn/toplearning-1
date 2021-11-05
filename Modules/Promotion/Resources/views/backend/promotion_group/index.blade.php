@extends('layouts.backend')

@section('page_title', 'Nhóm danh mục quà tặng')

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
                    <input type="text" name="search" value="" class="form-control w-30" placeholder="{{ trans('backend.promotion_category_group') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('promotion-group-create')
                        <div class="btn-group"> 
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('promotion-group-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('promotion-group-delete')
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
                <th data-field="code" data-align="center">{{trans('backend.group_code')}}</th>
                <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{trans('backend.group_name')}}</th>
                <th data-field="created_at" data-align="center">{{trans('backend.created_at')}}</th>
                <th data-field="updated_at" data-align="center">{{trans('backend.date_updated')}}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.status')}}</th>
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
                            @can('promotion-group-create')
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save ') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="icon">Icon (300x300) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div>
                                    <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                    <div id="image-review"></div>
                                    <input name="image" id="image-select" type="text" class="d-none" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="code">{{trans('backend.promotion_category_group_code')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control"  value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="name">{{trans('backend.promotion_category_group_name')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control"  value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('backend.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <label class="radio-inline">
                                    <input id="enable" class="status" type="radio" required name="status" value="1" checked>{{ trans('backend.enable') }}
                                </label>
                                <label class="radio-inline">
                                    <input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('backend.disable') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function image_formatter(value,row,index) {
            return '<img src="'+row.images+'" width="200px" height="150px">'
        }
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.promotion.group.getdata') }}',
            remove_url: '{{ route('module.promotion.group.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('module.promotion.group.ajax_is_open') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('module.promotion.group.edit') }}",
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
                $("input[name=image]").val(data.model.icon);
                $("#image-review").html('<img class="w-50" src="'+ data.image +'" alt="">');
                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
                }
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
            var status = $('.status:checked').val();
            var image = $("input[name=image]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.promotion.group.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'status': status,
                    'icon' : image
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
                $("#image-review").html('<img src="'+ path +'" class="w-50">');
                $("#image-select").val(path);
            });
        });
    </script>
@endsection
