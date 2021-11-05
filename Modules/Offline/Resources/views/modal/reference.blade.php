<div class="modal fade" id="modal-reference" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('module.offline.save_reference', ['id' => $course_id]) }}" method="post" class="form-ajax" data-success="form_reference">
            <input type="hidden" name="regid" value="{{ $regid }}">
            <input type="hidden" name="schedule" value="{{ $schedule }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('backend.permission_form') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <a href="javascript:void(0)" id="select-reference">Chọn đơn xin phép</a>
                        <div id="reference-review">
                            @if($model != null)
                                {{ basename($model->reference) }}
                            @endif
                        </div>
                        <input name="reference" id="reference-select" type="text" class="d-none" value="{{ $model != null ? $model->reference : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $("#select-reference").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            $("#reference-review").html(path2[3]);
            $("#reference-select").val(path);
        });
    });
</script>

