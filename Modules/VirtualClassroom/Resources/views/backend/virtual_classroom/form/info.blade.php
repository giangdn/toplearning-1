<form method="post" action="{{ route('module.virtualclassroom.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.class_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.class_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.time')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span>
                        <input name="start_time" type="text" class="timepicker form-control d-inline-block w-5" autocomplete="off" value="{{ get_date($model->start_date, 'H:i') }}">
                        <input name="start_date" type="text" placeholder="{{trans('backend.choose_start_date')}}" class="datepicker form-control d-inline-block w-25" autocomplete="off" value="{{ get_date($model->start_date, 'd/m/Y') }}">
                    </span>

                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>

                    <span>
                        <input name="end_time" type="text" class="timepicker form-control d-inline-block w-5" autocomplete="off" value="{{ get_date($model->end_date, 'H:i') }}">
                        <input name="end_date" type="text" placeholder='{{trans("backend.choose_end_date")}}' class="datepicker form-control d-inline-block w-25" autocomplete="off" value="{{ get_date($model->end_date, 'd/m/Y') }}">
                    </span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.note') }}</label>
                </div>
                <div class="col-md-9">
                    <textarea name="content" id="content" class="form-control">{!! $model->content  !!}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row row-acts-btn">
                <div class="col-sm-12">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        <a href="{{ route('module.virtualclassroom.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    </script>
</form>
