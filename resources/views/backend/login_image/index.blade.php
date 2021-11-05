@extends('layouts.backend')

@section('page_title', 'Quản lý hình nền đăng nhập')
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/logo/css/logo.css') }}">
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.login_wallpaper')}}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="5%" data-checkbox="true"></th>
                    <th data-field="image" data-formatter="image_formatter" data-width="50%">{{trans('backend.picture')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                    <th data-field="status" data-formatter="status_formatter" data-width="5%" data-align="center">{{trans('backend.status')}}</th>
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
                                <label for="image">{{trans('backend.picture')}} <span class="text-danger">*</span> <br>({{trans('backend.size')}}: 2000x1000)</label>
                            </div>
                            <div class="col-sm-5">
                                <a href="javascript:void(0)" id="select-image-web">{{trans('backend.choose_picture')}}</a>
                                <div id="image-review-web">
                                    
                                </div>
                                <input type="hidden" class="form-control" name="image" id="image-select-web" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input id="enable" class="status" type="radio" required name="status" value="1">{{ trans('backend.enable') }}
                                </label>
                                <label class="radio-inline">
                                    <input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('backend.disable') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="save(event)" class="btn btn-primary">{{ trans('lacore.save') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
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

        function image_formatter(value, row, index) {
            return '<a style="cursor: pointer;" onclick="edit('+ row.id +')"><img src="'+ row.image_url +'" class="w-50"></a>' ;
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
            url: '{{ route('backend.login_image.getdata') }}',
            remove_url: '{{ route('backend.login_image.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('backend.login_image.ajax_isopen_publish') }}",
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
            $.ajax({
                url: "{{ route('backend.login_image.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                $('#exampleModalLabel').html('Chỉnh sửa');
                $("input[name=id]").val(data.model.id);
                $("input[name=image]").val(data.model.image);
                $("#image-review-web").html('<img class="w-100" src="'+ data.image +'" alt="">');
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
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            var form = $('#form_save');
            var id =  $("input[name=id]").val();
            var status = $('.status:checked').val();
            var image = $("input[name=image]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.login_image.save') }}",
                type: 'post',
                data: {
                    'id': id,
                    'status': status,
                    'image' : image
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
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=id]").val('');
            $("input[name=image]").val('');
            $('#exampleModalLabel').html('Thêm mới');
            $('#modal-popup').modal();
            $("#image-review-web").html('<img src="" alt="">');
        }

        $("#select-image-web").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review-web").html('<img class="w-100" src="' + path + '" class="w-25">');
                $("#image-select-web").val(path);
            });
        });
    </script>
@endsection

