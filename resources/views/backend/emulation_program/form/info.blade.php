<form method="POST" action="{{ route('backend.emulation_program.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['emulation-program-edit', 'emulation-program-create'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcanany
                    <a href="{{ route('backend.emulation_program') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="type" value="1">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">Mã chương trình <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">Tên chương trình <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.time')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span>
                        <input name="time_start" 
                            type="text" class="datepicker form-control d-inline-block w-25"
                            placeholder="{{trans('backend.choose_start_date')}}" 
                            autocomplete="off" value="{{ get_date($model->time_start) }}">
                    </span>
                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span>
                        <input name="time_end" 
                            type="text" class="datepicker form-control d-inline-block w-25"
                            placeholder='{{trans("backend.choose_end_date")}}' 
                            autocomplete="off" value="{{ get_date($model->time_end) }}">
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.picture')}} ({{trans('backend.size')}}: 300x160)</label>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                    <div id="image-review" >
                        @if($model->image)
                            <img class="w-100" src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>
                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="isopen" class="hastip" data-toggle="tooltip" data-placement="right">{{trans('backend.status')}}</label>
                </div>
                <div class="col-sm-6">
                    <div class="radio">
                        <label><input type="radio" id="isopen" name="isopen" value="1" {{ $model->isopen == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}</label>
                        <label><input type="radio" id="isopen" name="isopen" value="0" {{ $model->isopen == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">{{trans('backend.description')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <textarea name="description" id="description" placeholder="{{trans('backend.description')}}" class="form-control" value="">{!! $model->description !!}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img class="w-100" src="'+ path +'">');
            $("#image-select").val(path);
        });
    });
</script>