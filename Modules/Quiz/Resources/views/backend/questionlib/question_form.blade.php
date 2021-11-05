@extends('layouts.backend')

@section('page_title', 'Câu hỏi: '.$category->name )

@section('header')
    <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamanager.quiz_manager') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.questionlib') }}">{{ trans('backend.questionlib') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.questionlib.question', ['id'=> $category->id]) }}">{{ trans('backend.question') }}: {{ $category->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{!! $page_title .'...' !!}</span>
        </h2>
    </div>
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form action="{{ route('module.quiz.questionlib.save_question', ['id' => $category->id]) }}" method="post" class="form-ajax">
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <input type="hidden" name="type" value="{{ $model->type }}">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group act-btns">
                                    @canany(['quiz-question-create', 'quiz-question-edit'])
                                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                    @endcanany
                                    <a href="{{ route('module.quiz.questionlib.question', ['id'=> $category->id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.question') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="name" id="name" type="text" class="form-control">{!! $model->name !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans("backend.kind")}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type1" value="essay" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'essay') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type1">{{trans("backend.essay")}}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type2" value="multiple-choise" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'multiple-choise') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type2">{{trans("backend.multiple_choice")}}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type3" value="matching" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'matching') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type3">{{trans("backend.matching_sentences")}}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type4" value="fill_in" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'fill_in') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type4">{{trans("backend.fill_in")}}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type5" value="fill_in_correct" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'fill_in_correct') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type5">Điền từ chính xác</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans("backend.choose")}} </label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline check-multi">
                                            <input class="form-check-input" type="checkbox" id="multiple" {{ $model->multiple == '1' ? 'checked' : '' }} {{ ($model->type == 'essay' || $model->type == 'matching' || $model->type == 'fill_in' || $model->type == 'fill_in_correct') ? 'disabled' : '' }}>

                                            <label class="form-check-label" for="multiple">{{trans("backend.select_all")}}</label>
                                            <input type="hidden" name="multiple" class="check-multiple" value="{{ $model->multiple ? $model->multiple : '0' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" id="answer_horizontal">
                                    <div class="col-sm-3 control-label">
                                        <label>Đáp án xếp ngang </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="answer_horizontal" class="form-control select2">
                                            <option value="0" {{ $model->answer_horizontal == 0 ? 'selected' : '' }}> Không</option>
                                            <option value="2" {{ $model->answer_horizontal == 2 ? 'selected' : '' }}> 2</option>
                                            <option value="3" {{ $model->answer_horizontal == 3 ? 'selected' : '' }}> 3</option>
                                            <option value="4" {{ $model->answer_horizontal == 4 ? 'selected' : '' }}> 4</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="shuffle_answers">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('backend.shuffle_answer')}}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="shuffle_answers" id="shuffle_answers1" value="1" {{ ($model->shuffle_answers == 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_answers1">{{trans("backend.enable")}}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="shuffle_answers" id="shuffle_answers0" value="0" {{ ($model->shuffle_answers == 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_answers0">{{trans("backend.disable")}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans("backend.answer_question")}}</label>
                                    </div>
                                    <div class="col-md-6" id="anwser-list">
                                        @if(isset($answers))
                                            @foreach($answers as $key => $answer)
                                            <div class="anwser-item">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <input type="hidden" name="ans_id[]" class="ans-id" value="{{ $answer->id }}">
                                                            <div class="col-sm-11">
                                                                @if($model->type == 'multiple-choise')
                                                                <input type="file" name="image_answer[]" class="" accept="image/*"><br>
                                                                    @if($answer->image_answer)
                                                                    <img src="{{ image_file($answer->image_answer) }}" alt="" class="w-25 img-responsive">
                                                                    @endif
                                                                <br>
                                                                @endif

                                                                <textarea type="text" class="form-control" name="answer[]" id="answer{{ $answer->id }}" placeholder="{{trans('backend.answer_question')}}">{{$answer->title }}</textarea>

                                                                @if($model->type == 'matching')
                                                                    <textarea name="matching_answer[]" type="text" class="form-control" placeholder="Đáp án">{{ $answer->matching_answer }}</textarea>
                                                                @elseif($model->type == 'fill_in_correct')
                                                                    <textarea name="fill_in_correct_answer[]" type="text" class="form-control" placeholder="Đáp án">{{ $answer->fill_in_correct_answer }}</textarea>
                                                                @elseif($model->type == 'multiple-choise')
                                                                    <span class="check-answer">
                                                                        <input type="checkbox" class="correct-answer" {{ $answer->correct_answer == 1 ? 'checked' : '' }}> {{ trans('backend.correct_answer') }}
                                                                        <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="{{ $answer->correct_answer }}">
                                                                    </span>
                                                                        <p></p>
                                                                    <span class="percent-answer">
                                                                        <input name="percent_answer[]" class="form-control is-number w-25 percent" placeholder="Nhập %" value="{{ $answer->percent_answer }}">
                                                                    </span>
                                                                        <p></p>
                                                                    <textarea name="feedback_answer[]" type="text" class="form-control" placeholder="Phản hồi cụ thể">{{$answer->feedback_answer }}</textarea>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <a href="javascript:void(0)" class="btn remove-anwser" data-ans="{{ $answer->id }}"> <i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p></p>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a href="javascript:void(0)" class="btn btn-info" id="add-answer" {{ $model->type === 'essay' ? 'hidden' : '' }}> {{ trans('backend.add_answer_question') }}</a>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.general_feedback') }}</label>
                                    </div>
                                    <div class="col-md-6" id="feedback-list" {{($model->type == 'multiple-choise') ? 'disabled' : '' }}>
                                        @if ($feedbacks)
                                            @foreach ($feedbacks as $feedback)
                                                <div class="feedback-item">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-11">
                                                                    <input name="feedback[]" type="text" class="form-control" value="{{ $feedback }}">
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <a href="javascript:void(0)" class="btn text-danger remove-feedback">{{ trans('backend.delete') }}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a href="javascript:void(0)" class="btn btn-info" id="add-feedback"> {{ trans('backend.add_feedback') }}</a>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.comment_question') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="note" type="text" class="form-control" value="" rows="3">{{ $model->note }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            var remove_answer = "{{ route('module.quiz.questionlib.remove_question_answer', ['id' => $category->id]) }}";
        </script>
    </div>
    <template id="anwser-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <input type="file" name="image_answer[]" class="" accept="image/*">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                            <span class="check-answer">
                                <input type="checkbox" class="correct-answer"> {{ trans('backend.correct_answer') }}
                                <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="0">
                            </span>
                            <p></p>
                            <span class="percent-answer">
                                <input name="percent_answer[]" class="form-control is-number w-25 percent" placeholder="Nhập %" value="">
                            </span>
                            <p></p>
                            <textarea name="feedback_answer[]" type="text" class="form-control" placeholder="Phản hồi cụ thể"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    <template id="matching-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                            <textarea name="matching_answer[]" type="text" class="form-control" placeholder="Đáp án"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    <template id="fill-in-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    <template id="fill-in-correct-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                            <textarea name="fill_in_correct_answer[]" type="text" class="form-control" placeholder="Đáp án"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    <template id="feedback-template">
        <div class="feedback-item">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <input name="feedback[]" type="text" class="form-control" value="">
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-feedback"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script type="text/javascript">
        var check_shuffle_answers = "<?php echo $model->shuffle_answers ?>";
        if(!check_shuffle_answers) {
            $("#shuffle_answers1").prop("checked", true);
        }
        
        var anwser_template = document.getElementById('anwser-template').innerHTML;
        var matching_template = document.getElementById('matching-template').innerHTML;
        var feedback_template = document.getElementById('feedback-template').innerHTML;
        var fill_in_template = document.getElementById('fill-in-template').innerHTML;
        var fill_in_correct_template = document.getElementById('fill-in-correct-template').innerHTML;

        var question_type = $("input[name=type]:checked").val();
        let multi = $("input[name=multiple]").val();
        if (multi == '0'){
            $("#anwser-list").find('.percent-answer').hide();
            $('#anwser-list').find('.check-answer').show();
        } else {
            $("#anwser-list").find('.percent-answer').show();
            $('#anwser-list').find('.check-answer').hide();
        }

        if(question_type == 'multiple-choise'){
            $('#shuffle_answers').show();
            $('#answer_horizontal').show();
        }else{
            $('#shuffle_answers').hide();
            $('#answer_horizontal').hide();
        }

        let type = question_type ? question_type : '';
        $('input[name=type]').on('change', function(){
            if($('#type1').is(':checked')){
                $('#add-answer').hide();
                $('.check-multi').hide();
                $('#add-feedback').show();
                type = 'essay';
                $("#anwser-list").html('');
                $("#feedback-list").html('');
                $('#shuffle_answers').hide();
                $('#answer_horizontal').hide();
            }else if($('#type3').is(':checked')){
                $('#add-answer').show();
                $('.check-multi').hide();
                $('#add-feedback').show();
                type = 'matching';
                $("#anwser-list").html('');
                $("#feedback-list").html('');
                $('#shuffle_answers').hide();
                $('#answer_horizontal').hide();
            }else if($('#type4').is(':checked')) {
                $('#add-answer').show();
                $('.check-multi').hide();
                $('#add-feedback').show();
                type = 'fill_in';
                $("#anwser-list").html('');
                $("#feedback-list").html('');
                $('#shuffle_answers').hide();
                $('#answer_horizontal').hide();
            }else if($('#type5').is(':checked')) {
                $('#add-answer').show();
                $('.check-multi').hide();
                $('#add-feedback').show();
                type = 'fill_in_correct';
                $("#anwser-list").html('');
                $("#feedback-list").html('');
                $('#shuffle_answers').hide();
                $('#answer_horizontal').hide();
            }else {
                $('#add-answer').show();
                $('.check-multi').show();
                $('#add-feedback').hide();
                type = 'multiple-choise';
                $("#anwser-list").html('');
                $("#feedback-list").html('');
                $('#shuffle_answers').show();
                $('#answer_horizontal').show();
            }
        });

        $("#add-answer").on('click', function () {
            let multi = $("input[name=multiple]").val();
            var ans_key = parseInt($('.anwser-item').last().data('ans_key'), 10) + 1;
            if (isNaN(ans_key)) {
                ans_key = 0;
            }

            if (type == ''){
                show_message('Chưa chọn loại', 'error');
                return false;
            }
            if (type == 'matching') {
                let matching = replacement_template(matching_template, {
                    'ans_key' : ans_key
                });

                $("#anwser-list").append(matching);
            }else if (type == 'fill_in') {
                let fill_in = replacement_template(fill_in_template, {
                    'ans_key' : ans_key
                });

                $("#anwser-list").append(fill_in);
            }else if (type == 'fill_in_correct') {
                let fill_in_correct = replacement_template(fill_in_correct_template, {
                    'ans_key' : ans_key
                });

                $("#anwser-list").append(fill_in_correct);
            }else {
                let anwser = replacement_template(anwser_template, {
                    'ans_key' : ans_key
                });

                $("#anwser-list").append(anwser);
            }

            if (multi == '0'){
                $("#anwser-list").find('.percent-answer').hide();
                $('#anwser-list').find('.check-answer').show();
            } else {
                $("#anwser-list").find('.percent-answer').show();
                $('#anwser-list').find('.check-answer').hide();
            }

            CKEDITOR.replace('add_answer'+ans_key+'', {
                filebrowserImageBrowseUrl: '/filemanager?type=image',
                filebrowserBrowseUrl: '/filemanager?type=file',
                filebrowserUploadUrl : null, //disable upload tab
                filebrowserImageUploadUrl : null, //disable upload tab
                filebrowserFlashUploadUrl : null, //disable upload tab
            });
        });

        $("#add-feedback").on('click', function () {
            if (type == ''){
                show_message('Chưa chọn loại', 'error');
                return false;
            }else {
                $("#feedback-list").append(feedback_template);
            }
        });

        $("#feedback-list").on('click', '.remove-feedback', function () {
            $(this).closest('.feedback-item').remove();
        });

        $('#anwser-list').on('click', '.remove-anwser', function(){
            $(this).closest('.anwser-item').remove();
            var ans_id = $(this).data('ans');

            $.ajax({
                url: remove_answer,
                type: 'post',
                data: {
                    ans_id: ans_id,
                },
            }).done(function(data) {

                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#anwser-list').on('click', '.is-text', function(){

            if($(this).is(':checked')){
                $(this).closest('.anwser-item').find('.check-is-text').val(1);
            }else{
                $(this).closest('.anwser-item').find('.check-is-text').val(0);
            }
        });

        $('#anwser-list').on('click', '.correct-answer', function(){

            if($(this).is(':checked')){
                $(this).closest('.anwser-item').find('.check-correct-answer').val(1);
            }else{
                $(this).closest('.anwser-item').find('.check-correct-answer').val(0);
            }
        });

        $('#multiple').on('click', function(){
            if($(this).is(':checked')){
                $(this).closest('.form-check').find('.check-multiple').val(1);
                $('#anwser-list').find('.check-answer').hide();
                $('#anwser-list').find('.percent-answer').show();
                $('#anwser-list').find('.check-correct-answer').val(0);
            }else{
                $(this).closest('.form-check').find('.check-multiple').val(0);
                $('#anwser-list').find('.check-answer').show();
                $('#anwser-list').find('.percent-answer').hide();
                $('#anwser-list').find('.percent').val('');
            }
        });

        function replacement_template( template, data ){
            return template.replace(
                /{(\w*)}/g,
                function( m, key ){
                    return data.hasOwnProperty( key ) ? data[ key ] : "";
                }
            );
        }
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('name', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });

        var _answer_id = $('input[name=ans_id\\[\\]]').map(function(){return $(this).val();}).get();
        $.each(_answer_id, function (i, area) {
            CKEDITOR.replace('answer'+area+'', {
                filebrowserImageBrowseUrl: '/filemanager?type=image',
                filebrowserBrowseUrl: '/filemanager?type=file',
                filebrowserUploadUrl : null, //disable upload tab
                filebrowserImageUploadUrl : null, //disable upload tab
                filebrowserFlashUploadUrl : null, //disable upload tab
            });
        });
    </script>
@stop
