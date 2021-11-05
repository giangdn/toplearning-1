<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.unit_code') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <input name="code" type="text" class="form-control" value="" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.unit') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <input name="name" type="text" class="form-control" value="" required>
            </div>
        </div>

        @if($level == 1)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Email</label>
                </div>
                <div class="col-md-7">
                    <input name="email" type="text" class="form-control" value="">
                </div>
            </div>
        @endif

        @if($level >= 1)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="parent_id">{{ trans('backend.management_unit') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <select name="parent_id" id="parent_id" class="form-control load-unit" data-placeholder="-- Cấp cha --" data-level="{{ $level - 1 }}">
                        @if(isset($parent))
                            <option value="{{ $parent->id }}">{{ $parent->code .' - '. $parent->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.unit_type') }}</label>
            </div>
            <div class="col-md-7" id="type_unit">
                <select name="type" id="type" class="form-control select2" data-placeholder="-- {{ trans('backend.unit_type') }} --">
                    <option value=""></option>
                    @foreach($type as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="all_area">
            @for($i=1;$i<=$max_area;$i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="area_id_{{ $i }}">{{ data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-7">
                        <select name="area_id" id="area_id_{{ $i }}" class="load-area" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }} --" data-level="{{ $i }}"
                                data-parent="{{ empty($area[$i-1]->id) ? '' : $area[$i-1]->id }}" data-loadchild="area_id_{{ $i+1 }}">
                            @if(isset($area[$i]))
                                <option value="{{ $area[$i]->id }}">{{ $area[$i]->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            @endfor
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.note') }} 1</label>
            </div>
            <div class="col-md-7">
                <textarea id="note1" name="note1" type="text" class="form-control" rows="5"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.note') }} 2</label>
            </div>
            <div class="col-md-7">
                <textarea id="note2" name="note2" type="text" class="form-control" rows="5"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.status') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <label class="radio-inline"><input id="enable" type="radio" required name="status" value="1" checked> {{ trans('backend.enable') }}</label>
                <label class="radio-inline"><input id="disable" type="radio" required name="status" value="0"> {{ trans('backend.disable') }}</label>
            </div>
        </div>
    </div>

</div>
