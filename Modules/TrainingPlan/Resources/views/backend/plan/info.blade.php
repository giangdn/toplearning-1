<div class="row">
    <div class="col-md-9">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.plan_code') }}</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-9">
                <input name="code" type="text" class="form-control" value="{{ $model->code }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.plan_name') }}</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-9">
                <input name="name" type="text" class="form-control" value="{{ $model->name }}">
            </div>
        </div>

        {{-- CHỌN ĐƠN VỊ --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.unit') }}</label>
            </div>
            <div class="col-md-9">
                <select name="unit_id" id="unit_id" class="form-control select2 load-unit"
                        data-placeholder="-- {{ trans('backend.choose_training_unit') }} --" required>
                    @if($model->unit_id)
                        <option value="{{ $unit->id }}" {{ $model->unit_id == $unit->id  ? 'selected' : ''
                        }}> {{ $unit->code . ' - ' . $unit->name }} </option>
                    @endif
                </select>
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.year') }}</label>
            </div>
            <div class="col-md-9">
                <select name="year" id="year" class="form-control w-25">
                    @for($i=date('Y')-4;$i <= date('Y') + 20;$i++)
                    <option value="{{ $i }}" {{ $model->year == $i ? 'selected': '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.status') }}</label>
            </div>
            <div class="col-md-6">
                <label class="radio-inline"><input type="radio" name="status" value="1" @if($model->status == 1) checked @endif>{{ trans('backend.enable') }}</label>
                <label class="radio-inline"><input type="radio" name="status" value="0" @if($model->status == 0) checked @endif>{{ trans('backend.disable') }}
            </div>
        </div>
    </div>
</div>
<script>
    $('.radio-inline').on('click',function() {
        if( $("input[name=status]").val() == 1 && $("input[name=name]").val() ) {
            $('#base_tab').removeClass("active");
            $('#base').removeClass("active");
            $('#cost_tab').addClass("active");
            $('#cost').addClass("active");
        }
    })
</script>