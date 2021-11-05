<form id="form-schedule-parent" action="{{ route('module.offline.save_schedule_parent', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="submit_success_schedule_parent">
    <input type="hidden" name="id" value="">
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
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            @if($model->lock_course == 0)
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('backend.add_new') }} </button>
            @endif
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12" id="schedule-parent">
        <div class="text-right">
            @if($model->lock_course == 0)
            <button id="delete-schedule-parent" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
            @endif
        </div>
        <p></p>
        <table class="tDefault table table-hover" id="table-schedule-parent">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="day" data-align="center" data-formatter="day_formatter">{{ trans('backend.session') }}</th>
                    <th data-field="time" data-align="center" data-formatter="time_formatter">{{ trans('backend.time') }}</th>
                    <th data-field="lesson_date" data-align="center">{{ trans('backend.start_date') }}</th>
                    <th data-field="created_by">{{ trans('backend.created_by') }}</th>
                    <th data-field="updated_by">{{ trans('backend.update_by') }}</th>
                    <th data-formatter="edit_formatter" data-width="5%" data-align="center">{{ trans('backend.edit') }}</th>
                    <th data-formatter="teacher_formatter" data-width="5%" data-align="center">{{ trans('backend.teacher') }}</th>
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
        return row.start_time +' <i class="fa fa-arrow-right"></i> ' + row.end_time;
    }

    function edit_formatter(value, row, index) {
        return '<a class="btn btn-info edit-schedule-parent" data-id="'+ row.id +'" data-start_time="'+ row.start_time +'" data-end_time="'+ row.end_time +'" data-lesson_date="'+ row.lesson_date +'" ><i class="fa fa-edit" aria-hidden="true"></i></a>';
    }

    function teacher_formatter(value, row, index) {
        return '<a class="btn btn-info add-teacher" data-id="'+ row.id +'" data-start_time="'+ row.start_time +'" data-end_time="'+ row.end_time +'" data-lesson_date="'+ row.lesson_date +'" ><i class="fa fa-user" aria-hidden="true"></i></a>';
    }

    var table_schedule_parent = new LoadBootstrapTable({
        url: '{{ route('module.offline.get_schedule_parent', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.remove_schedule_parent', ['id' => $model->id]) }}',
        detete_button: '#delete-schedule-parent',
        table: "#table-schedule-parent"
    });

    $('.timepicker').timepicker({
        showMeridian: false
    });

    $('#schedule-parent').on('click', '.edit-schedule-parent', function() {
        var id = $(this).data('id');
        var start_time = $(this).data('start_time');
        var end_time = $(this).data('end_time');
        var lesson_date = $(this).data('lesson_date');

        $('input[name=id]').val(id).trigger('change');
        $('input[name=start_time]').val(start_time).trigger('change');
        $('input[name=end_time]').val(end_time).trigger('change');
        $('input[name=lesson_date]').val(lesson_date).trigger('change');
    });

    $('#schedule-parent').on('click', '.add-teacher', function() {
        var id = $(this).data('id');
        var start_time = $(this).data('start_time');
        var end_time = $(this).data('end_time');
        var lesson_date = $(this).data('lesson_date');

        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.modal_schedule', ['id' => $model->id]) }}',
            dataType: 'html',
            data: {
                'schedule_parent_id': id,
                'start_time': start_time,
                'end_time': end_time,
                'lesson_date': lesson_date,
            },
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #modal-schedule").modal();

            return false;
        }).fail(function(data) {

            Swal.fire(
                '',
                '{{ trans('lageneral.data_error ') }}',
                'error'
            );
            return false;
        });
    });

    function submit_success_schedule_parent(form) {
        $("#form-schedule-parent input[name=id]").val('').trigger('change');
        $("#form-schedule-parent input[name=lesson_date]").val(null).trigger('change');
        table_schedule_parent.refresh();
    }
</script>
