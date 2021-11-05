<div class="row">

    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.manager') }}</label>
            </div>
            <div class="col-md-6">
                <select name="manager[]" id="manager" class="form-control load-user" data-placeholder="-- {{ trans('backend.manager') }} --" multiple>
                   
                    @if(isset($unit_managers))
                        @foreach($unit_managers as $item)
                            <option value="{{ $item->user_id }}" selected>{{ $item->user_code . ' - ' .  $item->user_lastname . ' ' . $item->user_firstname }} </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>