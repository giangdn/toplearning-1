<form method="post" action="{{ route('backend.slider.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model_app->id }}">
    <input type="hidden" name="type" value="2">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['banner-create', 'banner-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcanany
                <a href="{{ route('backend.slider') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="image">{{trans('backend.picture')}} <span class="text-danger">*</span> <br>({{trans('backend.size')}}: 500x300)</label>
        </div>

        <div class="col-sm-6">
            <a href="javascript:void(0)" id="select-image-mobile">{{trans('backend.choose_picture')}}</a>
            <div id="image-review-mobile">@if($model_app->image) <img src="{{ image_file($model_app->image) }}" class="w-25"> @endif</div>
            <input type="hidden" class="form-control" name="image" id="image-select-mobile" value="{{ $model_app->image }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="description">{{trans('backend.description')}}</label>
        </div>
        <div class="col-sm-6">
            <textarea name="description" id="description" class="form-control" rows="4">{{ $model_app->description }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="location">{{ trans('backend.object') }} </label>
        </div>
        <div class="col-sm-6">
            <select name="location" id="location" class="form-control select2" data-placeholder="-- {{ trans('backend.object') }} --">
                <option value=""></option>
                @foreach($unit as $item)
                    <option value="{{ $item->id }}" {{ $model_app->location == $item->id ? 'selected' : '' }}> {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="display_order">{{trans('backend.order')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-6">
            <input type="text" name="display_order" id="display_order" class="form-control is-number"
                   value="{{ if_empty($model_app->display_order, 1) }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="status">{{trans('backend.status')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-6">
            <select name="status" id="status" class="form-control select2-default" data-placeholder="-- {{trans('backend.status')}} --" required>

                <option value="1" {{ $model_app->status == 1 ? 'selected' : '' }}>{{trans("backend.enable")}}</option>
                <option value="0" {{ (!is_null($model_app->status) && $model_app->status == 0) ? 'selected' : '' }}>{{trans("backend.disable")}}</option>

            </select>
        </div>
    </div>

</form>

<script type="text/javascript">
    $("#select-image-mobile").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review-mobile").html('<img src="' + path + '" class="w-25">');
            $("#image-select-mobile").val(path);
        });
    });
</script>
