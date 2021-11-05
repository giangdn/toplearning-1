@php
    $date = date('Y-m-d');
@endphp
<div class="row">
    <div class="col-md-9">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Điều kiện</label>
            </div>
            <div class="col-md-6">
                <label class="radio-inline"><input type="radio" name="condition" value="1" checked> Khóa học Online </label>
                <label class="radio-inline"><input type="radio" name="condition" value="2"> Khóa học tập trung </label>
                <label class="radio-inline"><input type="radio" name="condition" value="3"> Kỳ thi </label>
            </div>
        </div>
        <form method="post" action="{{ route('backend.emulation_program.save_condition', ['id' => $model->id]) }}" 
            class="form-horizontal form-ajax" 
            role="form" enctype="multipart/form-data" 
            data-success="submit_success_condition">
            <input type="hidden" name="condition_type" id="condition_type" value="">
            <div id="choose_course_online">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> Khóa học Online </label>
                    </div>
                    <div class="col-md-9">
                        <select name="courses_online[]" multiple class="form-control select2" data-placeholder=" -- Chọn khóa học -- ">
                            @foreach ($get_courses_online as $get_course_online)
                                @if (!isset($get_course_online->end_date) || ($get_course_online->end_date !== null && $get_course_online->end_date > $date))
                                    <option value="{{$get_course_online->id}}">
                                        {{$get_course_online->code}} - 
                                        {{$get_course_online->name}} - 
                                        {{ \Carbon\Carbon::parse($get_course_online->start_date)->format('d-m-Y') }}
                                        {{$get_course_online->end_date ? (': ' . \Carbon\Carbon::parse($get_course_online->end_date)->format('d-m-Y')) : ''}}
                                    </option>                                    
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="choose_course_offline">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> Khóa học tập trung </label>
                    </div>
                    <div class="col-md-9">
                        <select name="courses_offline[]" multiple class="form-control select2" data-placeholder=" -- Chọn khóa học -- ">
                            @foreach ($get_courses_offline as $get_course_offline)
                                @if ($get_course_offline->end_date !== null && $get_course_offline->end_date > $date)
                                    <option value="{{$get_course_offline->id}}">
                                        {{$get_course_offline->code}} -
                                        {{$get_course_offline->name}} - 
                                        {{ \Carbon\Carbon::parse($get_course_online->start_date)->format('d-m-Y') }} :
                                        {{ \Carbon\Carbon::parse($get_course_online->end_date)->format('d-m-Y') }}
                                    </option>                                    
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="choose_quiz">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> Kỳ thi </label>
                    </div>
                    <div class="col-md-9">
                        <select name="quizs[]" multiple class="form-control select2" data-placeholder=" -- Chọn kỳ thi -- ">
                            @foreach ($get_quizs as $get_quiz)
                                @php
                                    $get_quiz_part = Modules\Quiz\Entities\QuizPart::where('quiz_id',$get_quiz->id)->max('end_date');
                                    $max_time = Modules\Quiz\Entities\QuizPart::where('quiz_id',$get_quiz->id)->max('end_date');
                                    $min_time = Modules\Quiz\Entities\QuizPart::where('quiz_id',$get_quiz->id)->min('start_date');
                                @endphp
                                @if ($get_quiz_part > $date)
                                    <option value="{{$get_quiz->id}}">
                                        {{$get_quiz->code}} -
                                        {{$get_quiz->name}} -
                                        {{ \Carbon\Carbon::parse($min_time)->format('d-m-Y') }} :
                                        {{ \Carbon\Carbon::parse($max_time)->format('d-m-Y') }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="condition_add">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        @can('emulation-program-create-condition')
                            <button type="submit" class="btn btn-info"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="form-condition">
        <div id="course-online-id">
            <div class="text-right">
                @can('emulation-program-delete-condition')
                    <button id="delete-item-course-online" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                @endcan
            </div>
            <p></p>
            <table class="tDefault table table-hover bootstrap-table" id="table-course-online">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="code">Mã khóa học</th>
                        <th data-field="course_name">Tên khóa học</th>
                        <th data-field="time" data-formatter="course_time">Thời gian</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="course-offline-id">
            <div class="text-right">
                @can('emulation-program-delete-condition')
                    <button id="delete-item-course-offline" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                @endcan
            </div>
            <p></p>
            <table class="tDefault table table-hover bootstrap-table3" id="table-course-offline">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="code">Mã khóa học</th>
                        <th data-field="course_name">Tên khóa học</th>
                        <th data-field="time" data-formatter="course_time">Thời gian</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="quiz-id">
            <div class="text-right">
                @can('emulation-program-delete-condition')
                    <button id="delete-quiz-id" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                @endcan
            </div>
            <p></p>
            <table class="tDefault table table-hover bootstrap-table2" id="table-quiz">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="code">Mã kỳ thi</th>
                        <th data-field="name">Tên Kỳ thi</th>
                        <th data-field="time" data-formatter="quiz_time">Thời gian</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    function course_time(value, row, index) {
        console.log(row);
        return row.start_date + ( row.end_date ? '<i class="fas fa-arrow-right"></i> ' + row.end_date : '')
    }

    function quiz_time(value, row, index) {
        console.log(row);
        return row.start_date + ( row.end_date ? '<i class="fas fa-arrow-right"></i> ' + row.end_date : '')
    }

    var table_course_online = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.emulation_program.get_course', ['id' => $model->id, 'type' => 1]) }}',
        remove_url: '{{ route('backend.emulation_program.remove_conditon', ['id' => $model->id, 'type' => 1]) }}',
        detete_button: '#delete-item-course-online',
        table: '#table-course-online',
    });

    var table_course_offline = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.emulation_program.get_course', ['id' => $model->id, 'type' => 2]) }}',
        remove_url: '{{ route('backend.emulation_program.remove_conditon', ['id' => $model->id, 'type' => 2]) }}',
        detete_button: '#delete-item-course-offline',
        table: '#table-course-offline',
    });

    var table_quiz = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.emulation_program.get_quiz', ['id' => $model->id]) }}',
        remove_url: '{{ route('backend.emulation_program.remove_conditon', ['id' => $model->id, 'type' => 3]) }}',
        detete_button: '#delete-quiz-id',
        table: '#table-quiz'
    });
