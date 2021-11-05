@extends('layouts.backend')

@section('page_title', 'Thí sinh bên ngoài')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">
    @if(isset($errors))

    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach

    @endif
        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_block')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    @can('quiz-user-secondary-create')
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp; {{ trans('lacore.disable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp; {{ trans('lacore.enable') }}
                            </button>
                        </div>

                        <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_nguoi_ngoai_ghi_danh_ky_thi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('quiz-user-secondary-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('quiz-user-secondary-delete')
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
                    <th data-sortable="true" data-width="10px" data-field="code">{{trans('backend.employee_outside_code')}}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{trans('backend.fullname')}}</th>
                    <th data-field="username">{{trans('backend.user_name')}}</th>
                    <th data-field="dob" data-align="center">{{ trans('backend.dob') }}</th>
                    <th data-field="identity_card" data-align="center">{{ trans('backend.identity_card') }}</th>
                    <th data-field="email" data-align="center">Email</th>
                    <th data-field="created_at2" data-align="center">{{ trans('backend.created_at') }}</th>
                    <th data-field="status_clock" data-formatter="status_clock_formatter" data-align="center">Trạng thái</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.user_secondary.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.user_secondary') }}</h5>
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
                            @canany(['quiz-user-secondary-create', 'quiz-user-secondary-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save ') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.employee_outside_code')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.fullname') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.user_name')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="username" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.pass')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="password" id="password" type="password" class="form-control" value="" placeholder="{{trans('backend.pass')}}" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="repassword" id="repassword" type="password" class="form-control" value="" placeholder="{{trans('backend.repassword')}}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.dob') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="dob" type="text" class="form-control datepicker" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>Email</label>
                            </div>
                            <div class="col-md-7">
                                <input name="email" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.identity_card') }} <span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-7">
                                <input name="identity_card" type="text" class="form-control is-number" value="" required>
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

        function status_clock_formatter(value, row, index) {
            return row.status_clock == 1 ? {{ trans('lacore.disable') }} : {{ trans('lacore.enable') }};
        }

        function status_clock_formatter(value, row, index) {
            var status = row.status_clock == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.user_secondary.getdata') }}',
            remove_url: '{{ route('module.quiz.user_secondary.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 thí sinh', 'error');
                    return false;
                }
            }
            $.ajax({
                url: base_url +'/admin-cp/user-secondary/lock',
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
                url: "{{ route('module.quiz.user_secondary.edit') }}",
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
                $("input[name=username]").val(data.username);
                $("input[name=dob]").val(data.dob);
                $("input[name=email]").val(data.model.email);
                $("input[name=identity_card]").val(data.model.identity_card);
                $("input[name=password]").val('');
                $("input[name=repassword]").val('');
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
            var username =  $("input[name=username]").val();
            var dob =  $("input[name=dob]").val();
            var email =  $("input[name=email]").val();
            var password =  $("input[name=password]").val();
            var repassword =  $("input[name=repassword]").val();
            var identity_card =  $("input[name=identity_card]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.quiz.user_secondary.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'username': username,
                    'dob': dob,
                    'email': email,
                    'password': password,
                    'repassword': repassword,
                    'identity_card': identity_card,
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
            $("input[name=username]").val('');
            $("input[name=dob]").val('');
            $("input[name=email]").val('');
            $("input[name=identity_card]").val('');
            $("input[name=password]").val('');
            $("input[name=repassword]").val('');
            $('#exampleModalLabel').html('Thêm mới');
            $('#modal-popup').modal();
        }
    </script>
@endsection
