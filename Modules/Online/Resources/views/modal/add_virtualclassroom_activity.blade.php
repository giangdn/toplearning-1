<div class="modal fade modal-add-activity" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.online.activity.save', ['id' => $course->id, 'activity' => 6]) }}" method="post" class="form-ajax">
                <input type="hidden" name="subject_id" value="{{ $model->subject_id }}">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.activiti') }}: {{ trans('backend.virtual_classroom') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
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
                                    <option value="{{$get_lesson->id}}" {{ $model->lesson_id == $get_lesson->id ? 'selected' : ''}}> {{ $get_lesson->lesson_name }}</option>
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
                            <label for="subject_id">{{ trans('backend.choose_class') }}</label>
                        </div>

                        <div class="col-md-9">
                            @php
                                $bbb = Modules\VirtualClassroom\Entities\VirtualClassroom::where('id', '=', $subject_id)->first();
                            @endphp
                            <select name="subject_id" id="subject_id" class="form-control load-bbb" data-placeholder="-- {{ trans('backend.choose_virtual_classroom') }} --">
                                @if($bbb)
                                    <option value="{{ $bbb->id }}">{{ $bbb->code .' - '. $bbb->name }}</option>
                                @endif
                            </select>
                            <p class="description"><a target="_blank" href="{{ route('module.virtualclassroom.index') }}" class="form-text text-info">{{ trans('backend.add_virtual_classroom') }}</a></p>
                        </div>
                    </div>

                    @include('online::modal.setting_activity')
                </div>

                <div class="modal-footer">
                    @php
                        $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                    @endphp
                    @if(!$check_history || $course->lock_course == 0)
                        <button type="submit" class="btn btn-primary" id="add-activity"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                    @endif

                    <button type="button" id="closed" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".load-bbb").select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: '{{ route('module.online.activity.loaddata', ['id' => $course->id, 'func' => 'loadBBB']) }}',
            dataType: 'json',
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });

    $('#closed').on('click', function () {
        window.location = '';
    });
</script>
