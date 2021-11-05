<div role="main">
    <div class="row">
        <div class="col-md-8">
            {{-- <form class="form-inline" id="form-search-ask-answer">
                <input type="text" name="search_note" value="" class="form-control" placeholder="Nhập tên">
                <button class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
            </form> --}}
        </div>
        <div class="col-md-4 text-right act-btns">
            @can('online-course-status')
                <div class="btn-group">
                    <button class="btn btn-primary status_ask_answer" data-status="1">
                        <i class="fa fa-check-circle"></i> &nbsp;Hiện
                    </button>
                    <button class="btn btn-warning status_ask_answer" data-status="0">
                        <i class="fa fa-exclamation-circle"></i> &nbsp;Ẩn
                    </button>
                </div>
            @endcan
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover" id="table-ask-answer">
        <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="status" data-formatter="status" data-width="10%" data-align="center">Trạng thái</th>
                <th data-field="fullname" data-width="20%">Tên học viên</th>
                <th data-field="ask">Câu hỏi</th>
                <th data-field="answer" data-formatter="answer">Trả lời</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function answer(value, row, index) {
            return `<textarea class="w-100 form-control answer" style="height:70px" data-id="`+ row.id +`">`+ (row.answer ? row.answer : "") +`</textarea>`;
        }

        function status(value, row, index) {
            return row.status == 1 ? '<span style="color:green">Hiện</span>' : '<span style="color:red">Ẩn</span>';
        }

        var table_note = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_user_ask_answer',['course_id' => $model->id]) }}',
            table: '#table-ask-answer',
            form_search: '#form-search-ask-answer',
        });

        var ajax_save_answer = "{{ route('module.online.save_answer') }}";
        var ajax_isopen_status = "{{ route('module.online.ajax_isopen_status') }}";

        $(document).ready(function () {
            $('#table-ask-answer').on('change', '.answer', function() {
                var answer = $(this).val();
                var regid = $(this).data('id');

                $.ajax({
                    url: ajax_save_answer,
                    type: 'post',
                    data: { 
                        answer: answer,
                        regid : regid,
                    },
                }).done(function(data) {
                    return false;
                }).fail(function(data) {
                    show_message(
                        'Lỗi hệ thống',
                        'error'
                    );
                    return false;
                });
            });

            $('.status_ask_answer').on('click', function () {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                var status = $(this).data('status');

                if (ids.length <= 0) {
                    show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                    return false;
                }

                $.ajax({
                    url: ajax_isopen_status,
                    type: 'post',
                    data: {
                        ids: ids,
                        status: status
                    }
                }).done(function(data) {
                    $('#table-ask-answer').bootstrapTable('refresh');
                    return false;
                }).fail(function(data) {
                    show_message('Lỗi hệ thống', 'error');
                    return false;
                });
            });
        });
</script>
