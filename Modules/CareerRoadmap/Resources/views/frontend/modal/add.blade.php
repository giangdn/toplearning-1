<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <form action="{{ route('module.career_roadmap.frontend.save') }}" method="post" class="form-ajax">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">@lang('career.add_roadmap')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roadmap_name">@lang('career.roadmap_name')</label>
                        <input type="text" name="name" id="roadmap_name" class="form-control" autocomplete="off">
                    </div>

                    <input type="hidden" name="id" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('app.save')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> @lang('app.close')</button>
                </div>
            </div>
        </div>
    </form>
</div>
