<style>
    .card .card-body label {
        font-size: larger;
        margin-bottom: 5px;
    }
</style>
<div role="main">
    <form action="{{ route('module.quiz_educate_plan.save_setting', ['id' => $model->id]) }}" method="post" class="form-ajax">
        <input type="hidden" name="id" value="{{ $setting ? $setting->id : '' }}">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                <p></p>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <b>{{trans('backend.right_after_inspection')}}</b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->after_test_review_test == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="after_test_review_test"
                                           name="after_test_review_test" value="{{ $setting ? $setting->after_test_review_test : 0 }}">
                                    <label class="form-check-label" for="after_test_review_test">{{trans('backend.test_time')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->after_test_yes_no == 1 ? 'checked'
                                    : '' : '' }} type="checkbox" id="after_test_yes_no" name="after_test_yes_no" value="{{
                                    $setting ? $setting->after_test_yes_no : 0 }}">
                                    <label class="form-check-label" for="after_test_yes_no">{{trans('backend.right_or_not')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->after_test_score == 1 ? 'checked' :
                                    '' : '' }} type="checkbox" id="after_test_score" name="after_test_score" value="{{ $setting
                                     ? $setting->after_test_score : 0 }}">
                                    <label class="form-check-label" for="after_test_score">{{ trans('backend.score') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->after_test_specific_feedback == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="after_test_specific_feedback"
                                           name="after_test_specific_feedback" value="{{ $setting ? $setting->after_test_specific_feedback : 0 }}">
                                    <label class="form-check-label" for="after_test_specific_feedback">{{trans('backend.feedback_specific')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->after_test_general_feedback == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="after_test_general_feedback"
                                           name="after_test_general_feedback" value="{{ $setting ? $setting->after_test_general_feedback : 0 }}">
                                    <label class="form-check-label" for="after_test_general_feedback">{{trans('backend.general_feedback')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->after_test_correct_answer == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="after_test_correct_answer"
                                           name="after_test_correct_answer" value="{{ $setting ? $setting->after_test_correct_answer : 0 }}">
                                    <label class="form-check-label" for="after_test_correct_answer">{{trans('backend.right_answer')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <b>{{trans('backend.when_exam_end')}}</b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->exam_closed_review_test == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="exam_closed_review_test"
                                           name="exam_closed_review_test" value="{{ $setting ? $setting->exam_closed_review_test : 0 }}">
                                    <label class="form-check-label" for="exam_closed_review_test">{{trans('backend.test_time')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->exam_closed_yes_no == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="exam_closed_yes_no" name="exam_closed_yes_no"
                                           value="{{ $setting ? $setting->exam_closed_yes_no : 0 }}">
                                    <label class="form-check-label" for="exam_closed_yes_no">{{trans('backend.right_or_not')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->exam_closed_score == 1 ? 'checked'
                                    : '' : ''}} type="checkbox" id="exam_closed_score" name="exam_closed_score" value="{{
                                    $setting ? $setting->exam_closed_score : 0 }}">
                                    <label class="form-check-label" for="exam_closed_score">{{ trans('backend.score') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->exam_closed_specific_feedback == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="exam_closed_specific_feedback"
                                           name="exam_closed_specific_feedback" value="{{ $setting ? $setting->exam_closed_specific_feedback : 0 }}">
                                    <label class="form-check-label" for="exam_closed_specific_feedback">{{trans('backend.feedback_specific')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->exam_closed_general_feedback == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="exam_closed_general_feedback"
                                           name="exam_closed_general_feedback" value="{{ $setting ? $setting->exam_closed_general_feedback : 0 }}">
                                    <label class="form-check-label" for="exam_closed_general_feedback">{{trans('backend.general_feedback')}}</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" {{ $setting ? $setting->exam_closed_correct_answer == 1 ?
                                    'checked' : '' : '' }} type="checkbox" id="exam_closed_correct_answer"
                                           name="exam_closed_correct_answer" value="{{ $setting ? $setting->exam_closed_correct_answer : 0}}">
                                    <label class="form-check-label" for="exam_closed_correct_answer">{{trans('backend.right_answer')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var after_test_review_test = $('#after_test_review_test').val();
    var exam_closed_review_test = $('#exam_closed_review_test').val();

    if (after_test_review_test == 1){
        $('#after_test_yes_no').prop('disabled', false);
        $('#after_test_specific_feedback').prop('disabled', false);
        $('#after_test_general_feedback').prop('disabled', false);
        $('#after_test_correct_answer').prop('disabled', false);
    }else {
        $('#after_test_yes_no').prop('disabled', true);
        $('#after_test_specific_feedback').prop('disabled', true);
        $('#after_test_general_feedback').prop('disabled', true);
        $('#after_test_correct_answer').prop('disabled', true);
    }

    if (exam_closed_review_test == 1){
        $('#exam_closed_yes_no').prop('disabled', false);
        $('#exam_closed_specific_feedback').prop('disabled', false);
        $('#exam_closed_general_feedback').prop('disabled', false);
        $('#exam_closed_correct_answer').prop('disabled', false);
    } else {
        $('#exam_closed_yes_no').prop('disabled', true);
        $('#exam_closed_specific_feedback').prop('disabled', true);
        $('#exam_closed_general_feedback').prop('disabled', true);
        $('#exam_closed_correct_answer').prop('disabled', true);
    }


    $('#after_test_review_test').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
            $('#after_test_yes_no').prop('disabled', false);
            $('#after_test_specific_feedback').prop('disabled', false);
            $('#after_test_general_feedback').prop('disabled', false);
            $('#after_test_correct_answer').prop('disabled', false);
        } else {
            $(this).val(0);
            $('#after_test_yes_no').prop('disabled', true);
            $('#after_test_specific_feedback').prop('disabled', true);
            $('#after_test_general_feedback').prop('disabled', true);
            $('#after_test_correct_answer').prop('disabled', true);

            $('#after_test_yes_no').val(0);
            $('#after_test_specific_feedback').val(0);
            $('#after_test_general_feedback').val(0);
            $('#after_test_correct_answer').val(0);

            $('#after_test_yes_no').prop('checked', false);
            $('#after_test_specific_feedback').prop('checked', false);
            $('#after_test_general_feedback').prop('checked', false);
            $('#after_test_correct_answer').prop('checked', false);
        }
    });

    $('#after_test_yes_no').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#after_test_score').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#after_test_specific_feedback').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#after_test_general_feedback').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#after_test_correct_answer').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#exam_closed_review_test').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
            $('#exam_closed_yes_no').prop('disabled', false);
            $('#exam_closed_specific_feedback').prop('disabled', false);
            $('#exam_closed_general_feedback').prop('disabled', false);
            $('#exam_closed_correct_answer').prop('disabled', false);
        } else {
            $(this).val(0);
            $('#exam_closed_yes_no').prop('disabled', true);
            $('#exam_closed_specific_feedback').prop('disabled', true);
            $('#exam_closed_general_feedback').prop('disabled', true);
            $('#exam_closed_correct_answer').prop('disabled', true);

            $('#exam_closed_yes_no').prop('checked', false);
            $('#exam_closed_specific_feedback').prop('checked', false);
            $('#exam_closed_general_feedback').prop('checked', false);
            $('#exam_closed_correct_answer').prop('checked', false);

            $('#exam_closed_yes_no').val(0);
            $('#exam_closed_specific_feedback').val(0);
            $('#exam_closed_general_feedback').val(0);
            $('#exam_closed_correct_answer').val(0);
        }
    });

    $('#exam_closed_yes_no').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#exam_closed_score').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#exam_closed_specific_feedback').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#exam_closed_general_feedback').on('change', function () {
        if ($(this).is(':checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#exam_closed_correct_answer').on('change', function () {
        if ($(this).is(':checked')) {
            $(this).val(1);
        }else {
            $(this).val(0);
        }
    });
</script>
