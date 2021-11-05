<style>
    table video {
        width: 50%;
        height: auto;
    }

    table img {
        width: 50% !important;
        height: auto !important;
    }
</style>
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('backend.add_questionlib') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <form action="" id="form-search">
                    <div class="form-group">
                        <label>{{ trans('backend.category') }}</label>
                        <select name="category_id" id="category_id" class="form-control select23" data-placeholder="-- {{ trans('backend.category') }} --">
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $count_question($category->id) }})</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <form action="" method="post" class="form-ajax" id="form-add-question" data-success="success_submit">
                    <div class="form-group">
                        <table class="tDefault table table-hover bootstrap-table">
                            <thead>
                                <tr>
                                    <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                                    <th data-field="name">{{ trans('backend.question') }}</th>
                                    <th data-field="type" data-formatter="type_formatter" data-align="center" data-width="20%">{{ trans('backend.kind_question') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary button-save" disabled><i class="fa fa-plus-circle"></i> {{ trans('backend.add_question_select') }}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    function index_formatter(value, row, index) {
        return (index+1);
    }

    function type_formatter(value, row, index) {
        switch(value){
            case('essay'): return 'Tự luận';
            case('matching'): return 'Nối câu';
            case('fill_in'): return 'Điền trống';
            case('fill_in_correct'): return 'Điền từ chính xác';
            default: return 'Trắc nghiệm';
        }
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz.question.getdata_question', ['id' => $quiz_id]) }}',
        search: true,
        page_size:10
    });

    $(".select23").select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $('#form-add-question').on('change', function(){
        if($('input[name=btSelectAll], input[name=btSelectItem]').is(':checked')){
            $(".button-save").prop('disabled', false);
        }
    });

    $(".button-save").on('click', function() {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');
        var qcate_id = $('#category_id').val();

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        $.ajax({
            type: 'POST',
            url: "{{ route('module.quiz.question.save_category_question', ['id' => $quiz_id]) }}",
            dataType: 'json',
            data: {
                ids: ids,
                cate_id: qcate_id,
            },
        }).done(function(data) {

            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);

            table.refresh();
            $("#app-modal #myModal").modal('hide');
            window.location = "";
            return false;
        }).fail(function(data) {
            show_message('Chưa chọn câu hỏi', 'error');
            button.find('i').attr('class', icon);
            button.prop("disabled", false);
            return false;
        });

    });
    $('#category_id').on('change',function () {
        table.refresh();
    });
    function success_submit(form) {
        $("#app-modal #myModal").modal('hide');
        table.refresh();
    }
</script>

