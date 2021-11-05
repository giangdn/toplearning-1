@extends('layouts.backend')

@section('page_title', trans('backend.title'))

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
            <span class="">{{ trans('backend.title') }}</span>
        </h2>
    </div>
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif
            @php
                $max_level = \App\Models\Categories\Unit::getMaxUnitLevel();
            @endphp
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search-user mb-3 w-100" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ ($i+1) }}">
                            </select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="group" id="group" class="form-control select2" data-placeholder="-- {{ trans('backend.title_group') }} --">
                            <option value=""></option>
                            <option value="CH">{{ trans('backend.store') }}</option>
                            <option value="CNT">{{ trans('backend.branch') }}</option>
                            <option value="VP">{{ trans('backend.office') }}</option>
                            <option value="NM">{{ trans('backend.subsidiaries_factories') }}</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('backend.code_name_title') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('category-titles-create')
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_chuc_danh.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="{{ route('backend.category.titles.export') }}"><i class="fa fa-download"></i> Export</a>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('category-titles-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </button>
                        @endcan
                        @can('category-titles-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.title_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.title_name') }}</th>
                    <th data-field="title_rank_name" >Cấp bậc</th>
                    <th data-field="unit_type_name" >Loại đơn vị</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('lageneral.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('lageneral.editor') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('backend.category.titles.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.title') }}</h5>
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
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form id="form_save" method="post" action="{{ route('backend.category.titles.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-create', 'category-unit-edit'])
                                <button type="button" id="btn_save" onclick="save(event)" class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('backend.title_code') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="code" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('backend.title_name') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="name" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label for="group">Cấp bậc chức danh <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7" id="group_modal">
                                        <select name="group" class="form-control select2" data-placeholder="--Chọn cấp bậc chức danh--" required>
                                            <option value=""></option>
                                            @foreach ($title_ranks as $title_rank)
                                                <option value="{{ $title_rank->id }}"> {{ $title_rank->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label for="unit_type">Loại đơn vị <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7" id="unit_type">
                                        <select name="unit_type" class="form-control select2" data-placeholder="--Chọn cấp bậc chức danh--" required>
                                            <option value=""></option>
                                            @foreach ($units_type as $unit_type)
                                                <option value="{{ $unit_type->id }}"> {{ $unit_type->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label for="group">{{ trans('backend.unit_level', ['level' => $i]) }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <select name="unit_id" id="unit-modal-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-modal-{{ ($i+1) }}">
                                            </select>
                                        </div>
                                    </div>
                                @endfor
    
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('backend.status') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="radio-inline">
                                            <input id="enable" required type="radio" name="status" value="1" checked>{{ trans('backend.enable') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input id="disable" required type="radio" name="status" value="0" >{{ trans('backend.disable') }}
                                        </label>
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
            return '<a class="edit" id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
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
            url: '{{ route('backend.category.titles.getdata') }}',
            remove_url: '{{ route('backend.category.titles.remove') }}',
            sort_name: 'id'
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
                url: "{{ route('backend.category.title.ajax_isopen_publish') }}",
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
            document.querySelector('.edit').style.pointerEvents = 'none';
            var level =  $("input[name=level]").val();
            $.ajax({
                url: "{{ route('backend.category.titles.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                document.querySelector('.edit').style.pointerEvents = 'auto';
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);

                $("#group_modal select").val(data.model.group);
                $("#group_modal select").val(data.model.group).change();

                $("#unit_type select").val(data.model.unit_type);
                $("#unit_type select").val(data.model.unit_type).change();
                
                for (var i = 1; i <= 5; i++) {
                    $("#unit-modal-"+i).html('');
                }
                if (data.unit) {
                    $.each(data.unit, function (index, value) { 
                        $("#unit-modal-"+index).html('<option value="'+ value.id +'">'+ value.name +'</option>');
                    }); 
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

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.titles.save') }}",
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

        function create() {
            $('#form_save').trigger("reset");
            $("#group_modal select").val('').trigger('change');
            $("#unit_type select").val('').trigger('change');
            $("input[name=id]").val('');
            $('#myModal2').modal();
            for (var i = 1; i <= 5; i++) {
                $("#unit-modal-"+i).html('');
            }
        }
    </script>
@endsection
