@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.add_video'))

@section('content')
    <div class="container mt-1">
        <form action="{{ route('module.daily_training.frontend.save_video') }}" method="post" enctype="multipart/form-data" class="form-validate form-ajax">
            @csrf
            <div class="form-group input-container">
                <input type="text" id="category_name" value="" class="form-control" placeholder="{{ data_locale('Danh mục', 'Category') }}" data-toggle="modal" data-target="#category_video" readonly> <i class="material-icons" data-toggle="modal" data-target="#category_video" style="position: absolute; right: 20px; top: 60px;"> add</i>
                <input type="hidden" name="category_id" id="category_id" value="">
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
@endsection
@section('modal')
    <div class="modal fade" id="category_video" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header theme-header border-0">
                    <h6 class="">{{ data_locale('Danh mục', 'Category') }}</h6>
                </div>
                <div class="modal-body p-0" style="border-top: 1px solid #dee2e6;">
                    <ul class="list-group list-group-flush" @if(count($categories) > 5) style="height: 180px; overflow-y: auto;" @endif>
                        @foreach($categories as $key => $category)
                        <li class="list-group-item" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                            {{ $category->name }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 text-center align-self-center">
                                <button type="button" class="btn btn-primary" data-dismiss="modal"{{-- id="add-cate"--}}>OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var category_id = '';
        var category_name = '';
        $('#category_video').on('click', '.list-group-item', function () {
            category_id = $(this).data('id');
            category_name = $(this).data('name');
            $('#category_video .list-group-item').find('.icon').remove();
            $(this).append('<span class="icon float-right"><i class="material-icons text-primary">check</i></span>');

            $('#category_id').val(category_id);
            $('#category_name').val(category_name);
        });

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
            chunkSize: 1048576,
            retryChunks: true,   // retry chunks on failure
            retryChunksLimit: 3,
            acceptedFiles: "{{ implode(',', $mimetypes) }}",
            maxFilesize: parseInt('{{ $max_file_size * 1024 * 1024  }}'),
        }
    </script>
@endsection
