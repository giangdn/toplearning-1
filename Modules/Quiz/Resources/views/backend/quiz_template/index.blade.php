@extends('layouts.backend')

@section('page_title', 'Cơ cấu đề thi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_exam')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('quiz-template-approved')
                            <button class="btn btn-success approved" data-model="el_quiz_templates" data-status="1">
                                <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                            </button>
                            <button class="btn btn-danger approved" data-model="el_quiz_templates" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}
                            </button>
                        @endcan
                    </div>

                    @can('quiz-template-open')
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="changeStatus(0,1)">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        </div>
                    @endcan
                    <p></p>

                    <div class="btn-group">
                        @can('quiz-template-create')
                            <a href="{{ route('module.quiz_template.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('quiz-template-delete')
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
                    <th data-field="code" data-width="5%" data-align="center">Mã bộ đề</th>
                    <th data-field="name" data-width="15%" data-formatter="name_formatter">Tên bộ đề</th>
                    <th data-field="name" data-width="15%" data-formatter="cate_question">Danh mục câu hỏi</th>
                    <th data-field="quiz_type" data-width="7%" data-align="center">{{trans('backend.quiz_form')}}</th>
                    <th data-field="limit_time" data-align="center" data-formatter="limit_time_formatter">{{trans('backend.time')}} <br> {{trans('backend.do_quiz')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="register_formatter">{{trans('backend.action')}}</th>
                    <th data-field="created_at2" data-align="center">{{trans('backend.create_time')}}</th>
                    <th data-field="user" data-align="center" data-formatter="created_formatter">{{ trans('lageneral.creator') }}</th>
                    <th data-field="user" data-align="center" data-formatter="updated_formatter">{{ trans('lageneral.editor') }}</th>
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

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " phút";
        }

        function cate_question(value, row, index) {
            console.log(row.get_cates);
            var get_cates = row.get_cates;
            var html = '';
            for (let index = 0; index < get_cates.length; index++) {
                html += '<p>'+ get_cates[index] +'</p>';
            }
            return html;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2 || null: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_quiz_templates" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
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
            if (row.export_url) {
                str += ' <a href="'+ row.export_url +'" class="btn btn-link"><i class="fa fa-download"></i> {{ trans("backend.print_the_exam") }}</a>';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz_template.getdata') }}',
            remove_url: '{{ route('module.quiz_template.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.quiz_template.ajax_is_open') }}";
        var ajax_status = "{{ route('module.quiz_template.ajax_status') }}";

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
