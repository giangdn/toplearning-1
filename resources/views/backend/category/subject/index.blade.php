@extends('layouts.backend')

@section('page_title', trans('backend.subject'))

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
            <span class="">{{ trans('backend.subject') }}</span>
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
            <div class="col-md-12">
                <form class="form-inline form-search mb-3 w-100" id="form-search">
                    <div class="w-25">
                        <select name="training_program_id" id="training_program" class="form-control select2 load-training-program w-100" data-placeholder="{{ trans('backend.training_program') }}">
                        </select>
                    </div>
                    <div class="w-25">
                        <select name="level_subject_id" id="level_subject" class="form-control select2 load-level-subject w-100" data-placeholder="{{ trans('backend.type_subject') }}">
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="{{trans('backend.subject_search')}}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('category-subject-create')
                        <div class="btn-group">
                            <a href="{{ download_template('mau_import_hoc_phan.xlsx') }}" class="btn btn-info"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                            <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i> Import</a>
                            <a class="btn btn-info" href="{{ route('backend.category.subject.export') }}">
                                <i class="fa fa-download"></i> Export
                            </a>
                        </div>
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
                        @can('category-subject-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </button>
                        @endcan
                        @can('category-subject-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.subject_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.subject_name') }}</th>
                    <th data-sortable="true" data-field="level_subject_name" data-width="20%">{{ trans('backend.type_subject') }}</th>
                    <th data-sortable="true" data-field="parent_name" data-width="20%">{{ trans('backend.topic_training_program') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('backend.category.subject.import') }}" class="form-ajax">
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

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="{{ route('backend.category.subject.save') }}" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="btn-group act-btns">
                            @canany(['category-subject-create', 'category-subject-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('backend.subject_code') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="code" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('backend.subject_name') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="name" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('backend.training_program') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9" id="training_program_id">
                                        <select name="training_program_id" class="form-control load-training-program" data-placeholder="-- {{ trans('backend.training_program') }} --" required>
                                            <option value=""></option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('backend.type_subject') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9" id="level_subject_id">
                                        <select name="level_subject_id" class="form-control select2" data-training-program="" data-placeholder="-- {{ trans('backend.type_subject') }} --" required>
                                            <option value=""></option>
                                            @if(isset($level_subject))
                                                @foreach ($level_subject as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label for="created_date">{{ trans('backend.created_at') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="created_date" class="form-control datepicker" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label for="created_by">{{ trans('backend.person_create') }}</label>
                                    </div>
                                    <div class="col-md-9" id="created_by">
                                        <select name="created_by" class="form-control load-user" data-placeholder="-- {{ trans('backend.person_create') }} --">
                                            <option value=""></option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label for="unit_code">{{ trans('backend.training_create') }}</label>
                                    </div>
                                    <div class="col-md-9" id="unit_id">
                                        <select name="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('backend.training_create') }} --">
                                            <option value=""></option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label for="description">{{ trans('backend.brief') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('backend.description') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="content" id="content" class="form-control ckeditor"></textarea>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('backend.status') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="radio-inline"><input type="radio" class="status" required name="status" value="1" checked>{{ trans('backend.enable') }}</label>
                                        <label class="radio-inline"><input type="radio" class="status" required name="status" value="0">{{ trans('backend.disable') }}</label>
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
            url: '{{ route('backend.category.subject.getdata') }}',
            remove_url: '{{ route('backend.category.subject.remove') }}'
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
                url: "{{ route('backend.category.subject.ajax_isopen_publish') }}",
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

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            event.preventDefault();
            var content =  CKEDITOR.instances['content'].getData();
            var id = $("input[name=id]").val();
            var code = $("input[name=code]").val();
            var name = $("input[name=name]").val();
            var created_date = $("input[name=created_date]").val();
            var description = $("#description").val();
            var level_subject_id = $("#level_subject_id select").val();
            var training_program_id = $("#training_program_id select").val();
            var created_by = $("#created_by select").val();
            var unit_id = $("#unit_id select").val();
            var status = $('.status:checked').val();
            $.ajax({
                url: "{{ route('backend.category.subject.save') }}",
                type: 'post',
                data: {
                    'id' : id,
                    'code' : code,
                    'name' : name,
                    'created_date' : created_date,
                    'description' : description,
                    'level_subject_id' : level_subject_id,
                    'training_program_id' : training_program_id,
                    'created_by' : created_by,
                    'unit_id' : unit_id,
                    'content' : content,
                    'status' : status
                },
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
                url: "{{ route('backend.category.subject.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("input[name=created_date]").val(data.format_date);
                $("#description").val(data.model.description);

                $("#level_subject_id select").val(data.model.level_subject_id);
                $("#level_subject_id select").val(data.model.level_subject_id).change();

                $("#training_program_id select").html('<option value="'+ data.training_programs.id +'" selected>'+ data.training_programs.name +'</option>');
                $("#created_by select").html('<option value="'+data.profile.user_id+'" selected>'+data.profile.code+' - '+data.profile.lastname+' '+data.profile.firstname+'</option>');

                $("#unit_id select").html('');
                if (data.unit) {
                    $("#unit_id select").html('<option value="'+ data.unit.id +'" selected>'+ data.unit.code +' - '+ data.unit.name +'</option>');
                }

                CKEDITOR.instances.content.setData(data.model.content);

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
            CKEDITOR.instances.content.setData('');
            $('#form_save').trigger("reset");
            $('#myModal2').modal();
            $("input[name=code]").val('');
            $("input[name=name]").val('');
            $("input[name=created_date]").val('');
            $("#description").val('');
            $("#level_subject_id select").val('').trigger('change');
            $("#training_program_id select").val('').trigger('change');
            $("#created_by select").val('').trigger('change');
            $("#unit_id select").val('').trigger('change');
        }

        $('#training_program_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
        });
    </script>
@endsection
