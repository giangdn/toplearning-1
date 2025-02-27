@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    @php
        $tabs = request()->get('tabs', null);
    @endphp
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.quiz') }}
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.quiz.manager') }}">{{ trans('backend.quiz_list') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    @if($model->id)
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right">
                @canany(['training-unit-quiz-add-question', 'quiz-add-question'])
                <a href="{{ $is_unit > 0 ? route('module.training_unit.quiz.question', ['id' => $model->id]) : route('module.quiz.question', ['id' => $model->id]) }}" class="btn btn-primary"> <i class="fa fa-question-circle"></i> {{ trans('backend.question') }}</a>
                @endcanany
                @canany(['training-unit-quiz-result', 'quiz-result'])
                <a href="{{ $is_unit > 0 ? route('module.training_unit.quiz.result', ['id' => $model->id]) : route('module.quiz.result', ['id' => $model->id]) }}" class="btn btn-success"><i class="fa fa-eye"></i> {{ trans('backend.result') }}</a>
                @endcanany
                @canany(['training-unit-quiz-register', 'quiz-register'])
                <a href="{{ $is_unit > 0 ? route('module.training_unit.quiz.register', ['id' => $model->id]) : route('module.quiz.register', ['id' => $model->id]) }}" class="btn btn-info"> <i class="fa fa-users"></i> {{ trans('backend.internal_contestant') }}</a>
                @endcanany
                @can('quiz-register-user-secondary')
                    <a href="{{ route('module.quiz.register.user_secondary', ['id' => $model->id]) }}" class="btn btn-warning"><i class="fa fa-users"></i> {{ trans('backend.user_secondary') }}</a>
                @endcan
            </div>
        </div>
        <p></p>
    @endif
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            @if($model->id)
                @canany(['quiz-create', 'quiz-edit'])
                <li class="nav-item"><a href="#part" class="nav-link" role="tab" data-toggle="tab">{{trans('backend.exams')}}</a></li>
                <li class="nav-item"><a href="#rank" class="nav-link" role="tab" data-toggle="tab">{{ trans("backend.classification") }}</a></li>
                <li class="nav-item"><a href="#teacher1" class="nav-link" role="tab" data-toggle="tab">{{ trans('backend.teacher') }}</a></li>
                <li class="nav-item"><a href="#setting1" class="nav-link" role="tab" data-toggle="tab">{{trans('backend.custom')}}</a></li>
                <li class="nav-item"><a href="#promotion" class="nav-link @if($tabs == 'promotion') active @endif" role="tab" data-toggle="tab">{{ trans('backend.reward_points') }}</a></li>

                <li class="nav-item"><a href="#suggestions" class="nav-link @if($tabs == 'suggestions') active @endif" role="tab" data-toggle="tab">Góp ý</a></li>
                @endcanany
            @endif
        </ul>

        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('quiz::backend.quiz.form.info')
            </div>

            @if($model->id)
                <div id="part" class="tab-pane">
                    @include('quiz::backend.quiz.form.part')
                </div>

                <div id="rank" class="tab-pane">
                    @include('quiz::backend.quiz.form.rank')
                </div>

                <div id="teacher1" class="tab-pane">
                    @include('quiz::backend.quiz.form.teacher')
                </div>

                <div id="setting1" class="tab-pane">
                    @include('quiz::backend.quiz.form.setting')
                </div>

                <div id="promotion" class="tab-pane @if($tabs == 'promotion') active @endif">
                    @include('quiz::backend.quiz.form.promotion')
                </div>

                <div id="suggestions" class="tab-pane @if($tabs == 'suggestions') active @endif">
                    @include('quiz::backend.quiz.form.suggestions')
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#paper_exam').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).closest('.form-check').find('.check-paper-exam').val(1);
                } else {
                    $(this).closest('.form-check').find('.check-paper-exam').val(0);
                }
            });

            $('input[name=webcam_require]').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                    $('input[name=times_shooting_webcam]').prop('disabled', false);
                } else {
                    $(this).val(0);
                    $('input[name=times_shooting_webcam]').prop('disabled', true);
                }
            });
            $('input[name=question_require]').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                    $('input[name=times_shooting_question]').prop('disabled', false);
                } else {
                    $(this).val(0);
                    $('input[name=times_shooting_question]').prop('disabled', true);
                }
            });

            $('.load-unit-quiz').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: function (params) {
                    return {
                        id: null,
                        text: params.placeholder,
                    }
                },
                ajax: {
                    method: 'GET',
                    url: '{{ route('module.quiz.edit.getunit', ['id' => $model->id]) }}',
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

        });

        $('select[name=quiz_template_id]').on('change',function () {
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.load.exam.template') }}',
                dataType: 'json',
                data: {
                    exam_template_id:$this.val()
                }
            }).done(function(result) {
                var data = result.data;
                var attemp = (data.attempts < 10 && data.attempts > 0) ? '0'+(data.attempts) : data.attempts;
                if (result.status=='success'){
                    $('input[name=code]').val(data.code);
                    $('input[name=name]').val(data.name);
                    $('textarea[name=description]').val(data.description);
                    $('input[name=limit_time]').val(data.limit_time);
                    $('input[name=pass_score]').val(data.pass_score);
                    $('input[name=max_score]').val(data.max_score);
                    $('input[name=questions_perpage]').val(data.questions_perpage);
                    $('select[name=quiz_type]').val(data.quiz_type).trigger('change');
                    $('select[name=shuffle_question]').val(data.shuffle_question).trigger('change');
                    $('select[name=shuffle_answers]').val(data.shuffle_answers).trigger('change');
                    $('select[name=attempts]').val(attemp).trigger('change');
                    $('select[name=grade_methor]').val(data.grade_methor).trigger('change');
                    $('select[name=type_id]').val(data.type_id).trigger('change');
                    $('input[name=paper_exam]').val(data.paper_exam);
                    $('#paper_exam').attr('checked',data.paper_exam==1?true:false);
                    $('input[name=img]').val(data.img);
                    $("#image-review").html('<img src="'+ data.img_view +'" class="w-50">');
                    $('input[name=webcam_require]').val(data.webcam_require);
                    $('input[name=question_require]').val(data.question_require);
                    $('input[name=times_shooting_webcam]').val(data.times_shooting_webcam);
                    $('input[name=times_shooting_question]').val(data.times_shooting_question);
                    $('input[name=webcam_require]').attr('checked',data.webcam_require==1?true:false);
                    $('input[name=question_require]').attr('checked',data.question_require==1?true:false);
                    $('input[name=times_shooting_webcam]').attr('disabled',data.webcam_require==1?false:true);
                    $('input[name=times_shooting_question]').attr('disabled',data.question_require==1?false:true);
                }
            }).fail(function(data) {
                return false;
            });
        });

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img src="'+ path +'" class="w-50">');
                $("#image-select").val(path);
            });
        });
    </script>
@endsection
