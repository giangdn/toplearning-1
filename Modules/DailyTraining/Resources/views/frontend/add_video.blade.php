@extends('layouts.app')

@section('page_title', 'Video')

@section('content')
    <div class="container add_video_daily_training">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content forum-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i>
                        <a href="{{ route('module.daily_training.frontend') }}">@lang('backend.training_video')</a>
                        <i class="uil uil-angle-right"></i>
                        <span class="font-weight-bold">{{ trans('app.add_video') }}</span>
                    </h2>
                </div>
            </div>
        </div>
<p></p>
        <form action="{{ route('module.daily_training.frontend.save_video') }}" method="post" enctype="multipart/form-data" class="form-validate form-ajax">
            @csrf
            <div class="form-group">
                <select name="category_id" class="form-control select2">
                    <option value="">{{ data_locale('Danh mục', 'Category') }}</option>
                    @foreach($categories as $key => $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="{{ data_locale('Nhập tên', 'Enter name') }}" required>
            </div>
            <div class="form-group">
                <input type="text" name="hashtask" class="form-control" placeholder="hashtag" required>
            </div>
            <div class="form-group">
                <a href="javascript:void(0)" class="btn btn-primary rounded-0" id="upload-button">
                    {{ trans('lfm.message-choose') }}
                </a>
                <span id="file-name"></span>
                <input type="hidden" name="video" value="" id="video">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-danger" disabled id="save-video">{{ trans('app.save') }}</button>
            </div>
        </form>

        <div class="modal-body" hidden>
            <form action="{{ route('module.daily_training.frontend.upload_video') }}" role='form' id='uploadForm' name='uploadForm' method='post' enctype='multipart/form-data' class="dropzone">
                <div class="form-group" id="attachment">
                    <div class="controls text-center">
                        <div class="text-center">
                            <a href="javascript:void(0)" class="btn btn-primary rounded-0"><i class="fa
                                fa-cloud-upload"></i> {{ trans('lfm.message-choose') }}
                            </a>
                        </div>
                    </div>
                </div>
                <input type='hidden' name='_token' value='{{ csrf_token() }}'>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        Dropzone.options.uploadForm = {
            paramName: "upload",
            uploadMultiple: false,
            parallelUploads: 5,
            clickable: '#upload-button',
            timeout: 0,
            dictDefaultMessage: 'Hoặc kéo thả tệp vào đây',
            init: function () {
                var _this = this; // For the closure

                this.on("sending", function(files) {
                    $('#file-name').html('Đang xử lý...');
                });

                this.on('success', function (file, response) {
                    var path = JSON.parse(file.xhr.response).path;
                    var path2 =  path.split("/");

                    $('#video').val(path);
                    $('#file-name').html(path2[path2.length - 1]);
                    $('#save-video').prop('disabled', false);
                });

                this.on("addedfiles", function(files) {
                    console.log(files.length + ' files added');
                });
            },
            chunking: true,
            forceChunking: true,
            chunkSize: 5242880, //cũ 1048576,
            retryChunks: true,   // retry chunks on failure
            retryChunksLimit: 3,
            acceptedFiles: "{{ implode(',', $mimetypes) }}",
            maxFilesize: parseInt('{{ $max_file_size * 1024 * 1024  }}'),
        }
    </script>
@endsection
