<div role="main">
    <form method="post" action="{{ route('module.online.save_tutorial') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $model->id }}">
        <input type="hidden" name="flag" id="flag" value="{{ $model->id ? 1 : 0 }}">
        <input type="hidden" name="content_of_id" id="content_of_id" value="{{ $model->tutorial }}">
        <div class="form-group row">
            <div class="col-12">
                @if($permission_save && $model->lock_course == 0)
                    <button style="float: right" type="submit" class="btn btn-primary mb-2" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endif
            </div>
            <div class="col-sm-3 control-label">
                <label for="type">Hướng dẫn học</label>
                <select name="type_tutorial" id="type_tutorial" class="">
                    <option value="1" {{ $model->type_tutorial == 1 ? 'selected' : ''}}>Bài viết</option>
                    <option value="2" {{ $model->type_tutorial == 2 ? 'selected' : ''}}>File</option>
                </select>
            </div>
            <div class="col-md-9" id="select_news">
                <textarea name="content_tutorial" id="content_tutorial" placeholder="{{ trans('backend.content') }}" class="form-control">{{$model->tutorial}}</textarea>
            </div>
            <div class="col-md-9" id="select_files">
                <input type="file"
                    id="files_tutorial"
                    name="files_tutorial[]"
                    class="myfrm form-control"
                    multiple
                    style="height:auto;">
                @if ($model->type_tutorial == 2)
                    @php
                        $files = json_decode($model->tutorial);
                    @endphp
                    @foreach ($files as $item)
                        <div>
                            <span>{{ basename($item) }}</span><br>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </form>
</div>
<script>
    CKEDITOR.replace('content_tutorial', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
    $(document).ready(function() {
        $('#select_files').hide();
        var type = `<?php if (isset($model->type_tutorial)) {
                        echo $model->type_tutorial;
                    } else {
                        echo '';
                    } ?>`;

        if (type !== '' && type == 2) {
            $('#select_files').show();
            $('#select_news').hide();
        } else if (type !== '' && type == 1) {
            $('#select_files').hide();
            $('#select_news').show();
        }
        $('#type_tutorial').on('change',function() {
            var type = $('#type_tutorial').val();
            if ( type == 1 ) {
                $('#select_news').show();
                $('#select_files').hide();
            } else {
                $('#select_files').show();
                $('#select_news').hide();
            }
        });
        $('#select_files').on('change',function() {
            var get_value = $('#pictures').val();
            $('#flag').val('0');
        });
    })
</script>
