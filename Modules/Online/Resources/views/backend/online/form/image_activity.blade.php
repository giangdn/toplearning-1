<div id="logo">
    <div role="main">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <form class="form-horizontal form-ajax" id="form-image-activity" method="post" action="{{ route('module.online.image_activity.save',['id' => $model->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="name" value="logo">
                        <button type="button" class="image-picker btn btn-info" id="select_image_activity">
                            <i class="fa fa-folder-open m-r-5"></i> Chọn ảnh đại diện hoạt động
                        </button>
                        <br>
                        <div id="image-activity-review" class="mt-2 mb-2">
                            @if($model->image_activity)
                                <img src="{{ image_file($model->image_activity) }}" alt="" class="w-50">
                            @else
                                <div class="single-image image-holder-wrapper clearfix">
                                    <div class="image-holder placeholder">
                                        <i class="far fa-image"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <input name="image_activity" id="image-activity-select" type="text" class="d-none" value="{{ $model->image_activity }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#select_image_activity").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-activity-review").html('<img src="'+ path +'" class="w-50">');
                $("#image-activity-select").val(path);
            });
        });
    });
</script>