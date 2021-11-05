<form id="form-schedule" action="{{ route('module.course_educate_plan.save_schedule', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="submit_success_schedule">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.lesson_time') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <span><input name="start_time" type="text" required class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ bắt đầu" autocomplete="off" value=""></span>
                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span><input name="end_time" type="text" required class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ kết thúc" autocomplete="off" value=""></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="lesson_date">{{ trans('backend.start_date') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <input name="lesson_date" type="text" class="form-control datepicker" placeholder="{{ trans('backend.start_date') }}" autocomplete="off" value="" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="lesson_date"><i class="fa fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.main_lecturer') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select
                        name="teacher_main_id" id="teacher_main_id" class="form-control select2 load-teacher" data-placeholder="{{ trans('backend.choose_main_lecturer') }}" required>
                        <option value=""></option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"> {{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="cost_teacher_main">{{ trans('backend.main_lecturer_cost') }} / {{ trans('backend.session') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="cost_teacher_main" class="form-control is-number" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="teach_id">{{ trans('backend.tutors') }} </label>
                </div>
                <div class="col-md-9">
                    <select name="teach_id"
                            id="teach_id" class="form-control select2 load-teacher" data-placeholder="{{ trans('backend.choose_tutors') }}">
                        <option value=""></option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"> {{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="cost_teach_type">{{ trans('backend.tutor_cost') }} / {{ trans('backend.session') }} </label>
                </div>
                <div class="col-md-9 ">
                    <input type="text" name="cost_teach_type" class="form-control is-number">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="total_lessons">{{ trans('backend.session') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="total_lessons" type="text" class="form-control is-number" value="">
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-9">
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('backend.add_new') }} </button>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12" id="schedule">
        <div class="text-right">
            <button id="delete-schedule" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
        </div>
        <p></p>
        <table class="tDefault table table-hover" id="table-schedule">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="day" data-align="center" data-formatter="day_formatter">{{ trans('backend.session') }}</th>
                    <th data-field="time" data-align="center" data-formatter="time_formatter">{{ trans('backend.time') }}</th>
                    <th data-field="main_name">{{ trans('backend.main_lecturer') }}</th>
                    <th data-field="cost_teacher_main">{{ trans('backend.main_lecturer_cost') }}</th>
                    <th data-field="teach_name">{{ trans('backend.tutors') }}</th>
                    <th data-field="cost_teach_type">{{ trans('backend.tutor_cost') }}</th>
                    <th data-field="total_lessons" data-align="center">{{ trans('backend.number_lessons') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<script type="text/javascript">
    function day_formatter(value, row, index) {
        return (index + 1);
    }
    function time_formatter(value, row, index) {
        return row.start_time +' <i class="fa fa-long-arrow-right"></i> ' + row.end_time;
    }

    var table_schedule = new LoadBootstrapTable({
        url: '{{ route('module.course_educate_plan.get_schedule', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.course_educate_plan.remove_schedule', ['id' => $model->id]) }}',
        detete_button: '#delete-schedule',
        table: "#table-schedule"
    });
</script>

<script type="text/javascript">
    $('.timepicker').timepicker({
        showMeridian: false
    });

    function submit_success_schedule(form) {
        $("#form-schedule select[name=teacher_main_id]").val(null).trigger('change');
        $("#form-schedule input[name=cost_teacher_main]").val(null).trigger('change');
        $("#form-schedule select[name=teach_id]").val(null).trigger('change');
        $("#form-schedule input[name=cost_teach_type]").val(null).trigger('change');
        $("#form-schedule input[name=lesson_date]").val(null).trigger('change');
        $("#form-schedule input[name=total_lessons]").val(null).trigger('change');
        table_schedule.refresh();
    }
</script>
