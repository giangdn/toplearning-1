<div role="main">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('module.quiz.save_teacher', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="form_save_teacher" id="form-save-teacher">
                <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ trans("backend.instructors_grade") }} <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select name="teachers[]" class="form-control load-teacher" id="teacher" data-placeholder="-- {{ trans('backend.choose_teacher') }} --" multiple>
                            <option value=""></option>
                            {{--@if(isset($teachers))
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" selected>{{ $teacher->name }}</option>
                                @endforeach
                            @endif--}}
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label">Câu hỏi</label>
                    <div class="col-sm-6">
                        <select name="question[]" class="form-control select2" id="question" data-placeholder="Câu hỏi" multiple>
                            <option value=""></option>
                            @if(isset($quiz_questions))
                                @foreach($quiz_questions as $questions)
                                    <option value="{{ $questions->id }}">{!! $questions->name !!}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-sm-3">
                        @if($model->quiz_type != 1)
                        @canany(['quiz-create', 'quiz-edit'])
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        @endcanany
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="text-right">
                <button id="delete-permission-teacher" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
            </div>
            <p></p>

            <table class="tDefault table table-hover" id="table-permission-teacher">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="teacher_name">Giảng viên</th>
                    <th data-field="question">Câu hỏi</th>
                    <th data-field="action" data-formatter="action_formatter" data-align="center" data-width="5%">{{trans('backend.action')}}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function action_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="edit-item" data-teacher_id="'+ row.teacher_id +'" data-teacher_name="'+ row.teacher_name +'" data-question_id="'+ row.question_ids +'"><i class="fa fa-edit"></i></a>';
        }

        var table_permission_techer = new LoadBootstrapTable({
            url: '{{ route('module.quiz.edit.getPermissionTeacher', ['id' => $model->id]) }}',
            remove_url: '{{ route('module.quiz.edit.removePermissionTeacher', ['id' => $model->id]) }}',
            detete_button: '#delete-permission-teacher',
            table: '#table-permission-teacher'
        });

        function form_save_teacher(form) {
            $("#form-save-teacher #teacher").val(' ').trigger('change');
            $("#form-save-teacher #question").val(' ').trigger('change');
            table_permission_techer.refresh();
            $('#form-save-teacher').attr('action', '{{ route('module.quiz.save_teacher', $model->id) }}');
        }

        $('#table-permission-teacher').on('click', '.edit-item', function () {
            var teacher_id = $(this).data('teacher_id');
            var teacher_name = $(this).data('teacher_name');
            var question_id = $(this).data('question_id');

            $("#form-save-teacher #teacher").append('<option value="'+teacher_id+'" selected>'+ teacher_name +'</option>');
            if(question_id.length > 0){
                $("#form-save-teacher #question").val(question_id.split(',')).trigger('change');
            }else{
                $("#form-save-teacher #question").val(question_id).trigger('change');
            }

            $('#form-save-teacher').attr('action', '{{ route('module.quiz.edit.updatePermissionTeacher', $model->id) }}');
        });
    </script>
</div>

