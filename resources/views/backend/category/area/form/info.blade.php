<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Mã {{ data_locale($name->name, $name->name_en) }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Tên {{ data_locale($name->name, $name->name_en) }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
            </div>
        </div>

        @if($level != 1)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="parent_id">Địa điểm quản lý</label>
                </div>
                <div class="col-md-6">
                    <select name="parent_id" class="form-control select2" data-placeholder="-- Cấp cha --">
                        <option value=""></option>
                        @foreach($parent_area as $item)
                            <option value="{{ $item['id'] }}" {{ isset($parent) && $parent->id == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if($level == 2)
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="unit_id">{{trans('backend.department')}}</label>
            </div>
            <div class="col-md-6">
                <select name="unit_id" id="unit_id" class="form-control load-unit" data-placeholder="-- {{trans('backend.department')}} --" data-level="3">
                    @if(isset($unit))
                        <option value="{{ $unit->id }}">{{ $unit->code .' - '. $unit->name }}</option>
                    @endif
                </select>
            </div>
        </div>
        @endif

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('backend.status')}} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <label class="radio-inline"><input type="radio" required name="status" value="1" @if($model->status == 1) checked @endif>{{ trans('backend.enable') }}</label>
                <label class="radio-inline"><input type="radio" required name="status" value="0" @if($model->status == 0) checked @endif>{{ trans('backend.disable') }}
            </div>
        </div>
    </div>

</div>
