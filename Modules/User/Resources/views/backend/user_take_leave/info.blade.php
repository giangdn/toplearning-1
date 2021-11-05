<div class="row">
    <div class="col-md-9">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="user_id">Nhân viên <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <select @if(!isset($model->id)) name="user_id" @endif id="user_id" class="form-control load-user" data-placeholder="-- Nhân viên --" required {{ isset($model->id) ? 'disabled' : '' }}>
                    @if(isset($model->id))
                        <option value="{{ $model->user_id }}"> {{ $model->full_name }}</option>
                    @endif
                </select>
                @if(isset($model->id))
                    <input type="hidden" name="user_id" value="{{ $model->user_id }}">
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="absent_code">Lý do <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <select name="absent_code" id="absent_code" class="form-control select2" data-placeholder="-- Lý do --">
                    <option value=""></option>
                    @foreach($absents as $absent)
                        <option value="{{ $absent->code }}" {{ $model->absent_code == $absent->code ? 'selected' : '' }}> {{ $absent->name }}</option>
                    @endforeach
                </select>
                <input type="checkbox" id="absent_other" value="{{ $model->absent_name ? 1 : 0 }}"> Khác
                <textarea name="absent_name" id="absent_name" class="form-control" rows="5"> {{ $model->absent_name }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Ngày nghỉ <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <span>
                    <input name="start_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="{{ get_date($model->start_date) }}" required>
                </span>
                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                <span>
                    <input name="end_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="{{ get_date($model->end_date) }}">
                </span>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
            </div>
            <div class="col-md-9">
                <div class="btn-group act-btns">
                    @can(['user-take-leave-create','user-take-leave-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    @endcan
                    <a href="{{ route('module.backend.user_take_leave') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    if ($('#absent_other').val() == 1){
        $('#absent_other').prop('checked', true);
        $('#absent_name').prop('disabled', false);
        $('#absent_code').val('').trigger('change');
        $('#absent_code').prop('disabled', true);
    }else {
        $('#absent_other').prop('checked', false);
        $('#absent_name').val('').trigger('change');
        $('#absent_name').prop('disabled', true);
        $('#absent_code').prop('disabled', false);
    }

    $('#absent_other').on('click', function () {
       if($(this).is(':checked')){
           $('#absent_name').prop('disabled', false);
           $('#absent_code').prop('disabled', true);
           $('#absent_code').val('').trigger('change');
       }else{
           $('#absent_name').val('').trigger('change');
           $('#absent_name').prop('disabled', true);
           $('#absent_code').prop('disabled', false);
       }
    });
</script>
