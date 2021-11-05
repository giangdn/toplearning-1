<div class="row mt-4">
    <div class="col-md-12">
    @if($permission_save)
        <form method="post" action="{{ route('module.online.save_lesson', ['course_id' => $model->id]) }}" class="form-ajax" id="form-lesson" data-success="submit_success_object">
            <div class="box-title">

                {{-- BÀI HỌC --}}
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>Tên bài học</label><span style="color:red"> * </span>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-8">
                                <input id="lesson_name" name="lesson_name" type="text" class="form-control" value="" required>
                            </div>
                            <div class="4">
                                @if($model->lock_course == 0)
                                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;Thêm bài học</button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @if($permission_save && $model->lock_course == 0)
        <a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="{{ route('module.online.modal_add_activity', ['id' => $model->id]) }}"><i class="fa fa-plus-circle"></i> {{trans('backend.add_activities')}}</a>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12 course-content">
        <form action="" method="post" id="form-activity">
        <ul class="section img-text yui3-dd-drop" id="sortable">
        @if(isset($activities) && $activities)
        @foreach($activities as $activity)
            @php
                $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $model->id)->where('course_activity_id', '=', $activity->id)->first();
            @endphp
            <li>
                <div class="row">
                    <input type="hidden" name="num_order[]" class="num-order" value="{{ $activity->id }}">
                    <div class="col-md-7" title="{{ $activity->activity_name .': '. $activity->name }}">
                        <span class="editing_move moodle-core-dragdrop-draghandle" title="Move resource" tabindex="0" data-draggroups="resource" role="button" data-sectionreturn="0">
                            <i class="icon fas fa-arrows fa-fw  iconsmall" aria-hidden="true"></i>
                        </span>
                        <img src="{{ $activity->icon }}" class="iconlarge activityicon" role="presentation" aria-hidden="true">
                        <span class="instancename">{{ $activity->name }} </span>
                    </div>

                    <div class="col-md-5">
                        @if($permission_save)
                            <a href="javascript:void(0)"
                                class="editing-update menu-action cm-edit-action"
                                data-action="update" role="menuitem"
                                aria-labelledby="actionmenuaction-9"
                                data-id="{{ $activity->id }}"
                                data-activity-code="{{ $activity->activity_code }}"
                                data-subject="{{ $activity->subject_id }}">
                                <i class="fas fa-cog fa-fw" aria-hidden="true"></i>
                                <span class="menu-action-text"> {{trans('backend.setting')}}</span>
                            </a>
                             @if($model->lock_course == 0)
                            <a href="javascript:void(0)"
                                class="editing-delete menu-action cm-edit-action ml-2"
                                role="menuitem"
                                aria-labelledby="actionmenuaction-15"
                                data-activity="{{ $activity->id }}">
                                <i class="fa fa-trash fa-fw " aria-hidden="true"></i>
                                <span class="menu-action-text" id="actionmenuaction-15"> {{trans('backend.delete')}} </span>
                            </a>

                            <a href="javascript:void(0)" class="editing-status menu-action cm-edit-action ml-2" role="menuitem"
                                aria-labelledby="actionmenuaction-15" data-activity="{{ $activity->id }}" data-status="{{ $activity->status == 1 ? 0 : 1 }}">
                                @if ($activity->status == 1)
                                    <i class="fas fa-eye-slash" aria-hidden="true"></i><span class="menu-action-text" id="actionmenuaction-15"> {{trans('backend.hide')}} </span>
                                @else
                                    <i class="fas fa-eye" aria-hidden="true"></i><span class="menu-action-text" id="actionmenuaction-15"> {{trans('backend.show')}} </span>
                                @endif
                            </a>
                             @endif
                        @endif
                            @if($activity->activity_id != 2)
                                <a target="_blank" href="{{ route('module.online.goactivity', ['id' => $model->id, 'aid' => $activity->id, 'lesson' => $activity->lesson_id]) }}" class="menu-action cm-edit-action ml-2" role="menuitem" aria-labelledby="actionmenuaction-15" data-turbolinks="false">
                                    <i class="fa fa-eye fa-fw " aria-hidden="true"></i>
                                    <span class="menu-action-text" id="actionmenuaction-15"> Vào học </span>
                                </a>
                            @endif
                    </div>
                </div>
            </li>
        @endforeach
        @endif
        </ul>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="text-right">
            @if($model->lock_course == 0)
            <button id="delete-lesson" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
            @endif
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table" id="table-lesson">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                    <th data-field="lesson_name">Tên bài học</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#sortable").on('click', '.editing-update', function () {
            let id = $(this).data('id');
            let activity_code = $(this).data('activity-code');
            let subject = $(this).data('subject');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.modal_activity', ['id' => $model->id]) }}',
                dataType: 'html',
                data: {
                    'id': id,
                    'activity': activity_code,
                    'subject_id': subject
                }
            }).done(function(data) {
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $("#sortable").on('click', '.editing-delete', function () {
            let item = $(this);
            let id = item.data('activity');
            Swal.fire({
                title: '',
                text: 'Bạn có chắc muốn xóa hoạt động này?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý!',
                cancelButtonText: 'Hủy!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('module.online.activity.remove', ['id' => $model->id]) }}",
                        dataType: 'json',
                        data: {
                            'id': id
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                item.closest('li').remove();
                                update_num_order();
                            }
                        }
                    });
                }
            });
        });

        $("#sortable").on('click', '.editing-status', function () {
            let item = $(this);
            let id = item.data('activity');
            let status = item.data('status');
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.activity.update_status_activity', ['id' => $model->id]) }}',
                dataType: 'html',
                data: {
                    'id': id,
                    'status': status
                }
            }).done(function(data) {
                window.location = '';
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $("#sortable").sortable({
            update: function (event, ui) {
                update_num_order();
            }
        });

        $("#sortable").disableSelection();

        function update_num_order() {
            let qcount = $("input[name='num_order[]']").length;
            if (qcount <= 0) {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.activity.update_numorder', ['id' => $model->id]) }}',
                dataType: 'json',
                data: $("#form-activity").serialize(),
            }).done(function (data) {
                if (data.status !== "success") {
                    show_message('Không thể cập nhật thứ tự', 'error');
                    return false;
                }
                return false;
            }).fail(function (data) {
                return false;
            });
        }
    });
</script>
<script type="text/javascript">
    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    var table_lesson = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.get_lesson', ['course_id' => $model->id]) }}',
        remove_url: '{{ route('module.online.remove_lesson', ['course_id' => $model->id]) }}',
        detete_button: '#delete-lesson',
        table: '#table-lesson',
    });

    function submit_success_object(form) {
        $("#form-lesson #lesson_name").val(null).trigger('change');
        table_lesson.refresh();
    }
</script>
