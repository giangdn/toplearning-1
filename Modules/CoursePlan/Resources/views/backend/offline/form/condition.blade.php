<form action="{{ route('module.course_plan.save_condition', ['course_type' => $course_type, 'id' => $model->id]) }}" method="post" class="form-ajax">

    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            @can('course-plan-create-condition')
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
            @endcan
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="ratio">{{ trans('backend.ratio') }} % {{ trans('backend.join') }}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="ratio" class="form-control is-number" value="{{ isset($condition) ? $condition->ratio : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="minscore">{{ trans('backend.dark_point_introduced') }}</label>
                </div>
                <div class="col-md-9">
                <input type="text" name="minscore" class="form-control is-number"  value="{{ isset($condition) ? $condition->minscore : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="survey">{{ trans('backend.assessments') }}</label>
                </div>
                <div class="col-md-9 text-left">
                <input style="margin-left: -80px;" type="checkbox" name="survey" id="survey" value="{{ isset($condition) ? $condition->survey : '' }}" class=" w-25 d-inline-block form-custom" {{ $condition['survey'] == 1 ? 'checked' : '' }}>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="certificate">{{ trans('backend.certificate_course') }}</label>
                </div>
                <div class="col-md-9 text-left">
                <input style="margin-left: -80px;" type="checkbox" name="certificate" id="certificate" value="{{ isset($condition) ? $condition->certificate : '' }}" class=" w-25 d-inline-block form-custom" {{ $condition['certificate'] == 1 ? 'checked' : '' }}>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

</form>

<script>
    $('#survey').on('change', function() {
        console.log($(this).is(':checked'));
        if($(this).is(':checked')) {
            $("#survey").val(1);
        }
        else {
            $("#survey").val(0);
        }
    });
    $('#certificate').on('change', function() {
        console.log($(this).is(':checked'));
        if($(this).is(':checked')) {
            $("#certificate").val(1);
        }
        else {
            $("#certificate").val(0);
        }
    });
</script>
