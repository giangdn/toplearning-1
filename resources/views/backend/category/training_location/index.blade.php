@extends('layouts.backend')

@section('page_title', trans('backend.training_location'))

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
            <span class="">{{ trans('backend.training_location') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control w-25" placeholder="Nhập mã tên điểm đào tạo">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('category-training-location-edit')
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
                        @can('category-training-location-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('category-training-location-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.training_location_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.training_location_name') }}</th>
                    <th data-sortable="true" data-field="province" data-width="20%">{{ trans('backend.province') }}</th>
                    <th data-sortable="true" data-field="district" data-width="20%">{{ trans('backend.district') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
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
                            @canany(['category-training-location-create', 'category-training-location-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 pr-0 control-label">
                                <label>{{ trans('backend.training_location_code') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 pr-0 control-label">
                                <label>{{ trans('backend.training_location_name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 pr-0 control-label">
                                <label>{{ trans('backend.province') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7" id="province">
                                <select name="province_id" id="province_id" class="form-control select2" data-url="{{route('backend.category.district.filter')}}">
                                    <option value="">{{ trans('backend.choose_province') }}</option>
                                    @foreach($province as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 pr-0 control-label">
                                <label>{{ trans('backend.district') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="district_id" id="district_id" data-placeholder="{{trans('backend.choose_district')}}" class="form-control select2">
                                    <option value="">{{ trans('backend.choose_district') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 pr-0 control-label">
                                <label>{{trans('backend.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <label class="radio-inline"><input id="enable" class="status" type="radio" required name="status" value="1" checked>{{ trans('backend.enable') }}</label>
                                <label class="radio-inline"><input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('backend.disable') }}</label>
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
            url: '{{ route('backend.category.training_location.getdata') }}',
            remove_url: '{{ route('backend.category.training_location.remove') }}'
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
                url: "{{ route('backend.category.training_location.ajax_isopen_publish') }}",
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
                url: "{{ route('backend.category.training_location.edit') }}",
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
                $("#province select").val(data.model.province_id);
                $("#province select").val(data.model.province_id).change();

                var district = '';
                $.each(data.districts, function (i, item){
                    if(item.id == data.model.district_id) {
                        district += `<option value="`+ item.id +`" selected>`+ item.name +`</option>`;
                    } else {
                        district += `<option value="`+ item.id +`" >`+ item.name +`</option>`;
                    }
                });
                $('#district_id').html(district);
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
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var status = $('.status:checked').val();
            var province_id = $('#province_id').val();
            var district_id = $('#district_id').val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_location.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'status': status,
                    'district_id': district_id,
                    'province_id': province_id,
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
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $('#exampleModalLabel').html('Thêm địa điểm đào tạo');
            var province = `<option value="">{{ trans('backend.choose_province') }}</option>
                                @foreach($province as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach`
            $('#province_id').html(province);
            $('#district_id').html('<option value="">{{ trans('backend.choose_district') }}</option>');
            $('#modal-popup').modal();
        }

        $('#province_id').on('change',function (e) {
            var province_id = $('#province_id').val();
            console.log(province_id);
            $.ajax({
                url: "{{ route('backend.category.district.filter') }}",
                type: 'get',
                data: {
                    province_id: province_id,
                }
            }).done(function(result) {
                if (result && result.length) {
                    let html = '';
                    $.each(result, function (i, item){
                        html+='<option value='+ item.id +'>'+ item.name +'</option>';
                    });
                    $('#district_id').html(html);
                } else {
                    $('#district_id').html('<option></option>')
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>
@endsection
