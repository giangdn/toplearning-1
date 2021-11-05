<div class="container lst">
    @if(count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Sorry!</strong> There were more problems with your HTML input.<br><br>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <form method="post" action="{{ route('module.offline.uploadfile') }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <a href="javascript:void(0)" class="btn btn-info" id="select-file-manager">{{ trans('backend.choose_file') }}</a>
        <div id="file-manager-review"></div>
        <input name="filenames" id="file-manager-select" type="text" class="d-none" value="">
        <input type="hidden" name="course_id" value="{{ $model->id }}">

        @if($model->lock_course == 0)
        <button type="submit" class="btn btn-success" style="margin-top:10px">{{ trans('backend.save') }}</button>
        @endif
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btn-success").click(function () {
            var lsthmtl = $(".clone").html();
            $(".increment").after(lsthmtl);
        });
        $("body").on("click", ".btn-danger", function () {
            $(this).parents(".hdtuto control-group lst").remove();
        });

        $("#select-file-manager").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'file'}, function (url, path) {
                var path2 =  path.split("/");
                $("#file-manager-review").html(path2[path2.length - 1]);
                $("#file-manager-select").val(path);
            });
        });
    });
</script>
