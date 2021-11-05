@extends('layouts.backend')

@section('page_title', 'Kết quả')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.quiz') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.manager') }}">{{ trans('backend.quiz_list') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.edit', ['id' => $quiz_id]) }}">{{ $quiz_name->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.result') }}</span>
        </h2>
    </div>
    <div role="main" id="quiz-result">
    @if(isset($errors))

    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach

    @endif
        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}"
                                class="form-control load-unit"
                                data-placeholder="-- ĐV cấp {{$i}} --"
                                data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                            </select>
                        </div>
                    @endfor

                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <select name="status" class="form-control select2" data-placeholder="-- {{ trans('backend.status') }} --">
                            <option value=""></option>
                            <option value="0">{{ trans('backend.inactivity') }}</option>
                            <option value="1">{{ trans('backend.doing') }}</option>
                            <option value="2">{{ trans('backend.probationary') }}</option>
                            <option value="3">{{ trans('backend.pause') }}</option>
                        </select>
                    </div>

                    <div class="w-25">
                        <select name="type" class="form-control select2" data-placeholder="-- {{ trans('backend.examinee_type') }} --">
                            <option value=""></option>
                            <option value="1">{{trans('backend.internal_contestant')}}</option>
                            <option value="2">{{trans('backend.examinee_outside')}}</option>
                        </select>
                    </div>

                    <div class="w-25">
                        <select name="part" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
                            <option value=""></option>
                            @foreach ($quiz_part as $part)
                                <option value="{{ $part->id }}" >{{ $part->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-25">
                        <select name="result_quiz" class="form-control select2" data-placeholder="-- {{ trans('backend.result') }} --">
                            <option value=""></option>
                            <option value="1"> {{trans('backend.submitted')}}</option>
                            <option value="2"> {{trans('backend.not_yet_submitted')}}</option>
                            <option value="3"> {{trans('backend.achieved')}}</option>
                            <option value="4"> {{trans('backend.not_achieved')}}</option>
                        </select>
                    </div>

                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if($quiz_name->paper_exam == 1)
                            <a class="btn btn-info" href="{{ download_template('mau_import_diem_ky_thi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>

                            <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        @endif
                        @if($export_result)
                            <a class="btn btn-info" href="javascript:void(0)" id="export-result">
                                <i class="fa fa-download"></i> Export
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-field="code" data-formatter="code_formatter" data-width="5%">MNV</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter" data-width="15%">{{ trans('backend.employee_name') }}</th>
                    <th data-field="type" data-formatter="type_formatter" data-width="10%">{{ trans('backend.examinee') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="dob" data-align="center" data-formatter="dob_formatter" data-width="5%">{{ trans('backend.dob') }}</th>
                    <th data-field="identity_card" data-align="center" data-formatter="identity_card_formatter" data-width="7%">{{ trans('backend.identity_card') }}</th>
                    <th data-field="email" data-formatter="email_formatter">Email</th>
                    <th data-field="part_name" data-align="center" data-width="5%">{{trans('backend.exams')}}</th>
                    <th data-field="grade" data-formatter="grade_formatter" data-width="15%">{{ trans('backend.score') }}</th>
                    <th data-field="reexamine" data-align="center" data-formatter="reexamine_formatter" data-width="5%">{{ trans('backend.references') }}</th>
                    <th data-field="res" data-align="center" data-formatter="res_formatter" data-width="5%">{{ trans('backend.result') }}</th>
                    <th data-field="file" data-align="center" data-formatter="file_formatter" data-width="5%">{{ trans('backend.attach_file') }}</th>
                    <th data-field="view_quiz" data-align="center" data-formatter="view_quiz_formatter" data-width="5%">{{ trans('backend.task') }}</th>
                    <th data-field="view_image" data-align="center" data-formatter="view_image_formatter" data-width="5%">{{trans('backend.picture')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.result.import_result', ['id' => $quiz_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $quiz_name->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.score') }}</h5>
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

    <script type="text/javascript">
        function code_formatter(value, row, index) {
            return row.type == 1 ? row.profile_code : row.user_secon_code;
        }

        function name_formatter(value, row, index) {
            return row.type == 1 ? row.lastname + ' ' + row.firstname : row.secondary_name;
        }

        function dob_formatter(value, row, index) {
            return row.type == 1 ? row.profile_dob : row.user_secon_dob;
        }

        function identity_card_formatter(value, row, index) {
            return row.type == 1 ? row.profile_identity_card : row.user_secon_identity_card;
        }

        function email_formatter(value, row, index) {
            return row.type == 1 ? row.profile_email : row.user_secon_email;
        }

        function grade_formatter(value, row, index) {
            return '<input style="width:50px;" type="text" {{ $save_grade ? '' : 'disabled' }} name="grade" data-regid="'+row.regid+'" data-id="'+ row.result_id +'" value="'+row.grade+'" class="form-control is-number change-grade" '+ ((row.paper_exam == 1) ? '' : 'readonly') +' >';
        }

        function reexamine_formatter(value, row, index) {
            return '<input type="text" {{ $save_reexamine ? '' : 'disabled' }} name="reference" data-regid="'+row.regid+'" data-id="'+ row.result_id +'" value="'+row.reexamine+'" class="form-control is-number change-reexamine" value="" >';
        }

        function file_formatter(value, row, index) {
            return '<div class="attemp btn-group" {{ ($save_grade || $save_reexamine )? '' : 'disabled' }}><a href="javascript:void(0)" class="select-file btn btn-primary"><i class="fa fa-upload"></i></a> <input type="hidden" data-regid="'+row.regid+'" data-id="'+ row.result_id +'" value="'+row.file+'" name="file" class="file-select"> <a href="'+ row.link_download +'" title="'+ row.file_name +'" class="btn btn-primary '+ (row.link_download ? '' : 'disabled') +'"><i class="fa fa-download"></i></a></div>';
        }

        function res_formatter(value, row, index) {
            return row.res == 1 ? '<span class="text-success">Đậu</span>' : row.res == 0 ? '<span class="text-danger">Rớt</span>' : '';
        }

        function type_formatter(value, row, index) {
            return value == 1 ? '{{trans("backend.internal")}}' : '{{trans("backend.outside")}}';
        }

        function view_quiz_formatter(value, row, index){
            if (row.status != 0){
                return '<a href="'+row.review_link+'"><i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        function view_image_formatter(value, row, index){
            if (row.status != 0){
                return '<a href="'+row.url_image+'"><i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.result.getdata', ['id' => $quiz_id]) }}',
        });

    </script>

<script type="text/javascript">
    $('#quiz-result').on('click', '.select-file', function () {
        let item = $(this);
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            item.closest(".attemp").find('.file-review').html(path2[path2.length - 1]);
            item.closest(".attemp").find('.file-select').val(path);
            var result_id = item.closest(".attemp").find('.file-select').data('id');
            var regid = item.closest(".attemp").find('.file-select').data('regid');

            $.ajax({
                url: "{{ route('module.quiz.result.save_file', ['id' => $quiz_id]) }}",
                type: 'post',
                data: {
                    result_id: result_id,
                    regid : regid,
                    path : path,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });

        });
    });

    $('#quiz-result').on('change', '.change-grade', function () {
        var result_id = $(this).data('id');
        var regid = $(this).data('regid');
        var grade = $(this).val();

        $.ajax({
            url: "{{ route('module.quiz.result.save_grade', ['id' => $quiz_id]) }}",
            type: 'post',
            data: {
                result_id: result_id,
                regid : regid,
                grade : grade,
            }
        }).done(function(data) {
            if(data.status == 'error'){
                show_message(data.message, 'error');
            }
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#quiz-result').on('change', '.change-reexamine', function () {
        var result_id = $(this).data('id');
        var regid = $(this).data('regid');
        var reexamine = $(this).val();

        $.ajax({
            url: "{{ route('module.quiz.result.save_reexamine', ['id' => $quiz_id]) }}",
            type: 'post',
            data: {
                result_id: result_id,
                regid : regid,
                reexamine : reexamine,
            }
        }).done(function(data) {
            if(data.status == 'error'){
                show_message(data.message, 'error');
            }
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#export-result').on('click', function () {
        let form_search = $("#form-search").serialize();
        window.location = '{{ route('module.quiz.result.export_result', ['id' => $quiz_id]) }}?'+form_search;
    })
</script>
@endsection