</script>

<script type="text/javascript">
    function submit_success_condition(form) {
        $("#choose_course_online select[name=courses_online]").val(null).trigger('change');
        $("#choose_course_offline select[name=courses_offline]").val(null).trigger('change');
        $("#choose_quiz select[name=quizs]").val(null).trigger('change');
        table_course_online.refresh();
        table_course_offline.refresh();
        table_quiz.refresh();
    }


    var condition = $("input[name=condition]").val();
    if (condition == 1) {
        $("input[name=condition_type]").val(1);
        $("#choose_course_online").show('slow');
        $("#choose_course_offline").hide('slow');
        $("#choose_quiz").hide('slow');
        $("#course-online-id").show('slow');
        $("#course-offline-id").hide('slow');
        $("#quiz-id").hide('slow');
    } else if (condition == 2) {
        $("input[name=condition_type]").val(2);
        $("#choose_course_online").hide('slow');
        $("#choose_course_offline").show('slow');
        $("#choose_quiz").hide('slow');
        $("#course-online-id").hide('slow');
        $("#course-offline-id").show('slow');
        $("#quiz-id").hide('slow');
    } else {
        $("input[name=condition_type]").val(3);
        $("#choose_course_online").hide('slow');
        $("#choose_course_offline").hide('slow');
        $("#choose_quiz").show('slow');
        $("#course-online-id").hide('slow');
        $("#course-offline-id").hide('slow');
        $("#quiz-id").show('slow');
    }

    $("input[name=condition]").on('change', function () {
        var condition = $(this).val();
        if (condition == 1) {
            $("input[name=condition_type]").val(1);
            $("#choose_course_online").show('slow');
            $("#choose_course_offline").hide('slow');
            $("#choose_quiz").hide('slow');
            $("#course-online-id").show('slow');
            $("#course-offline-id").hide('slow');
            $("#quiz-id").hide('slow');
        } else if (condition == 2) {
            $("input[name=condition_type]").val(2);
            $("#choose_course_online").hide('slow');
            $("#choose_course_offline").show('slow');
            $("#choose_quiz").hide('slow');
            $("#course-online-id").hide('slow');
            $("#course-offline-id").show('slow');
            $("#quiz-id").hide('slow');
        } else {
            $("input[name=condition_type]").val(3);
            $("#choose_course_online").hide('slow');
            $("#choose_course_offline").hide('slow');
            $("#choose_quiz").show('slow');
            $("#course-online-id").hide('slow');
            $("#course-offline-id").hide('slow');
            $("#quiz-id").show('slow');
        }
    });
</script>
