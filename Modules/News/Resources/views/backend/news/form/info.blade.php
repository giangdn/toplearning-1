@if(count($errors) > 0)
<div class="alert alert-danger">
    <strong>Xin lỗi!</strong> Có lỗi xảy ra khi lưu.<br><br>
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('module.news.save') }}" class="form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['news-list-create', 'news-list-edit'])
                    @if (isset($model->id))
                        <a href="{{ route('module.news.preview_new',['id'=>$model->id]) }}" class="btn btn-warning"><i class="fa fa-eye"></i> Xem trước nội dung</a>
                    @endif
                @endcan
                @canany(['news-list-create', 'news-list-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcan
                <a href="{{ route('module.news.manager') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
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
                    <select name="category_id" id="category_id" class="form-control select2"  data-placeholder="--{{trans('backend.choose_category_post')}}--" required>
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

            {{-- TIN NỔI BẬT CHUNG --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="hot_public" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">Tin nổi bật chung</label>
                </div>
                <div class="col-sm-9">
                    <input type="radio" name="hot_public" value="0" {{ $model->hot_public == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}
                    <input type="radio" name="hot_public" value="1" {{ $model->hot_public == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">{{trans('backend.hot_news')}}</label>
                </div>
                <div class="col-sm-9">
                    <input type="radio" name="hot" value="0" {{ $model->hot == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}
                    <input type="radio" name="hot" value="1" {{ $model->hot == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('backend.enable')}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="number_setup">Số ngày hiển thị icon bắt đầu từ ngày tạo</label>
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
                    <textarea name="description" placeholder="Nhập mô tả" class="form-control" required>{{ $model->description }}</textarea>
                </div>
            </div>
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

            {{--Link file--}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">Thêm Link/File</label>
                    <a class="btn btn-info" id="btn-add-link"><i class="fa fa-plus-circle"></i></a>
                </div>
                <div class="col-md-9" id="list-link">
                    @if(isset($news_link))
                        @foreach($news_link as $key => $link)
                            <div class="item-link mt-2" data-link_key="{{ $key }}">
                                <input name="link_id[]" type="hidden" value="{{ $link->id }}">
                                <div class="input-group row">
                                    <div class="col-2 pr-0"></div>
                                    <div class="col-9 pl-0">
                                        <input type="text" name="news_link_title[]" class="form-control" placeholder="-- Tiêu đề --" value="{{ $link->title }}">
                                    </div>
                                    <div class="col-1">
                                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-link" data-link_id="{{ $link->id }}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 pr-0">
                                        <a href="javascript:void(0)" class="select-link btn" data-link_key="{{ $key }}">Chọn file</a>
                                    </div>
                                    <div class="col-9 pl-0">
                                        <input name="news_link_url[]" id="link-select{{ $key }}" type="text" class="form-control" value="{{ $link->link }}" placeholder="-- Link/File --">
                                        <input name="news_link_type[]" id="link-type{{ $key }}" type="hidden" class="form-control" value="{{ $link->type }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

<template id="link-template">
    <div class="item-link mt-2" data-link_key="{link_key}">
        <input name="link_id[]" type="hidden" value="">
        <div class="input-group row">
            <div class="col-2 pr-0"></div>
            <div class="col-9 pl-0">
                <input type="text" name="news_link_title[]" class="form-control" placeholder="-- Tiêu đề --">
            </div>
            <div class="col-1">
                <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-link" data-link_id="">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
            <div class="col-2 pr-0">
                <a href="javascript:void(0)" class="select-link btn" data-link_key="{link_key}">Chọn file</a>
            </div>
            <div class="col-9 pl-0">
                <input name="news_link_url[]" id="link-select{link_key}" type="text" class="form-control" value="" placeholder="-- Link/File --">
                <input name="news_link_type[]" id="link-type{link_key}" type="hidden" class="form-control" value="link">
            </div>
        </div>
    </div>
</template>

<script>
    var link_template = document.getElementById('link-template').innerHTML;
    var remove_item_link = '{{ route('module.news.remove_item_new_link') }}';

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

    $('#btn-add-link').on('click', function() {
        var link_key = parseInt($('.item-link').last().data('link_key'), 10) + 1;
        if (isNaN(link_key)) {
            link_key = 0;
        }
        let category = replacement_template(link_template, {
            'link_key' : link_key
        });

        $('#list-link').append(category);
    });

    $("#list-link").on('click', '.select-link', function () {
        var link_key = $(this).data('link_key');

        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'file'}, function (url, path) {
            var path2 =  path.split("/");
            $("#list-link #link-select"+link_key).val(path);
            $("#list-link #link-type"+link_key).val('file');
        });
    });

    $('#list-link').on('click', '#del-link', function() {
        var link_id = parseInt($(this).data('link_id'));
        if (isNaN(link_id)) {
            $(this).parents('.item-link').remove();
        }else{
            $(this).parents('.item-link').remove();

            $.ajax({
                type: 'POST',
                url : remove_item_link,
                data : {
                    link_id : link_id,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        }
    });

    function replacement_template(template, data){
        return template.replace(
            /{(\w*)}/g,
            function( m, key ){
                return data.hasOwnProperty( key ) ? data[ key ] : "";
            }
        );
    }
</script>
<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>

<script type="text/javascript" src="{{ asset('styles/module/news/js/news.js') }}"></script>
