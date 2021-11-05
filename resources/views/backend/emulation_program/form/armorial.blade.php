<form method="POST" action="{{ route('backend.emulation_program.save_armorial',['id' => $model->id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right mb-3">
            <div class="btn-group act-btns">
                @can('emulation-program-create-armorial')
                    <button type="button" class="btn btn-primary" onclick="addMoreArmorial()"><i class="fas fa-plus-circle"></i> &nbsp; Thêm mới huy hiệu</button>
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcan
                <a href="{{ route('backend.emulation_program') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row" id="armorial_id">
        @if (!$amorial_emulations->isEmpty())
            @foreach ($amorial_emulations as $key => $amorial_emulation)
                <div class="col-md-12"  id="id_armorial_{{$amorial_emulation->id}}">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="name">Tên Huy hiệu <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input name="name_armorials[]" type="text" class="form-control" value="{{$amorial_emulation->name}}" required>
                        </div>
                        @if ($key == 1)
                        <div class="col-sm-2 control-label">
                            <a style="cursor: pointer" onclick="closeAddArmorialId({{$amorial_emulation->id}})" class="btn btn-primary">Xóa</a>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="code_armorials">Mã Huy hiệu <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input name="code_armorials[]" type="text" class="form-control" value="{{$amorial_emulation->code}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{trans('backend.picture')}} ({{trans('backend.size')}}: 200x200)</label>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)" onclick="chooseImageArmorialId({{$amorial_emulation->id}})">{{trans('backend.choose_picture')}}</a>
                            <div id="image-armorial-id-{{$amorial_emulation->id}}" >
                                @if($amorial_emulation->images)
                                    <img src="{{ image_file($amorial_emulation->images) }}" alt="" width="100%">
                                @endif
                            </div>
                            <input name="image_armorials[]" id="image-select-armorial-id-{{$amorial_emulation->id}}" type="text" class="d-none" value="{{$amorial_emulation->images}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="score">Điểm <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <input name="min_scores[]" type="text" class="form-control w-25 d-inline-block" placeholder="Từ điểm" autocomplete="off" value="{{$amorial_emulation->min_score}}">
                            <input name="max_scores[]" type="text" class="form-control w-25 d-inline-block" placeholder="Đến điểm" autocomplete="off" value="{{$amorial_emulation->max_score}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="description_armorial">Mô tả <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <textarea name="description_armorials[]" class="form-control w-100 d-inline-block" placeholder="Mô tả">{{$amorial_emulation->description}}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="name">Tên Huy hiệu <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input name="name_armorials[]" type="text" class="form-control" value="" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="code_armorials">Mã Huy hiệu <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input name="code_armorials[]" type="text" class="form-control" value="" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{trans('backend.picture')}} ({{trans('backend.size')}}: 200x200)</label>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:void(0)" id="select-image-amorial">{{trans('backend.choose_picture')}}</a>
                        <div id="image-armorial" >
                        </div>
                        <input name="image_armorials[]" id="image-select-armorial" type="text" class="d-none" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="score">Điểm <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-9">
                        <input name="min_scores[]" type="text" class="form-control w-25 d-inline-block" placeholder="Từ điểm" autocomplete="off" value="">
                        <input name="max_scores[]" type="text" class="form-control w-25 d-inline-block" placeholder="Đến điểm" autocomplete="off" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="description_armorial">Mô tả <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="description_armorials[]" class="form-control w-100 d-inline-block" placeholder="Mô tả"></textarea>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</form> 
<script>
    $("#select-image-amorial").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-armorial").html('<img width="200px" src="'+ path +'">');
            $("#image-select-armorial").val(path);
        });
    });
    var clicks = 0
    function addMoreArmorial() {
        clicks += 1;
        $('#armorial_id').append(`<div class="col-md-12 mt-2" id="id_`+clicks+`">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="name">Tên Huy hiệu <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="name_armorials[]" type="text" class="form-control" value="" required>
                                        </div>
                                        <div class="col-sm-2 control-label">
                                            <a style="cursor: pointer" onclick="closeAddArmorial(`+clicks+`)" class="btn btn-primary">Xóa</a>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="code_armorials">Mã Huy hiệu <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="code_armorials[]" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{trans('backend.picture')}} ({{trans('backend.size')}}: 200x200)</label>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)" onclick="chooseImageArmorial(`+clicks+`)">{{trans('backend.choose_picture')}}</a>
                                            <div id="image-armorial-`+clicks+`" >
                                            </div>
                                            <input name="image_armorials[]" id="image-select-armorial-`+clicks+`" type="text" class="d-none" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label"></div>
                                        <div class="col-md-9">
                                            <input name="min_scores[]" type="text" class="form-control w-25 d-inline-block" placeholder="Từ điểm" autocomplete="off" value="">
                                            <input name="max_scores[]" type="text" class="form-control w-25 d-inline-block" placeholder="Đến điểm" autocomplete="off" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="description_armorial">Mô tả <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="description_armorials[]" class="form-control w-100 d-inline-block" placeholder="Mô tả"></textarea>
                                        </div>
                                    </div>
                                </div>`);
    }
    function chooseImageArmorial(id) {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-armorial-"+id).html('<img width="200px" src="'+ path +'">');
            $("#image-select-armorial-"+id).val(path);
        });
    }
    function chooseImageArmorialId(id) {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-armorial-id-"+id).html('<img width="200px" src="'+ path +'">');
            $("#image-select-armorial-id-"+id).val(path);
        });
    }
    function closeAddArmorial(id) {
        $('#id_'+id).remove();
    }
    function closeAddArmorialId(id) {
        $('#id_armorial_'+id).remove();
    }
</script>