@extends('layouts.backend')

@section('page_title', trans('backend.training_program'))

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
            <span class="">{{ trans('backend.training_program') }}</span>
        </h2>
    </div>
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control w-25" placeholder="{{trans('backend.topic_training_program_search')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-training-program-create')
                            <div class="btn-group">
                                <a class="btn btn-info" href="{{ download_template('mau_import_chuong_trinh_dao_tao.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                    
                                <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                    <i class="fa fa-upload"></i> Import
                                </button>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-info" href="{{ route('backend.category.training_program.export') }}">
                                    <i class="fa fa-download"></i> Export
                                </a>
                            </div>
                        @endcan
                        @can('category-training-program-edit')
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        @endcan
                        @can('category-training-program-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('category-training-program-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.topic_training_program_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.topic_training_program') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('backend.category.training_program.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT Chương trình đào tạo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
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
                            @canany(['category-training-program-create', 'category-training-program-edit'])
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
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
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
            url: '{{ route('backend.category.training_program.getdata') }}',
            remove_url: '{{ route('backend.category.training_program.remove') }}'
        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
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
                url: "{{ route('backend.category.training_program.ajax_isopen_publish') }}",
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
                url: "{{ route('backend.category.training_program.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('Chỉnh sửa ' + data.name);
                $('#body_modal').html(`<input type="hidden" name="id" value="`+ data.id +`">
                                        <div class="form-group row">
                                            <div class="col-sm-4 control-label">
                                                <label for="code">Mã chương trình đào tạo</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="code" type="text" class="form-control" value="`+ data.code +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4 control-label">
                                                <label for="name">Tên chương trình đào tạo</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="name" type="text" class="form-control" value="`+ data.name +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4 control-label">
                                                <label>{{ trans('backend.status') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="radio-inline">
                                                    <input id="enable" class="status" required type="radio" name="status" value="1">{{ trans('backend.enable') }}
                                                </label>
                                                <label class="radio-inline">
                                                    <input id="disable" class="status" required type="radio" name="status" value="0">{{ trans('backend.disable') }}
                                                </label>
                                            </div>
                                        </div>`)
                $(".status").attr('checked', false); 
                if (data.status == 1) {
                    $('#enable').attr( 'checked', true )
                } else {
                    $('#disable').attr( 'checked', true )
                }
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
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var status = $('.status:checked').val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_program.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'status': status,
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
            $('#exampleModalLabel').html('Thêm chương trình đào tạo');
            $('#body_modal').html(`<input name="id" type="hidden" class="form-control" value="">
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label for="code">Mã chương trình đào tạo</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="code" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label for="name">Tên chương trình đào tạo</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="name" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>{{ trans('backend.status') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="radio-inline"><input required type="radio" class="status" name="status" value="1" checked>{{ trans('backend.enable') }}</label>
                                            <label class="radio-inline"><input required type="radio" class="status" name="status" value="0">{{ trans('backend.disable') }}</label>
                                        </div>
                                    </div>`)
            $('#modal-popup').modal();
        }
    </script>
@endsection
