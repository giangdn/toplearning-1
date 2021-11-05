<div class="row">
    <div class="col-md-9">
    @if($permission_save)
        <form method="post" action="{{ route('module.offline.save_ratting_course', ['course_id' => $model->id]) }}" class="form-ajax" id="form-rating-course">
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label>Nội dung chương trình (%)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="program_content" id="program_content" class="form-control is-number" value="{{ $ratting_course ? $ratting_course->program_content : 0 }}" onblur="findTotal()">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label>Giảng viên (%)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="teacher" id="teacher" class="form-control is-number" value="{{ $ratting_course ? $ratting_course->teacher : 0 }}" onblur="findTotal()">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label>Tổ chức (%)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="organization" id="organization" class="form-control is-number" value="{{ $ratting_course ? $ratting_course->organization : 0 }}" onblur="findTotal()">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label>Chất lượng chung khóa học</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="quality_course" id="quality_course" class="form-control" value="{{ $ratting_course ? $ratting_course->quality_course : 0 }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 control-label"></div>
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary" data-must-checked="false">
                        <i class="fa fa-save"></i> &nbsp;{{ trans('lacore.save ') }}
                    </button>
                </div>
            </div>
        </form>
    @endif
    </div>
</div>

<script type="text/javascript">
    function findTotal() {
        var program_content = $('#program_content').val() ? Number(parseFloat($('#program_content').val()).toFixed(2)) : 0;
        var teacher = $('#teacher').val() ? Number(parseFloat($('#teacher').val()).toFixed(2)) : 0;
        var organization = $('#organization').val() ? Number(parseFloat($('#organization').val()).toFixed(2)) : 0;
        var total = (program_content + teacher + organization) / 3;
        var quality_course = parseFloat(total).toFixed(2);
        $('#quality_course').val(quality_course);
    }
</script>
