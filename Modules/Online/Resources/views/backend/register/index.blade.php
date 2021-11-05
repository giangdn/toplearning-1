@extends('layouts.backend')

@section('page_title', 'Quản lý Ghi danh')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.management') }}">{{ trans('backend.online_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.edit', ['id' => $online->id]) }}">{{ $online->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.enrollment_management') }}</span>
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
                <form class="form-inline form-search-user mb-3" id="form-search-user">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('backend.enter_code_name__email_username_employee') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            @if($online->lock_course == 0)
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('online-course-register-create')
                        <button type="button" class="btn btn-success" id="send-mail-user-registed"><i class="fa fa-send"></i> Gửi mail báo đã ghi danh</button>
                        @if(!$user_invited)
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" id="invite-user-register"><i class="fa fa-plus"></i> Mời ghi danh</button>
                        </div>
                        @endif

                        @if(count($quiz_exists) > 0)
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" id="add-to-quiz"><i class="fa fa-plus"></i> {{ trans('backend.add_student') }}</button>
                            </div>
                        @endif

                        <div class="btn-group">
                            <a class="btn btn-info" href="{{ download_template('mau_import_nhan_vien_ghi_danh_khoa_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                            <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        </div>
                    @endcan

                   @can('online-course-register-approve')
                       @if(!$user_invited)
                            <div class="btn-group">
                                <button type="button" class="btn btn-success approved" data-model="el_online_register" data-status="1">
                                    <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                                </button>
                                <button type="button" class="btn btn-danger approved" data-model="el_online_register" data-status="0">
                                    <i class="fa fa-times-circle"></i> {{ trans('backend.deny') }}
                                </button>
                            </div>
                       @endif
                    @endcan

                    <div class="btn-group">
                        @can('online-course-register-create')
                        <a href="{{ route('module.online.register.create', ['id' => $online->id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('online-course-register-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
            @endif
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500, ALL]" id="list-user-registed">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5px">{{ trans('backend.employee_code') }}</th>
                    <th data-sortable="true" data-field="name" data-width="20%" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    @if(count($quiz_exists) > 0)
                        <th data-field="quiz_name" data-align="center">{{ trans('backend.exam') }}</th>
                    @endif
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('backend.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.online.register.import_register', ['id' => $online->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $online->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.student') }}</h5>
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

    @if(count($quiz_exists) > 0)
    <div class="modal fade" id="modal-add-to-quiz">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.add_student') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    @if(count($quiz_exists) > 1)
                    <div class="form-group">
                        <label for="quiz_id">{{ trans('backend.exam') }}</label>
                        <select name="quiz_id" id="quiz_id" class="form-control load-quiz-online" data-course="{{ $online->id }}"
                                data-placeholder="{{trans('backend.choose_quiz')}}">
                            <option value=""></option>
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="quiz_id" value="{{ $quiz_exists[0]->subject_id }}">
                    @endif

                    <div class="form-group">
                        <label for="part_id">{{trans('backend.exams')}}</label>
                        <select name="part_id" id="part_id" class="form-control load-part-quiz-online" data-quiz_id="" data-placeholder="{{trans('backend.choose_exams')}}">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="save"><i class="fa fa-plus"></i> {{trans('backend.add_new')}}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{trans('backend.close')}}</button>
                </div>

            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="modal-invite-user-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mời ghi danh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Người có vai trò</label>
                        <select name="user_id" id="user_id" class="form-control select2" data-placeholder="-- {{ trans('backend.employee_name') }} --" required>
                            <option value=""></option>
                            @foreach ($user_has_role_register as $user_has_role)
                                <option value="{{ $user_has_role->user_id }}" data-role="{{ $user_has_role->role_id }}">{{ \App\Profile::fullname($user_has_role->user_id) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>SL ghi danh</label>
                        <input type="text" name="num_register" class="form-control is-number" value="" placeholder="SL ghi danh" required>
                    </div>

                    <div class="form-group">
                        <div class="text-right">
                            <button id="delete-invite-user-role" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                        </div>
                        <p></p>
                        <table class="tDefault table table-hover bootstrap-table" id="invite-user-role">
                            <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                                <th data-field="user_code">{{ trans('backend.employee_code') }}</th>
                                <th data-field="user_name">{{ trans('backend.employee_name') }}</th>
                                <th data-field="num_register" data-align="center">SL ghi danh</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    <button type="submit" class="btn btn-primary" id="invite-user">Thêm</button>
                </div>
            </div>

            <script type="text/javascript">
                var table_invite_user = new LoadBootstrapTable({
                    locale: '{{ \App::getLocale() }}',
                    url: '{{ route('module.online.register.getdata.invite_user', ['id' => $online->id]) }}',
                    remove_url: '{{ route('module.online.register.remove.invite_user', ['id' => $online->id]) }}',
                    table: '#invite-user-role',
                    detete_button: '#delete-invite-user-role',
                });

                function index_formatter(value, row, index) {
                    return index + 1;
                }
            </script>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }

        function title_formatter(value, row, index) {
            return row.title_name;
        }

        function unit_approve_formatter(value, row, index) {
            if (value == 0) {
                return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
            }

            if (value == 1) {
                return '<span class="text-success">{{ trans("backend.approved") }}</span>';
            }

            return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_online_register" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function status_formatter(value, row, index) {
            if (value == 0) {
                return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
            }else if (value == 1) {
                return '<span class="text-success">{{ trans("backend.approved") }}</span>';
            }else{
                return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.register.getdata', ['id' => $online->id]) }}',
            remove_url: '{{ route('module.online.register.remove', ['id' => $online->id]) }}',
            table: '#list-user-registed',
            form_search: '#form-search-user'
        });

        $('#quiz_id').on('change', function () {
            var quiz_id = $('#quiz_id option:selected').val();
            $("#part_id").empty();
            $("#part_id").data('quiz_id', quiz_id);
            $('#part_id').trigger('change');
        });

        var quiz_id = $("input[name=quiz_id]").val();
        $("#part_id").empty();
        $("#part_id").data('quiz_id', quiz_id);
        $('#part_id').trigger('change');

        $("#add-to-quiz").on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $('#modal-add-to-quiz').modal();

            $('#save').on('click', function () {
                var quiz_id = $("input[name=quiz_id]").val() ? $("input[name=quiz_id]").val() : $('#quiz_id option:selected').val();
                var part_id = $('#part_id option:selected').val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.online.register.add_to_quiz', ['id' => $online->id]) }}',
                    dataType: 'json',
                    data: {
                        'ids': ids,
                        'part_id': part_id,
                        'quiz_id': quiz_id,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    $('#modal-add-to-quiz').hide();
                    window.location = '';
                    return false;
                }).fail(function(data) {
                    return false;
                });
            });
        });

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.register.send_mail_user_registed', ['id' => $online->id]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                table.refresh();
                return false;
            }).fail(function(data) {
                return false;
            });
        })

        $("#invite-user-register").on('click', function () {
            $('#modal-invite-user-register').modal();

            $('#invite-user').on('click', function () {
                var user_id = $('#user_id option:selected').val();
                var role_id = $('#user_id option:selected').data('role');
                var num_register = $("input[name=num_register]").val();

                if(!user_id){
                    show_message('Vui lòng chọn nhân viên!', 'error');
                    return false;
                }

                if(!num_register){
                    show_message('Vui lòng nhập SL ghi danh!', 'error');
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.online.register.invite_user', ['id' => $online->id]) }}',
                    dataType: 'json',
                    data: {
                        'user_id': user_id,
                        'role_id': role_id,
                        'num_register': num_register,
                    }
                }).done(function(data) {
                    $("#user_id").val('').trigger('change');
                    $("input[name=num_register]").val('').trigger('change');

                    table_invite_user.refresh();

                    return false;
                }).fail(function(data) {
                    return false;
                });
            });
        });

    </script>
@endsection
