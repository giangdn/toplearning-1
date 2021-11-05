<form method="post" action="{{ route('backend.app_mobile.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model_apple ? $model_apple->id : '' }}">
    <input type="hidden" name="type" value="2">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="image">{{trans('backend.picture')}} <span class="text-danger">*</span> <br>({{trans('backend.size')}}: 132x42)</label>
        </div>

        <div class="col-sm-6">
            <a href="javascript:void(0)" id="select-image-apple">{{trans('backend.choose_picture')}}</a>
            <div id="image-review-apple">@if($model_apple) <img src="{{ image_file($model_apple->image) }}" class="w-25"> @endif</div>
            <input type="hidden" class="form-control" name="image" id="image-select-apple" value="{{ $model_apple ? $model_apple->image : '' }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="link">Link</label>
        </div>
        <div class="col-sm-6">
            <input name="link" class="form-control" value="{{ $model_apple ? $model_apple->link : '' }}">
        </div>
    </div>
</form>
<script type="text/javascript">
    $("#select-image-apple").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review-apple").html('<img src="' + path + '" class="w-25">');
            $("#image-select-apple").val(path);
        });
    });
</script>
