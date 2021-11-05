@extends('layouts.backend')

@section('page_title', trans('backend.teacher'))

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
            <span class="">{{ trans('backend.teacher') }}</span>
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
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("backend.enter_teacher_name")}}'>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    @can('category-teacher-create')
                    <div class="btn-group">
                        <a href="{{ download_template('mau_import_giang_vien_noi_bo.xlsx') }}" class="btn btn-info"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i> Import</a>
                        <a class="btn btn-info" href="{{ route('backend.category.training_teacher.export') }}"><i class="fa fa-download"></i> Export</a>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('category-teacher-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </button>
                        @endcan
                        @can('category-teacher-delete')
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
                    <th data-field="code">Mã giảng viên</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.teacher_name') }}</th>
                    <th data-field="email">{{ trans('backend.teacher_email') }}</th>
                    <th data-sortable="true" data-width="15px" data-field="phone">{{ trans('backend.teacher_phone') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="15%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('backend.category.training_teacher.import') }}" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="btn-group act-btns">
                            @canany(['category-teacher-create', 'category-teacher-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div id="base" class="tab-pane active">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label for="type">{{trans('backend.form')}} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="type" id="type" class="form-control" required data-placeholder="-- {{trans('backend.choose_form')}} --">
                                                <option value="1">{{trans("backend.internal")}}</option>
                                                <option value="2">{{trans("backend.outside")}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" id="form-internal">
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.choose_user') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="user_id" id="user_id" class="form-control select2 ">
                                                <option value="" disabled selected>--{{ trans('backend.choose_user') }}--</option>
                                                @foreach($get_users_not_regis as $user_not_regis)
                                                    <option value="{{ $user_not_regis->user_id }}">
                                                        {{ $user_not_regis->code . ' - ' . $user_not_regis->lastname . ' ' . $user_not_regis->firstname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.teacher_code') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="code" id="code" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.teacher_name') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="name" id="name" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>Email <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="email" id="email" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.teacher_phone') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="phone" id="phone" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>Số tài khoản</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="account_number" id="account_number" type="text" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.unit') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input id="unit" type="text" class="form-control" value="{{ isset($unit) ? $unit->code . ' ' . $unit->name : ''
                                             }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.title') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input id="title" type="text" class="form-control" value="{{ isset($title) ? $title->code . ' ' . $title->name :
                                           ''}}" disabled>
                                        </div>
                                    </div>
        
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label for="teacher_type_id">{{ trans('backend.teacher_type') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="teacher_type_id" id="teacher_type_id" class="form-control select2" data-placeholder="-- {{ trans('backend.teacher_type') }} --" >
                                                <option value=""></option>
                                                @foreach($teacher_types as $teacher_type)
                                                    <option value="{{ $teacher_type->id }}">{{ $teacher_type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 control-label">
                                            <label for="training_partner_id">Đối tác </label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="training_partner_id" id="training_partner" class="form-control select2" data-placeholder="-- Chọn đối tác --" >
                                                <option value=""></option>
                                                @foreach($training_partner as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
        
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('backend.status') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="radio-inline"><input id="enable" type="radio" required name="status" value="1" checked>Đang làm việc</label>
                                            <label class="radio-inline"><input id="disable" type="radio" required name="status" value="0">Nghỉ việc</label>
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                        </div>                
                    </div>
                </form>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        var ajax_get_user = "{{ route('backend.category.ajax_get_user') }}";
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ value +'</a>' ;
        }
        function status_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">Đang làm việc</span>' : '<span style="color: red;">Nghỉ việc</span>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_teacher.getdata') }}',
            remove_url: '{{ route('backend.category.training_teacher.remove') }}'
        });

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_teacher.save') }}",
                type: 'post',
                data: $("#form_save").serialize(),
                
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#myModal2').modal('hide');
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

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('backend.category.training_teacher.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("input[name=email]").val(data.model.email);
                $("input[name=phone]").val(data.model.phone);
                $("input[name=account_number]").val(data.model.account_number);

                $('#type').attr("disabled", true); 

                $("#unit").val('');
                $("#title").val('');
                if (data.unit) {
                    $("#unit").val(data.unit.code + ' ' + data.unit.name);
                }
                if (data.title) {
                    $("#title").val(data.title.code + ' ' + data.title.name);
                }
                
                $("#teacher_type_id").val(data.model.teacher_type_id);
                $("#teacher_type_id").val(data.model.teacher_type_id).change();

                $("#training_partner").val(data.model.training_partner_id);
                $("#training_partner").val(data.model.training_partner_id).change();

                if (data.model.type == 1) {
                    $("#type").html('<option value="1" selected>{{trans("backend.internal")}}</option>');
                    $('#form-internal').show();
                    $('#user_id').attr("disabled", false); 
                } else {
                    $("#type").html('<option value="2" selected>{{trans("backend.outside")}}</option>');
                    $('#form-internal').hide();
                    $('#user_id').attr("disabled", true); 
                }

                if (data.user) {
                    $("#user_id").val(data.user.user_id);
                    $("#user_id").val(data.user.user_id).change();
                }

                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
                }

                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#form_save').trigger("reset");
            $("input[name=id]").val('');
            $('#type').attr("disabled", false); 
            $("#teacher_type_id").val('').trigger('change');
            $("#training_partner").val('').trigger('change');
            $("#user_id").val('').trigger('change');
            $("#unit").val('');
            $("#title").val('');
            $("#type").html(`<option value="1" selected>{{trans("backend.internal")}}</option>
                             <option value="2">{{trans("backend.outside")}}</option>`);
            $('#form-internal').show();
            $('#user_id').attr("disabled", false); 
            $('#myModal2').modal();
        }
    </script>
    <script src="{{ asset('styles/module/training_teacher/js/training_teacher.js') }}"></script>
@endsection
