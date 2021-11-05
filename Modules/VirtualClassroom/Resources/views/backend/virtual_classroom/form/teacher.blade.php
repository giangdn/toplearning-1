<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{ route('module.virtualclassroom.save_teacher', ['id' => $model->id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success_object" id="form-teacher">
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.teacher') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <select name="teacher_id[]" id="teacher_id" class="form-control select2" data-placeholder="{{ trans('backend.choose_teacher') }}" required multiple>
                                <option value=""></option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"> {{ $teacher->code . ' - ' . $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label"></div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.add_new') }} </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="form-teacher">
        <div class="text-right">
            <button id="delete-teacher" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
        </div>
        <p></p>
        <table class="tDefault table table-hover" id="table-teacher">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                <th data-field="teacher_code">{{ trans('backend.code') }}</th>
                <th data-field="teacher_name">{{ trans('backend.fullname') }}</th>
                <th data-field="teacher_email">Email</th>
                <th data-field="teacher_phone">{{ trans('backend.phone') }}</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">

    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    var table_teacher = new LoadBootstrapTable({
        url: '{{ route('module.virtualclassroom.get_teacher', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.virtualclassroom.remove_teacher', ['id' => $model->id]) }}',
        detete_button: '#delete-teacher',
        table: '#table-teacher'
    });

    function submit_success_object(form) {
        $("#form-teacher #teacher_id").val(null).trigger('change');
        table_teacher.refresh();
    }
</script>
