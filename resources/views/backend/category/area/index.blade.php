@extends('layouts.backend')

@section('page_title', data_locale($name->name, $name->name_en))

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
            <span class="">{{ data_locale($name->name, $name->name_en) }}</span>
        </h2>
    </div>
    <div role="main">
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
                @php
                $notification->markAsRead();
                @endphp
            @endforeach
        @endif
        
        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_code_name') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    @if($level == 1)
                        @can('category-area-create')
                            <div class="btn-group">
                                <a class="btn btn-info" href="{{ download_template('mau_import_khu_vuc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                                <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                    <i class="fa fa-upload"></i> Import
                                </button>
                            </div>
                        @endcan
                    @endif
                    <div class="btn-group">
                        @can('category-area-edit')
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('category-area-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('category-area-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.name') }}</th>
                    @if($level != 1)
                        <th data-sortable="true" data-field="parent_name" data-width="20%">{{ trans('backend.management_locations') }}</th>
                    @endif
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('backend.category.area.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT Khu vực</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="area_id" value="{{ $level }}">
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="btn-group act-btns">
                            @canany(['category-area-create', 'category-area-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="level" value="{{ $level }}">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>Mã {{ data_locale($name->name, $name->name_en) }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>Tên {{ data_locale($name->name, $name->name_en) }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                
                        @if($level != 1)
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="parent_id">Địa điểm quản lý</label>
                                </div>
                                <div class="col-md-6" id="parent">
                                    <select name="parent_id" id="parent_id" class="form-control select2" data-placeholder="-- Cấp cha --">
                                        <option value=""></option>
                                        @foreach($parent_area as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                
                        @if($level == 2)
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_id">{{trans('backend.department')}}</label>
                                </div>
                                <div class="col-md-6" id="unit">
                                    <select name="unit_id" id="unit_id" class="form-control select2" data-placeholder="-- {{trans('backend.department')}} --">
                                        <option value=""></option>
                                        @foreach($units3 as $unit3)
                                            <option value="{{ $unit3->id }}">{{ $unit3->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

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
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
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
            url: '{{ route('backend.category.area.getdata', ['level' => $level]) }}',
            remove_url: '{{ route('backend.category.area.remove', ['level' => $level]) }}',
            sort_name: 'a.id',
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
                url: "{{ route('backend.category.area.ajax_isopen_publish') }}",
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
            var level =  $("input[name=level]").val();
            $.ajax({
                url: "{{ route('backend.category.area.edit',['level' => $level]) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                if (data.parent) {
                    $("#parent select").val(data.parent.id);
                    $("#parent select").val(data.parent.id).change();
                }
                if (level == 2) {
                    console.log(data.model.unit_id);
                    $("#unit select").val(data.model.unit_id);
                    $("#unit select").val(data.model.unit_id).change();
                }
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
            var level =  $("input[name=level]").val();
            var parent_id = $('#parent_id').val() ? $('#parent_id').val() : '';
            var unit_id = $('#unit_id').val() ? $('#unit_id').val() : '';
            var status = $('.status:checked').val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.area.save',['level' => $level]) }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'level': level,
                    'parent_id' : parent_id,
                    'unit_id' : unit_id,
                    'status' : status
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
            $('#modal-popup').modal();
            $("#parent_id").val('').trigger('change')
        }
    </script>
@endsection
