<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.languages.save_group') }}" method="post" class="form-ajax" data-success="success_submit">
            <input type="hidden" name="id" value="@if(isset($model->id)){{ $model->id }}@endif">

            <div class="modal-header">
                <h4 class="modal-title">@if(isset($model->name)) {{ $model->name }} @else {{trans('backend.add_new')}} @endif</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>{{ trans('backend.category_name') }}</label>
                    <input name="name" type="text" class="form-control" value="@if(isset($model->name)){{ $model->name }}@endif">
                </div>
            </div>

            <div class="modal-footer">
                @canany(['quiz-category-question-create', 'quiz-category-question-edit'])
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                @endcanany
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

