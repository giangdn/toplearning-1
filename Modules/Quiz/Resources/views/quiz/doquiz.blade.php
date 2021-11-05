@extends('quiz::layout.app')

@section('page_title', $quiz->name)
<style>
    #questions video {
        width: 50%;
        height: auto;
    }

    #questions img {
        max-width: 100% !important;
        height: auto !important;
    }

    #quiz-content #modal-check-user-question .datepicker {
        box-sizing: border-box;
    }

    .flag-item {
        position: relative;
        top: -23px;
        left: -11px;
    }
    .flag-item:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        border-style: solid;
        border-width: 10px;
        border-color: yellow transparent transparent yellow;
    }
    .fa-flag-red{
        color: red;
    }
</style>

@section('content')
    <link rel="stylesheet" href="{{ asset('styles/module/quiz/css/doquiz.css') }}">

    <div class="row" id="quiz-content">

        <div class="col-md-3">
            @include('quiz::quiz.component.sidebar')
        </div>

        <div class="col-md-9 quiz-{{ $quiz->id }}">
            <form method="post" action="" id="form-question">
                <div class="card">
                    <div class="card-header">
                        <div class="text-center mb-1 button-page">
                            <button type="button" class="btn btn-info button-back"><i class="fa fa-mail-reply"></i> {{ trans('backend.back') }}</button> |
                            <button type="button" class="btn btn-info button-next">{{ trans('backend.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="questions"></div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center mt-1 button-page">
                            <button type="button" class="btn btn-info button-back"><i class="fa fa-mail-reply"></i> {{ trans('backend.back') }}</button> |
                            <button type="button" class="btn btn-info button-next">{{ trans('backend.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>

                    <div id="loading"></div>
                </div>
            </form>

            <form action="{{ route('module.quiz.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part->id, 'attempt_id' => $attempt->id]) }}" method="post" class="form-ajax text-center" id="form-submit" data-success="submit_success">
                <div class="card">
                    <div class="card-header">
                        Chúc mừng bạn đã hoàn thành kỳ thi <b>{{ $quiz->name }}</b>
                    </div>
                    <div class="card-body">
                        @if(!$attempt_finish)
                            <p>Để nộp bài vui lòng nhấn nút <b>Nộp bài thi</b></p>
                            <p>Để xem lại bài thi vui lòng nhấn nút <b>Xem lại bài</b></p>

                            <p id="camera-text"></p>
                        @else
                            <p>Bài thi của bạn đã được nộp, nhấn nút <b>Xem lại bài</b> để xem lại bài làm của mình</p>
                        @endif
                        <p></p>
                        <button type="button" class="btn btn-info button-back"><i class="fa fa-mail-reply"></i> Xem lại bài</button>
                        @if($attempt_finish)
                            <a href="{{ route('module.quiz.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" class="btn btn-info"><i class="fa fa-mail-reply"></i> Trở về màn hình kỳ thi</a>
                        @endif
                        @if(!$attempt_finish)
                        <button type="button" class="btn btn-primary send-quiz"><i class="fa fa-send-o"></i> Nộp bài thi</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <template id="question-template">
        <div class="question-item" id="q{qid}" data-qid="{qid}">
            <input type="hidden" name="q[]" value="{qid}">
            <div class="row">
                <div class="col-md-2">
                    <div class="info">
                        <h3 class="no">{{ trans('backend.question') }} <span class="qno">{index}</span></h3>
{{--                        <div class="grade">Điểm: {max_score}</div>--}}
                        <div class="questionflag editable"></div>
                        <a href="javascript:void(0)" class="flag" data-id="{qid}" data-flag="{flag}"><i class="fa fa-flag {class_flag}" aria-hidden="true"></i></a>
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="content">
                        <div class="formulation clearfix">
                            <div class="qtext">
                                <b><span lang="DE">{name}</span></b>
                            </div>
                            <div class="ablock">
                                <div class="prompt">{prompt}</div>
                                <div class="answer">
                                    {answers}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="answer-template-matching">
        <div class="r{index}">
            <input type="hidden" name="q_{qid}[]" value="{id}">
            <label class="m-l-1">
                <span class="answernumber">{index_text} </span>
                <span lang="VN">{title}</span>
            </label>
            @if($quiz_setting && $disabled)
                <select name="matching_{qid}[{id}]" class="selected-answer" data-answer="{id}" @if($disabled) disabled @endif>
                    <option value="{matching}">{matching}</option>
                </select>
                @if(($quiz_setting->after_test_yes_no == 1 && $disabled == 1) || ($quiz_setting->exam_closed_yes_no == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {correct}
                @endif
            @else
                <select name="matching_{qid}[{id}]" class="selected-answer" data-answer="{id}">
                    {option}
                </select>
            @endif
        </div>
    </template>

    <template id="matching-feedback-template">
        @if($disabled && $quiz_setting)
            @if(($quiz_setting->after_test_general_feedback == 1 && $disabled == 1) || ($quiz_setting->exam_closed_general_feedback == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                {feedback}
            @endif
            @if(($quiz_setting->after_test_correct_answer == 1 && $disabled == 1) || ($quiz_setting->exam_closed_correct_answer == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        Câu trả lời đúng
                    </div>
                    <textarea type="text" class="form-control" @if($disabled) disabled @endif>{correct_answer}</textarea>
                </div>
            @endif
        @endif
    </template>

    <template id="answer-template-chosen">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                <input type="{input_type}" name="q_{qid}[]" value="{id}" id="q{qindex}:choice{index}" class="selected-answer" data-answer="{id}" {checked} @if($disabled) disabled @endif>
                <span class="answernumber">{index_text} </span>
                <span lang="VN">{title}</span>
                <p lang="VN">{image_answer}</p>
                @if($disabled && $quiz_setting)
                    @if(($quiz_setting->after_test_yes_no == 1 && $disabled == 1)|| ($quiz_setting->exam_closed_yes_no == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                        {correct}
                    @endif
                @endif
            </label>
            @if($disabled && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $disabled == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    <template id="correct-answer-template-chosen">
        @if($quiz_setting)
            @if(($quiz_setting->after_test_correct_answer == 1 && $disabled == 1) || ($quiz_setting->exam_closed_correct_answer == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        Câu trả lời đúng
                    </div>
                    <div class="card-body">
                        {correct_answer}
                    </div>
                </div>
            @endif
        @endif
    </template>

    <template id="answer-template-essay">
        <input id="qf_{qid}" type="file" data-answer="{id}" class="selected-answer file-essay" accept=".xlsx, .pdf, .docx">
        <div>
            <a href="{link_file_essay}" class="">{file_essay}</a>
        </div>
        <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" data-answer="{id}" @if($disabled) disabled @endif>{text_essay}</textarea>
        @if($disabled && $quiz_setting)
            @if(($quiz_setting->after_test_general_feedback == 1 && $disabled == 1) || ($quiz_setting->exam_closed_general_feedback == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                {feedback}
            @endif
        @endif
    </template>

    <template id="qqcategory-template">
        <h3 class="question-title">
            <div class="row">
                <div class="col-md-10">
                    {name}
                </div>
                <div class="col-md-2 text-right">
                    {percent} %
                </div>
            </div>
        </h3>
    </template>

    <template id="fill-in-template">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                <span class="answernumber">{index_text} </span>
                <span lang="VN">{title}</span>
            </label>
            <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" id="q{qindex}:choice{index}" data-answer="{id}" @if($disabled) disabled @endif>{text_essay}</textarea>
            @if($disabled && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $disabled == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    <template id="fill-in-correct-template">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                <span class="answernumber">{index_text} </span>
                <span lang="VN">{title}</span>
            </label>
            <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" id="q{qindex}:choice{index}" data-answer="{id}" @if($disabled) disabled @endif>{text_essay}</textarea>
            @if($disabled && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $disabled == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    <div id="modal-check-user-question" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form id="check-user-question">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Mời bạn trả lời các câu hỏi sau:</h4>
                        {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                    </div>
                    <div class="modal-body">
                        @php
                            $arr = [
                                'code' => 'Mã số NV của bạn là gì',
                                'identity_card' => 'CMND của bạn',
                                'month' => 'Bạn sinh vào tháng mấy',
                                'day' => 'Bạn sinh vào ngày mấy',
                                'year' => 'Bạn sinh vào năm mấy',
                                'join_company' => 'Ngày bạn vào làm là ngày nào',
                                'phone' => 'Số điện thoại của bạn',
                                'unit_code' => 'Lựa chọn Đơn vị trực tiếp bạn đang làm việc',
                                'title_code' => 'Lựa chọn Chức danh của bạn',
                            ];
                            $key = array_rand($arr, 1);
                            $titles = \App\Models\Categories\Titles::where('status', '=', 1)->get();
                            $unit = \App\Models\Categories\Unit::where('status', '=', 1)->get();
                        @endphp
                        <input type="hidden" name="key" value="{{ $key }}" class="item">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="title">{{ $arr[$key] }}</div>
                                <div class="content">
                                    <input name="answer" id="question_orther" type="text" class="form-control">
                                    <select name="answer" class="form-control select2" id="unit">
                                        <option value=""></option>
                                        @foreach($unit as $item)
                                            <option value="{{ $item->code }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="answer" class="form-control select2" id="title">
                                        <option value=""></option>
                                        @foreach($titles as $item)
                                            <option value="{{ $item->code }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="check-user">Gửi</button>
                        {{--<button type="button" class="btn btn-default" id="refresh-question">Đổi câu</button>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
        var session_time = {{ config('session.lifetime') }};
        var quiz_id = '{{ $quiz->id }}';
        var quiz_url = '{{ route('module.quiz.doquiz.do_quiz', [
            'quiz_id' => $quiz->id,
            'part_id' => $part->id,
            'attempt_id' => $attempt->id,
        ]) }}';
        var qqcategory = jQuery.parseJSON('{!! json_encode($qqcategory) !!}');
        var questions_perpage = '{{ $quiz->questions_perpage }}';
        var lang = '{{ App::getLocale() }}';

        var context = '';
        var total_question = {{ count($questions) }};

        $('.select2').select2({
            allowClear: true,
            dropdownAutoWidth : true,
            width: '100%',
            placeholder: function(params) {
                return {
                    id: null,
                    text: params.placeholder,
                }
            },
        });
    </script>

    @if($quiz->webcam_require == 1)
        @include('quiz::quiz.component.camera_script')
    @endif

    <script type="text/javascript" src="{{ asset('styles/module/quiz/js/doquiz.js') }}"></script>

@stop
