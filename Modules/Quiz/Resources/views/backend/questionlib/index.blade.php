@extends('layouts.backend')

@section('page_title', trans('backend.questionlib'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline w-100 form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category_name') }}">
                    <div class="w-25">
                        <select name="parent_id" class="form-control select2" data-placeholder="-- {{ trans('backend.parent_category') }} --">
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group"> 
                        <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        @can('quiz-category-question-create')
                            <a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="{{ route('module.quiz.questionlib.get_modal') }}"><i class="fa fa-plus-circle"></i> @lang('backend.add_new')</a>
                        @endcan
                        @can('quiz-category-question-delete')
                            <button class="btn btn-danger" id="delete-item-libquestion"><i class="fa fa-trash"></i> @lang('backend.delete')</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table-question-lib" data-tree-enable="true">
            <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                <th data-field="state" data-checkbox="true" data-width="3%"></th>
                <th data-field="status" data-formatter="status_formatter" data-width="5%" data-align="center">{{ trans('backend.status') }}</th>
                <th data-field="name" data-formatter="name_formatter" data-width="30%">{{ trans('backend.category_questions') }}</th>
                <th data-field="parent_name">{{ trans('backend.parent_category') }}</th>
                <th data-field="quantity" data-width="10%" data-align="center">{{ trans('backend.number_questions') }}</th>
                <th data-field="cate_user" data-width="10%" data-align="center" data-formatter="cate_user_formatter">{{ trans('backend.permisstion') }}</th>
                <th data-field="question" data-width="10%" data-align="center" data-formatter="question_formatter">{{ trans('backend.question') }}</th>
                <th data-field="export" data-width="10%" data-align="center" data-formatter="export_question_formatter">Xuất câu hỏi</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var $table_question = $('#table-question-lib');

        var table = new LoadBootstrapTable({
            url: '{{ route('module.quiz.questionlib.getdata_category') }}',
            locale: '{{ \App::getLocale() }}'
        });

        // $table_question.bootstrapTable({
        //         url: '{{ route('module.quiz.questionlib.getdata_category') }}',
        //         striped: true,
        //         sidePagination: 'server',
        //         pagination: true,
        //         idField: 'id',
        //         treeShowField: 'name',
        //         parentIdField: "parent_id",
        //         onPostBody: function() {
        //             var columns = $table_question.bootstrapTable('getOptions').columns;
        //             //if (columns && columns[0][3].visible) {
        //             $table_question.treegrid({
        //                     treeColumn: 3,
        //                     onChange: function() {
        //                         $table_question.bootstrapTable('resetView')
        //                     }
        //                 })
        //             //}
        //         }
        //     });

        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="edit-item" data-id="'+ row.id +'">'+ value + ' (' + row.num_child + ') </a>';
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function question_formatter(value, row, index) {
            var html = '';
            @can('quiz-question')
                html = '<a href="'+ row.question_url +'"><i class="fa fa-cogs"></i></a>';
            @endcan
                return html;
        }

        function cate_user_formatter(value, row, index) {
            var html = '';
            @can('quiz-category-question-permission')
                html = '<a href="'+ row.cate_user_url +'"><i class="fa fa-users"></i></a>';
            @endcan
                return html;
        }

        function export_question_formatter(value, row, index) {
            let str = '';
            if (row.export_word) {
                str += ' <a href="'+ row.export_word +'" class="btn btn-link"><i class="fa fa-download"></i> In word</a>';
            }
            if (row.export_excel) {
                str += ' <a href="'+ row.export_excel +'" class="btn btn-link"><i class="fa fa-download"></i> In excel</a>';
            }

            return str;
        }

        $('#delete-item-libquestion').prop('disabled', true);

        $('#delete-item-libquestion').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            Swal.fire({
                title: '',
                text: 'Bạn có chắc muốn xóa các mục đã chọn không ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý!',
                cancelButtonText: 'Hủy!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('module.quiz.questionlib.remove_category') }}',
                        dataType: 'json',
                        data: {
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                $table_question.bootstrapTable('refresh');
                                return false;
                            }
                            else {
                                show_message(result.message, result.status);
                                return false;
                            }
                        }
                    });
                }
            });

            return false;
        });

        function success_submit(form) {
            $("#app-modal #myModal").modal('hide');
            $table_question.bootstrapTable('refresh');
        }

        $table_question.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', () => {
            $('#delete-item-libquestion').prop('disabled', !$table_question.bootstrapTable('getSelections').length);
        });

        $("div[role=main]").on('click', '.edit-item', function () {
            let item = $(this);
            let oldtext = item.html();
            let id = item.data('id');
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.questionlib.get_modal') }}',
                dataType: 'html',
                data: {
                    'id': id,
                },
            }).done(function(data) {
                item.html(oldtext);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.html(oldtext);
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });

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
                url: '{{ route('module.quiz.questionlib.save_status_category') }}',
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
@endsection
