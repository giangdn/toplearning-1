@extends('layouts.backend')

@section('page_title', trans('backend.unit_type'))

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
            <span class="">{{ trans('backend.unit_type') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_name')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        {{-- @can('category-unit-type-create')
                        <a href="{{ route('backend.category.unit_type.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
                        @endcan
                        @can('category-unit-type-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan --}}
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    
                    <th data-width="20%px" data-sortable="true" data-field="name" data-formatter="name_formatter">{{ @trans('backend.unit_type') }}</th>
                    <th data-field="unit_type_code">Mã đơn vị</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-type-create', 'category-unit-type-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.unit_type') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name" type="text" class="form-control" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>Mã đơn vị<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="code" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>Mã đơn vị</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="all_unit_type_code">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            // return '<a href="'+ row.edit_url +'">'+ value +'</a>';
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.unit_type.getdata') }}',
            remove_url: '{{ route('backend.category.unit_type.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('backend.category.unit_type.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                console.log(data.html);
                $("input[name=code]").val('');
                $('#exampleModalLabel').html('Chỉnh sửa ' + data.name);
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);
                $(".all_unit_type_code").html('');
                $(".all_unit_type_code").append(data.html);
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                item.html(oldtext);
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.unit_type.save') }}",
                type: 'post',
                data: {
                    'code': code,
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

        function deleteUnitCode(id) {
        $.ajax({
            url: '{{ route('backend.category.unit_type.remove') }}',
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            window.location = '';
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    }
    </script>
@endsection
