<form method="POST" action="{{ route('module.libraries.video.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="type" value="4">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['libraries-video-create', 'libraries-video-edit'])
                    <a style="cursor: pointer" id="add_more_video" onclick="addMoreVideos()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> &nbsp;Thêm video</a>
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcanany
                <a href="{{ route('module.libraries.video') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">{{ trans('backend.video_name') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="category_id">{{ trans('backend.video_category') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <select name="category_id" id="category_id" class="form-control select2"
                            data-placeholder="--{{ trans('backend.choose_video_category') }}--" required>
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $model-> category_id == $category->id ? 'selected' : '' }} >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name_author">Tên tác giả <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name_author" type="text" class="form-control" placeholder="-- Tên tác giả --" value="{{ $model->name_author }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.picture')}} ({{trans('backend.size')}}: 350x500)</label>
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
            <div class="form-group row" id="choose_video">
                <div class="col-sm-3 control-label">
                    <label>{{trans("backend.choose_file")}}</label>
                </div>
                <div class="col-md-9">
                    <div>
                        <a href="javascript:void(0)" id="select-form-review">{{trans("backend.choose_file")}}</a>
                        <div id="form-review">
                            @if($model->attachment)
                                {{ basename($model->attachment) }}
                            @endif
                        </div>
                        <input name="attachment" id="item-select" type="text" class="d-none" value="{{ $model->attachment }}">
                    </div>
                </div>
            </div>
            <div id="new_video">
                @if (isset($videos))
                    @foreach($videos as $video)
                    <div class="form-group row" id="name-video-{{$video->id}}">
                        <div class="col-sm-3 control-label">
                            <label for="name_videos">{{ trans('backend.video_name') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input name="name_videos[]" type="text" class="form-control" value="{{$video->name_video}}" required>
                        </div>
                    </div>
                    <div class="form-group row" id="{{$video->id}}">
                        <div class="col-sm-3 control-label">
                            <label></label>
                        </div>
                        <div class="col-md-7">
                            <div>
                                <a href="javascript:void(0)" onclick="chooseVideos({{$video->id}})" id="select-form-reviews-{{$video->id}}">{{trans("backend.choose_file")}}</a>
                                <div id="form-reviews-{{$video->id}}">
                                    {{ basename($video->attachment) }}
                                </div>
                                <input name="attachments[]" id="item-selects-{{$video->id}}" type="text" class="d-none" value="{{ $video->attachment }}">
                            </div>
                        </div>
                        <div class="col-sm-2 control-label">
                            <a style="cursor: pointer" id="close_add_videos" onclick="closeAddVideos({{$video->id}})" class="btn btn-primary">Xóa</a>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">{{trans('backend.status')}}</label>
                </div>
                <div class="col-sm-6">
                    <div class="radio">
                        <label><input type="radio" id="status" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}</label>
                        <label><input type="radio" id="status" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">{{trans('backend.description')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <textarea name="description" id="description"  placeholder="{{trans('backend.description')}}" class="form-control" value="">{!! $model->description  !!} </textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#select-form-review").on('click', function () {
        open_filemanager({type: 'file'}, function (url, path, name) {
            $("#form-review").html(name);
            $("#item-select").val(path);
        });
    });
    var clicks = 0
    function addMoreVideos() {
        clicks += 1;
        $('#new_video').append(`<div class="form-group row" id="name-video-`+clicks+`">
                                    <div class="col-sm-3 control-label">
                                        <label for="name_videos">{{ trans('backend.video_name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name_videos[]" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="form-group row" id="`+clicks+`">
                                    <div class="col-sm-3 control-label">
                                    </div>
                                    <div class="col-md-7">
                                        <div>
                                            <a href="javascript:void(0)" onclick="chooseVideo(`+clicks+`)" 
                                                id="select-form-review-`+clicks+`">
                                                {{trans("backend.choose_file")}}
                                            </a>
                                            <div id="form-review-`+clicks+`">
                                            </div>
                                            <input name="attachments[]" id="item-select-`+clicks+`" type="text" class="d-none" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 control-label">
                                        <a style="cursor: pointer" id="close_add_videos" onclick="closeAddVideos(`+clicks+`)" class="btn btn-primary">Xóa</a>
                                    </div>
                                </div>`);
    }
    function chooseVideo(id) {
        open_filemanager({type: 'file'}, function (url, path, name) {
            $("#form-review-"+id).html(name);
            $("#item-select-"+id).val(path);
        });
    }
    function chooseVideos(id) {
        open_filemanager({type: 'file'}, function (url, path, name) {
            $("#form-reviews-"+id).html(name);
            $("#item-selects-"+id).val(path);
        });
    }
    function closeAddVideos(id) {
        $('#'+id).remove();
        $('#name-video-'+id).remove();
    }
</script>
<script type="text/javascript" src="{{ asset('styles/module/libraries/js/libraries.js') }}"></script>
<!-- <script type="text/javascript" src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script> -->
<script>
    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>