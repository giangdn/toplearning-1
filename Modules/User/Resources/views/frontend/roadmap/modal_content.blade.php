<div class="modal fade" id="modal-content" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <h5 class="modal-title text-center" id="exampleModalLabel">{{trans('backend.description')}} {{ $subject->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               {{ $roadmap->content }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>
