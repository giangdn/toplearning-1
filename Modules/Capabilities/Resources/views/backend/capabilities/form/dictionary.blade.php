<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home">Mức độ áp dụng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu1">Mức độ phức tạp</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu2">Phạm vi ảnh hưởng</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane container active" id="home">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="basic_apply">{{trans('backend.levels')}} 1</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="basic_apply" class="form-control" value="">{{ $dictionary ? $dictionary->basic_apply : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="medium_apply">{{trans('backend.levels')}} 2</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="medium_apply" class="form-control" value="">{{ $dictionary ? $dictionary->medium_apply : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="advanced_apply">{{trans('backend.levels')}} 3</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="advanced_apply" class="form-control" value="">{{ $dictionary ? $dictionary->advanced_apply : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="profession_apply">{{trans('backend.levels')}} 4</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="profession_apply" class="form-control" value="">{{ $dictionary ? $dictionary->profession_apply : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane container fade" id="menu1">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="basic_complex">{{trans('backend.levels')}} 1</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="basic_complex" class="form-control" value="">{{ $dictionary ? $dictionary->basic_complex : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="medium_complex">{{trans('backend.levels')}} 2</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="medium_complex" class="form-control" value="">{{ $dictionary ? $dictionary->medium_complex : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="advanced_complex">{{trans('backend.levels')}} 3</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="advanced_complex" class="form-control" value="">{{ $dictionary ? $dictionary->advanced_complex : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="profession_complex">{{trans('backend.levels')}} 4</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="profession_complex" class="form-control" value="">{{ $dictionary ? $dictionary->profession_complex : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane container fade" id="menu2">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="basic_affect">{{trans('backend.levels')}} 1</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="basic_affect" class="form-control" value="">{{ $dictionary ? $dictionary->basic_affect : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="medium_affect">{{trans('backend.levels')}} 2</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="medium_affect" class="form-control" value="">{{ $dictionary ? $dictionary->medium_affect : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="advanced_affect">{{trans('backend.levels')}} 3</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="advanced_affect" class="form-control" value="">{{ $dictionary ? $dictionary->advanced_affect : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="profession_affect">{{trans('backend.levels')}} 4</label>
                    </div>
                    <div class="col-md-6">
                        <textarea name="profession_affect" class="form-control" value="">{{ $dictionary ? $dictionary->profession_affect : '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>