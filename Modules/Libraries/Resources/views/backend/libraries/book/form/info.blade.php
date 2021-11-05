<form method="POST" action="{{ route('module.libraries.book.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="type" value="1">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['libraries-book-create', 'libraries-book-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcanany
                    <a href="{{ route('module.libraries.book') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">{{trans('backend.book_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="phone_contact" type="text" class="form-control" value="{{ $model->phone_contact }}" required>
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
                    <label for="category_id">{{trans('backend.category_book')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <select name="category_id" id="category_id" class="form-control select2" data-placeholder="--Chọn danh mục sách--" required>
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $model-> category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
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
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right">{{trans('backend.status')}}</label>
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
                    <label for="current_number">{{trans('backend.quantity_available')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="current_number" type="text" class="form-control is-number" value="{{ $model->current_number }}" required>
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
<script type="text/javascript" src="{{ asset('styles/module/libraries/js/libraries.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>

