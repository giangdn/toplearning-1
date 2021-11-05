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
        <!-- @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif -->
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
        @php
            $max_level = \App\Models\Categories\Unit::getMaxUnitLevel();
            $check_level = $level < 15 ? $level + 1 : $level;
        @endphp
        <div class="row">
            <div class="col-md-12 form-inline m-2">
                <form class="form-inline w-100" id="form-search">
                    @for($i = 1; $i <= $check_level; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ ($i+1) }}">
                            </select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="{{ trans('backend.enter_code_name') }}">
                    </div>
                    <div class="w-25">
                        <input type="text" name="user_code" value="" class="form-control w-100" placeholder="{{ trans('backend.enter_unit_manager_code') }}">
                    </div>
                    <div class="w-25">
                        <select name="unit_type" id="" class="form-control select2 w-100" data-placeholder="--Chọn loại đơn vị--">
                            <option value=""></option>
                            <option value="1">Hội sở</option>
                            <option value="2">Đơn vị kinh doanh</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns mt-2">
                <div class="pull-right">
                    @if($level == 1)
                        @can('category-unit-create')
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Export Full
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="btn btn-info w-100" href="{{ route('backend.category.unit.export', ['level' => 0]) }}"><i class="fa fa-download"></i> Export Full</a>
                                        <button class="btn btn-info" id="import-plan-update" type="submit" name="task" value="import">
                                            <i class="fa fa-upload"></i> Cập nhật import theo excel
                                        </button>
                                    </div>
                                  </div>
                            </div>
                        @endcan
                        <div class="btn-group">
                            <a href="{{ route('backend.category.unit.tree_folder') }}" class="btn btn-info"> {{ trans('backend.folder_tree') }}</a>
                        </div>
                        @can('category-unit-create')
                            <div class="btn-group">
                                <a class="btn btn-info" href="{{ download_template('mau_import_don_vi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                                <button class="btn btn-info" id="import-plan" type="button">
                                    <i class="fa fa-upload"></i> Import
                                </button>
                            </div>
                        @endcan
                    @endif
                    <div class="btn-group">
                        @can('category-unit-create')
                            <div class="btn-group">
                                <a class="btn btn-info" href="{{ route('backend.category.unit.export', ['level' => $level]) }}"><i class="fa fa-download"></i> Export</a>
                            </div>
                        @endcan
                        @can('category-unit-edit')
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        @endcan
                        @can('category-unit-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </button>
                        @endcan
                        @can('category-unit-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.unit_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.unit') }}</th>
                    @if($level != 1)
                        <th data-sortable="true" data-field="parent_name" data-width="20%">{{ trans('backend.management_unit') }}</th>
                    @endif
                    <th data-field="type_name">{{ trans('backend.unit_type') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.manager') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">Tên cấp bậc chức danh</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('backend.category.unit.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.unit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="unit_id" value="{{ $level }}">
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

        <div class="modal fade" id="modal-import-update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelUpdate" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('backend.category.unit.import_update') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelUpdate">IMPORT UPDATE {{ trans('backend.unit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="unit_id" value="{{ $level }}">
                            <input type="file" name="import_file_update" id="import_file_update" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="{{ route('backend.category.unit.save', ['level' => $level]) }}" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save">
                    <input type="hidden" name="level" value="{{ $level }}">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-create', 'category-unit-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="tPanel">
                            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                                <li class="nav-item">
                                    <a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#object" class="nav-link" data-toggle="tab">{{ trans('backend.management') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="base" class="tab-pane active">
                                    @include('backend.category.unit.form.info')
                                </div>
                
                                <div id="object" class="tab-pane">
                                    @include('backend.category.unit.form.manager')
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
            url: '{{ route('backend.category.unit.getdata', ['level' => $level]) }}',
            remove_url: '{{ route('backend.category.unit.remove', ['level' => $level]) }}',

        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#import-plan-update').on('click', function() {
            $('#modal-import-update').modal();
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
                url: "{{ route('backend.category.unit.ajax_isopen_publish') }}",
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
            $.ajax({
                url: "{{ route('backend.category.unit.save',['level' => $level]) }}",
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
            var level =  $("input[name=level]").val();
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('backend.category.unit.edit',['level' => $level]) }}",
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
                $("#note1").val(data.model.note1);
                $("#note2").val(data.model.note2);

                $("#type_unit select").val(data.model.type);
                $("#type_unit select").val(data.model.type).change();

                if (data.parent) {
                    $("#parent_id").html('<option value="'+ data.parent.id +'">'+ data.parent.code +' - '+ data.parent.name +'</option>');
                }
                $("#manager").html('');
                if (data.unit_managers) {
                    $.each(data.unit_managers, function (index, value) { 
                        $("#manager").append('<option value="'+ value.user_id +'" selected>'+ value.user_code + ' - ' +  value.user_lastname + ' ' + value.user_firstname +'</option>');
                    });
                }
                
                for (var i = 1; i <= data.max_area; i++) {
                    $("#area_id_"+i).html('');
                }
                $.each(data.area, function (index, value) { 
                    $("#area_id_"+index).html('<option value="'+ value.id +'">'+ value.name +'</option>');
                });

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
            var max_area = '{{ $max_area }}';
            $('#form_save').trigger("reset");
            $("#type").val('').trigger('change');
            $("#parent_id").val('').trigger('change');
            $("input[name=id]").val('');
            $("#manager").html('');
            $('#myModal2').modal();
            for (var i = 1; i <= max_area; i++) {
                $("#area_id_"+i).html('');
            }
        }
    </script>

@endsection
