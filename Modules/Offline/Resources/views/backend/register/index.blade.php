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
            <a href="{{ route('module.offline.management') }}">{{ trans('backend.offline_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.edit', ['id' => $course_id]) }}">{{ $offline->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.register') }}</span>
        </h2>
    </div>
    <div role="main">
    @if(isset($errors))

    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach

    @endif
        <div class="row">
            @if($offline->id && !$user_invited)
                <div class="col-md-12 text-center">
                    @canany(['offline-course-create', 'offline-course-edit'])
                    <a href="{{ route('module.offline.edit',['id' => $offline->id]) }}" class="btn btn-info">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans('backend.info') }}</div>
                    </a>
                    @endcanany
                    @canany(['offline-course-teacher'])
                        <a href="{{ route('module.offline.teacher', ['id' => $offline->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-inbox"></i></div>
                            <div>{{ trans('backend.teacher') }}</div>
                        </a>
                    @endcanany
                        <a href="{{ route('module.offline.monitoring_staff', ['id' => $offline->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>Cán bộ theo dõi</div>
                        </a>
                    @canany(['offline-course-attendance'])
                        <a href="{{ route('module.offline.attendance', ['id' => $offline->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>{{ trans('backend.attendance') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-result'])
                        <a href="{{ route('module.offline.result', ['id' => $offline->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans('backend.training_result') }}</div>
                        </a>
                    @endcanany
                    {{--@can('offline-course-rating-result')
                        <a href="{{ route('module.rating.result.index', ['course_id' => $offline->id, 'type' => 2]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>
                    @endcan--}}
                    @can('offline-course-rating-level-result')
                        <a href="{{ route('module.offline.rating_level.list_report', [$offline->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>Kết quả đánh giá</div>
                        </a>
                    @endcan
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 mb-2">
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
            @if($offline->lock_course == 0)
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @canany(['offline-course-register-create'])
                        <button type="button" class="btn btn-success" id="send-mail-user-registed"><i class="fa fa-send"></i> Gửi mail báo đã ghi danh</button>

                        @if(!$user_invited)
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" id="invite-user-register"><i class="fa fa-plus"></i> Mời ghi danh</button>
                            </div>
                        @endif

                        @if($offline->quiz_id)
                            <button type="button" class="btn btn-success" id="add-to-quiz"><i class="fa fa-plus"></i> {{ trans('backend.add_student') }}</button>
                        @endif
                        <div class="btn-group">
                            <a class="btn btn-info" href="{{ download_template('mau_import_nhan_vien_ghi_danh_khoa_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                            <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> Import
                            </button>
                            <a class="btn btn-info" href="{{ route('module.offline.register.export_register', ['id' => $offline->id]) }}">
                                <i class="fa fa-download"></i> Export
                            </a>
                        </div>
                    @endcanany
                    @canany(['offline-course-register-approve'])
                        @if(!$user_invited)
                        <div class="btn-group">
                            <button type="button" class="btn btn-success approved" data-model="el_offline_register" data-status="1"><i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}</button>
                            <button type="button" class="btn btn-danger approved" data-model="el_offline_register" data-status="0"><i class="fa fa-times-circle"></i> {{ trans('backend.deny') }}</button>
                        </div>
                        @endif
                    @endcanany
                    @canany(['offline-course-register-create'])
                        <div class="btn-group">
                            <a href="{{ route('module.offline.register.create', ['id' => $offline->id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        </div>
                    @endcanany
                </div>
            </div>
            @endif
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500, ALL]" id="list-user-registed">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code">{{ trans('backend.employee_code') }}</th>
                    <th data-sortable="true" data-width="25%" data-field="full_name"  >{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('backend.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.register.import_register', ['id' => $course_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $offline->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.user') }}</h5>
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

    @if($offline->quiz_id)
    <div class="modal fade" id="modal-add-to-quiz">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.add_student') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="part_id">{{trans('backend.exams')}}</label>
                        <select name="part_id" id="part_id" class="form-control select2" data-placeholder="{{trans('backend.choose_exams')}}">
                            <option value=""></option>
                            @php
                                $quiz_parts = isset($offline->quiz_id) ? $quiz_part($offline->quiz_id) : [];
                            @endphp
                            @foreach($quiz_parts as $part)
                                <option value="{{ $part->id }}">{{ $part->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="save"><i class="fa fa-plus"></i>
                    {{ trans('backend.add_new') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                    url: '{{ route('module.offline.register.getdata.invite_user', ['id' => $offline->id]) }}',
                    remove_url: '{{ route('module.offline.register.remove.invite_user', ['id' => $offline->id]) }}',
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
        function status_formatter(value, row, index) {

            if (value == 1) {
                return '<span class="text-success">{{ trans("backend.approved") }}</span>';
            }
            else if (value == 0) {
                return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
            }
            else {
                return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }

        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_offline_register" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function unit_status_formatter(value, row, index) {
            return row.status_level_1 == 1 ? '<span class="text-primary">{{ trans("backend.approved") }}</span>' : row.status_level_1 == 0 ? '<span ' +
                'class="text-danger">{{ trans("backend.deny") }}</span>' : '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.register.getdata', ['id' => $course_id]) }}',
            remove_url: '{{ route('module.offline.register.remove', ['id' => $course_id]) }}',
            table: '#list-user-registed',
            form_search: '#form-search-user'
        });

        $("#add-to-quiz").on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $('#modal-add-to-quiz').modal();

            $('#save').on('click', function () {
                var part_id = $('#part_id option:selected').val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.offline.register.add_to_quiz', ['id' => $offline->id]) }}',
                    dataType: 'json',
                    data: {
                        'ids': ids,
                        'part_id': part_id,
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
                    url: '{{ route('module.offline.register.invite_user', ['id' => $offline->id]) }}',
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

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.register.send_mail_user_registed', ['id' => $course_id]) }}',
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
    </script>
@endsection
