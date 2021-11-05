<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.quiz_template.question.save_question_random', ['id' => $quiz_id]) }}" method="post" class="form-ajax" data-success="success_submit">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.add_random_question') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('backend.category') }}</label>
                        <select name="category_id" class="form-control select23" data-placeholder="-- {{ trans('backend.category') }} --">
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $count_question($category->id) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.number_random_questions') }}</label>
                        <input name="random_question" class="form-control is-number" type="text">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
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
</script>

