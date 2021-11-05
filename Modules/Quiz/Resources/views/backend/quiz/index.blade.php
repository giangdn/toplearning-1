@extends('layouts.backend')

@section('page_title', 'Các kỳ thi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6 form-inline">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_exam')}}">

                    <input name="start_date" class="form-control datepicker w-25" placeholder="{{trans('backend.start_date')}}">

                    <input name="end_date" class="form-control datepicker w-25" placeholder="{{trans('backend.end_date')}}">

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    @can('quiz-view-result')
                    <div class="btn-group">
                        <button class="btn btn-primary result" data-status="1">
                            <i class="fa fa-check-circle"></i> {{trans("backend.see_result")}}
                        </button>
                        <button class="btn btn-danger result" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans("backend.off_result")}}
                        </button>
                    </div>
                    @endcan

                    @can('quiz-approve')
                    <div class="btn-group">
                        <button class="btn btn-success approved" data-model="el_quiz" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                        </button>
                        <button class="btn btn-danger approved" data-model="el_quiz" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}
                        </button>
                    </div>
                    @endcan

                    @can('quiz-status')
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="changeStatus(0,1)">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning" onclick="changeStatus(0,0)">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    <p></p>
                    @endcan
                    @canany(['quiz-create', 'quiz-edit'])
                        <div class="btn-group">
                            <button class="btn btn-success" id="send-mail-approve">
                                <i class="fa fa-send"></i> {{trans("backend.send_request_mail")}} {{trans("backend.approve")}}
                            </button>

                            <button class="btn btn-success" id="send-mail-change">
                                <i class="fa fa-send"></i> {{trans("backend.send_mail_report")}}
                            </button>

                            <button class="btn btn-success" id="send-mail-invitation">
                                <i class="fa fa-send"></i> {{trans("backend.send_mail_invite")}}
                            </button>
                        </div>
                        <p></p>
                    @endcanany
                    <div class="btn-group">
                        @can('quiz-copy')
                        <button class="btn btn-warning copy">
                            <i class="fa fa-copy"></i> &nbsp;{{trans("backend.copy")}}
                        </button>
                        @endcan
                        @can('quiz-create')
                        <a href="{{ route('module.quiz.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('quiz-delete')
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
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    <th data-field="is_open" data-width="3%" data-formatter="is_open_formatter" data-align="center">{{trans('backend.status')}}</th>
                    <th data-field="code" data-width="5%" data-align="center">{{trans('backend.quiz_code')}}</th>
                    <th data-field="name" data-width="15%" data-formatter="name_formatter">{{trans('backend.quiz_name')}}</th>
                    <th data-field="quiz_type" data-width="7%" data-align="center">{{trans('backend.quiz_form')}}</th>
                    <th data-field="quiz_time" data-width="21%" data-formatter="quiz_time_formatter">{{trans('backend.time')}}</th>
                    <th data-field="limit_time" data-align="center" data-formatter="limit_time_formatter">{{trans('backend.time')}} <br> {{trans('backend.do_quiz')}}</th>
                    <th data-field="view_result" data-formatter="view_result_formatter" data-align="center" data-width="7%">{{trans('backend.see_result')}}</th>
                    <th data-field="course_name">Khóa học</th>
                    <th data-field="regist" data-align="center" data-formatter="register_formatter">{{trans('backend.action')}}</th>
                    <th data-field="quantity_quiz_attempts" data-width="10%" data-align="center" data-formatter="number_candidates_submission">
                        {{trans('backend.number_candidates_submission')}}
                    </th>
                    <th data-field="created_at2" data-align="center">{{trans('backend.create_time')}}</th>
                    <th data-field="user" data-align="center" data-formatter="created_formatter">{{trans('backend.user_create')}}</th>
                    <th data-field="user" data-align="center" data-formatter="updated_formatter">Người sửa</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('backend.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function quiz_time_formatter(value, row, index) {
            return row.start_date + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : '');
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_quiz" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function number_candidates_submission(value, row, index) {
            console.log(row);
            return row.quantity_quiz_attempts + ' / ' + row.quantity;
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " phút";
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2 || null: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        function view_result_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.viewed") }}</span>' : '<span class="text-danger">{{ trans("backend.not_seen") }}</span>';
        }

        function is_open_formatter(value, row, index) {
            var status = row.is_open == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_url+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function user_approved_formatter(value, row, index) {
            if (row.user_approved_url){
                return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_approved_url+'"><i class="fa fa-user"></i></a>';
            }
            return '';
        }

        function register_formatter(value, row, index) {
            let str = '';
            if (row.question) {
                str += '<a href="'+ row.question +'" class="btn btn-primary"><i class="fa fa-question-circle"></i> {{ trans("backend.question") }}</a> ';
            }
            if (row.register_url){
                str += '<a href="'+ row.register_url +'" class="btn btn-info"><i class="fa fa-users"></i> {{ trans("backend.internal_contestant") }}</a> <p></p> ';
            }
            if (row.result){
                str += '<a href="'+ row.result +'" class="btn btn-success"><i class="fa fa-eye"></i></i> {{ trans("backend.result") }}</a> ';
            }
            if (row.user_secondary_url) {
                str += '<a href="'+ row.user_secondary_url +'" class="btn btn-warning"><i class="fa fa-users"></i> {{ trans("backend.examinee_outside") }}</a>';
            }
            if (row.export_url) {
                str += ' <a href="'+ row.export_url +'" class="btn btn-link"><i class="fa fa-download"></i> {{ trans("backend.print_the_exam") }}</a>';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.getdata') }}',
            remove_url: '{{ route('module.quiz.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.quiz.ajax_is_open') }}";
        var ajax_status = "{{ route('module.quiz.ajax_status') }}";
        var ajax_view_result = "{{ route('module.quiz.ajax_view_result') }}";
        var ajax_copy_quiz = "{{ route('module.quiz.ajax_copy_quiz') }}";

        // BẬT/TẮT
        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: ajax_isopen_publish,
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
    </script>
    <script src="{{ asset('styles/module/quiz/js/quiz.js') }}"></script>
@endsection
