<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="category_id">Danh mục năng lực <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <select name="category_id" id="category_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_capacity_category')}}--" required>
                    <option value=""></option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $model->category_id == $category->id ? 'selected': '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="category_group_id">Nhóm danh mục năng lực</label>
            </div>
            <div class="col-md-6">
                <select name="category_group_id" id="category_group_id" class="form-control select2" data-placeholder="--Chọn nhóm danh mục năng lực--">
                    <option value=""></option>
                    @if(isset($groups))
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $model->category_group_id == $group->id ? 'selected': '' }}>{{ $group->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="code">Ký hiệu <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="name">Tên năng lực <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="description">Diễn giải</label>
            </div>
            <div class="col-md-6">
                <textarea name="description" type="text" class="form-control" value="">{{ $model->description }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="group_name">Nhóm <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <select name="group_id" id="group_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_group')}}--" required>
                    <option value=""></option>
                    @foreach($level as $item)
                        <option value="{{ $item->id }}" {{ $model->group_id == $item->id ? 'selected': '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
