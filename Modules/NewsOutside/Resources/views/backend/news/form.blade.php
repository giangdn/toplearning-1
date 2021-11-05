@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        Tin tức chung
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.news_outside.manager') }}">{{ trans('backend.post_manager') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="POST" action="{{ route('module.news_outside.save') }}" class="form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @can('news-outside-list-edit')
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcan
                    <a href="{{ route('module.news_outside.manager') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="title">{{trans("backend.enter_title_thread")}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="title" type="text" class="form-control" value="{{ $model->title }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="category_id">{{trans("backend.category_post")}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="category_id" id="category_id" class="form-control select2"  data-placeholder="--{{trans('backend.choose_category_post_outside')}}--" required>
                                        <option value=""></option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }} " {{ $model-> category_id == $category->id ? 'selected' : '' }} >{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{trans('backend.picture')}} (300 x 160)</label>
                                </div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                    <div id="image-review" >
                                        @if($model->image)
                                            <img class="w-100" src="{{ image_file($model->image) }}" alt="">
                                        @endif
                                    </div>
                                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image ? $model->image : '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">{{trans('backend.status')}}</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="radio" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}
                                    <input type="radio" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="hot_public" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">Tin Nổi bật chung</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="radio" name="hot_public" value="0" {{ $model->hot_public == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}
                                    <input type="radio" name="hot_public" value="1" {{ $model->hot_public == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="hot" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">Tin Hot</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="radio" name="hot" value="0" {{ $model->hot == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}
                                    <input type="radio" name="hot" value="1" {{ $model->hot == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="number_setup">Số ngày hiển thị icon bắt đầu từ ngày tạo </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" name="number_setup"  placeholder="-- Nhập Số ngày hiển thị icon --" class="form-control" value="{{ $model->number_setup }}">
                                </div>
                            </div>
                            
                            {{-- Mô tả --}}
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="description">Mô tả <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="description"  placeholder="Nhập mô tả" class="form-control" required>{{ $model->description }}</textarea>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="content">Nội dung <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="content" id="content" placeholder="Nhập mô tả" class="form-control">{{ $model->content }}</textarea>
                                </div>
                            </div> --}}

                            {{-- Thể loại --}}
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="type">Thể loại <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="" required>
                                        <option value="1" {{ $model->type == 1 ? 'selected' : ''}} >Bài viết</option>
                                        <option value="2" {{ $model->type == 2 ? 'selected' : ''}}>Video</option>
                                        <option value="3" {{ $model->type == 3 ? 'selected' : ''}}>Hình ảnh</option>
                                    </select>
                                </div>
                                <div class="col-md-9" id="select_news">
                                    <textarea name="content" id="content" placeholder="{{ trans('backend.content') }}" class="form-control">{{ $model->content }}</textarea>
                                </div>
                                <div class="col-md-9" id="select_video">
                                    <a href="javascript:void(0)" class="myfrm form-control" id="select-form-review">{{trans("backend.choose_file")}}</a>
                                    <div id="form-review">
                                        @if($model->content)
                                            {{ basename($model->content) }}
                                        @endif
                                    </div>
                                    <input name="video" id="video" type="text" class="d-none" value="{{ $model->content }}">
                                    {{-- <input type="file" name="video" id="video" class="myfrm form-control" style="height:auto;"> --}}
                                    @if ($model->type == 2)
                                        <div class="mt-2">
                                            <video width="100%" height="auto" controls>
                                                <source src="{{ image_file($model->content) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9" id="select_pictures">
                                    <input type="file"
                                        id="pictures"
                                        name="pictures[]"
                                        class="myfrm form-control"
                                        multiple
                                        style="height:auto;">
                                    <div class="row all_pictures">
                                    @if ($model->type == 3)
                                        @php
                                            $pictures = json_decode($model->content);
                                        @endphp
                                        @foreach ($pictures as $item)
                                            <div class="col-4 mt-2">
                                                <img src="{{ image_file($item) }}" alt="" class="w-100" style="height: 150px">
                                            </div>
                                        @endforeach
                                    @endif
                                    </div>
                                </div>
                                <input type="hidden" name="flag" id="flag" value="{{ $model->id ? 1 : 0 }}">
                                <input type="hidden" name="content_of_id" id="content_of_id" value="{{ $model->content }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });

    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img class="w-100" src="' + path + '">');
            $("#image-select").val(path);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#select_video').hide();
        $('#select_pictures').hide();
        var type = `<?php if (isset($model->type)) {
                        echo $model->type  ;
                    } else {
                        echo '';
                    } ?>`;
        if (type !== '' && type == 3) {
            $('#select_video').hide();
            $('#select_pictures').show();
            $('#select_news').hide();
        } else if (type !== '' && type == 2) {
            $('#select_video').show();
            $('#select_pictures').hide();
            $('#select_news').hide();
        } else {
            $('#select_video').hide();
            $('#select_pictures').hide();
            $('#select_news').show();
        }
        if (type !== '') {
            $('#type option').attr('disabled',true);
            var op = document.getElementById("type").getElementsByTagName("option");
            for (var i = 0; i < op.length; i++) {
                var get_value_op = op[i].value;
                if (op[i].value == type) {
                    op[i].disabled = false;
                }
            }
        }
        $('#type').on('change',function() {
            var type = $('#type').val();
            if ( type == 1 ) {
                $('#select_news').show();
                $('#select_video').hide();
                $('#select_pictures').hide();
            } else if ( type == 2 ) {
                $('#select_video').show();
                $('#select_news').hide();
                $('#select_pictures').hide();
            } else {
                $('#select_pictures').show();
                $('#select_news').hide();
                $('#select_video').hide();
            }
        })
        $('#select_pictures').on('change',function() {
            var get_value = $('#pictures').val();
            $('#flag').val('0');
        })
        $('#select-form-review').on('change',function() {
            var get_value = $('#video').val();
            $('#flag').val('0');
        })
    })
    $("#select-form-review").on('click', function () {
        open_filemanager({type: 'file'}, function (url, path, name) {
            $("#form-review").html(name);
            $("#video").val(path);
        });
    });
</script>
@stop
