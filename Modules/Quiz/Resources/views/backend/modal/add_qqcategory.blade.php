<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('module.quiz.question.save_qqcategory', ['id' => $quiz->id]) }}" method="post" class="form-ajax" data-success="success_add_qqcategory">

                <input type="hidden" name="id" value="{{ $category->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.add_new') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('backend.title_item') }}</label>
                        <input name="name" type="text" class="form-control" value="{{ $category->name }}">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.title_percentage') }}</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control is-number" placeholder="Số %" name="percent_group" value="{{ $category->percent_group }}" >
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
                </div>

                <input type="hidden" name="num_order" value="{{ $num_order }}">
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function success_add_qqcategory(form) {
        $("#app-modal #myModal").modal('hide');
        window.location = "";
    }

</script>