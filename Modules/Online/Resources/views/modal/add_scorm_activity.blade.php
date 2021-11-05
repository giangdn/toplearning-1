<div class="modal fade modal-add-activity" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.online.activity.save', [$course->id, 1]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <input type="hidden" name="subject_id" value="{{ $model->subject_id }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.activiti') }}: Scorm</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" style="width:100%; max-height: 500px; overflow: scroll;">
                    <h3>{{ trans('backend.general_setting') }}</h3>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="select_lesson_name">Thuộc bài học</label><span style="color:red"> * </span>
                        </div>
                        <div class="col-md-9">
                            @php
                                $get_lessons = Modules\Online\Entities\OnlineCourseLesson::where('course_id',$course->id)->get();
                            @endphp
                            <select class="form-control" name="select_lesson_name" id="select_lesson_name">
                                <option value="" selected disabled>Chon bài học</option>
                                @foreach ($get_lessons as $get_lesson)
                                    <option value="{{$get_lesson->id}}" {{ $model->lesson_id == $get_lesson->id ? 'selected' : ''}}>{{$get_lesson->lesson_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="name">{{ trans('backend.activiti_name') }}</label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $model->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">{{ trans('backend.description') }}</label>
                        </div>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control" rows="5">{{ $module->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="path">{{ trans('backend.file') }}</label>
                        </div>

                        <div class="col-md-9">
                            <a href="javascript:void(0)" id="select-file-activity">{{ trans('backend.choose_file') }}</a>
                            <br><em id="path-name">{{ $module->warehouse->file_name ?? '' }}</em>
                            <input type="hidden" name="path" id="path" value="{{ $module->path }}">
                        </div>
                    </div>

                    <h3>{{ trans('backend.attemps') }}</h3>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="max_attempt">{{ trans('backend.number_of_attempts') }}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="max_attempt" id="max_attempt" class="form-control">
                                <option value="0">{{ trans('backend.unlimited') }}</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ trans('backend.times') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="new_attempt_required">{{ trans('backend.force_new_attempt') }}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="new_attempt_required" id="new_attempt_required" class="form-control">
                                <option value="0">{{ trans('backend.no') }}</option>
                                <option value="1" @if($module->new_attempt_required == 1) selected @endif>{{ trans('backend.when_attempt_completed_passed_failed') }}</option>
                                <option value="2" @if($module->new_attempt_required == 2) selected @endif>{{ trans('backend.always') }}</option>
                            </select>
                        </div>
                    </div>

                    <h3>{{ trans('backend.score') }}</h3>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="what_grade">{{ trans('backend.scoring_method') }}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="what_grade" id="what_grade" class="form-control">
                                <option value="1" @if($module->what_grade == 1) selected @endif>{{ trans('backend.highest') }}</option>
                                <option value="2" @if($module->what_grade == 2) selected @endif>{{ trans('backend.medium') }}</option>
                                <option value="3" @if($module->what_grade == 3) selected @endif>{{ trans('backend.first') }}</option>
                                <option value="4" @if($module->what_grade == 4) selected @endif>{{ trans('backend.end') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="max_score">{{ trans('backend.maximum_score') }}</label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" name="max_score" id="max_score" class="form-control" value="{{ $module->max_score ?? 100 }}">
                        </div>
                    </div>

                    <h3>{{ trans('backend.completion_conditions') }}</h3>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="score_required">{{ trans('backend.request_to_receive') }}</label>
                        </div>

                        <div class="col-md-9">
                            <input type="checkbox" name="score_required" id="score_required" value="1" @if($module->score_required == 1) checked @endif>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="min_score_required">{{ trans('backend.completed_dark_spot') }}</label>
                        </div>

                        <div class="col-md-9">
                            <input type="number" name="min_score_required" id="min_score_required" class="form-control" value="{{ $module->min_score_required ?? 0 }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="name">{{ trans('backend.status') }}</label>
                        </div>

                        <div class="col-md-9">
                            {{ trans('backend.passed') }} <input type="checkbox" name="status_passed" value="1" @if($module->status_passed == 1) checked @endif>
                            {{ trans('backend.completed') }} <input type="checkbox" name="status_completed" value="1" @if($module->status_completed == 1) checked @endif>
                        </div>
                    </div>

                    <h3>Điều kiện truy cập</h3>
                    @include('online::modal.setting_activity')
                </div>

                <div class="modal-footer">
                    @php
                        $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                    @endphp
                    @if(!$check_history || $course->lock_course == 0)
                    <button type="submit" class="btn btn-primary" id="add-activity"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    @endif
                    <button type="button" id="closed" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#select-file-activity").on('click', function () {
        open_filemanager({type: 'scorm'}, function (url, path, name) {
            $("#path-name").html(name);
            $("#path").val(path);
        });
    });

    $('#closed').on('click', function () {
        window.location = '';
    });
</script>
