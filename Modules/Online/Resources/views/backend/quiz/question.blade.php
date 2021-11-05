@extends('layouts.backend')

@section('page_title', 'Câu hỏi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.management') }}">{{ trans('backend.online_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.edit', ['id' => $course_id]) }}">{{ $coure_name }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.quiz', ['course_id' => $course_id]) }}">{{ trans('backend.quiz_list') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $quiz->id]) }}">{{ $quiz->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.question') }}</span>
        </h2>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/quiz/css/question.css') }}">
@endsection

@section('content')

<div class="main" id="quiz-question">
    <form method="post" action="" class="form-validate form-ajax" id="form-question" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right">

                <div class="dropdown">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus-circle"></i> {{trans('backend.add_new')}} </button>
                    <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item load-modal" data-url="{{ route('module.quiz.question.get_modal_quiz_question', ['id' => $quiz->id]) }}">{{trans('backend.add_questionlib')}}</a>
                        <a href="javascript:void(0)" class="dropdown-item load-modal" data-url="{{ route('module.quiz.question.get_modal_question_category', ['id' => $quiz->id]) }}">{{trans('backend.add_random_question')}}</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="row">
            <div class="col-md-12">

                <ul class="list-quiz-question" id="sortable">
                    @php
                        $page = 1;
                        $index = 1;
                        $qqcategory = 0;
                    @endphp

                @foreach($quiz_questions as $quiz_question)
                    @php
                        $category = $categories($quiz_question->qcategory_id);
                        $question = $questions($quiz_question->question_id);
                    @endphp
                    @if ($index % $quiz->questions_perpage == 1)
                        <li class="page-name" data-index="{{ $index-1 }}"> Trang {{ $page }} </li>
                    @endif

                    @php
                        $qcats = $qqc($quiz->id, $index-1);
                    @endphp

                    @if($qcats)

                        @foreach($qcats as $qcat)
                        <li class="item-quiz-question category-question">
                            <div class="row">
                                <div class="col-md-10">
                                    <span class="custom-question"> {!! $qcat->name !!}</span>
                                </div>

                                <div class="col-md-2 text-right">
                                    <span><b>{{ $qcat->percent_group }} %</b></span>
                                    <a href="javascript:void(0)" class="edit-category" data-category="{{ $qcat->id }}" data-num_order="{{ $quiz_question->num_order }}"><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:void(0)" class="remove-category" data-category="{{ $qcat->id }}"><i class="fa fa-trash text-danger"></i></a>
                                </div>
                            </div>
                            <input type="hidden" name="question[]" value="c_{{ $qcat->id }}">
                        </li>
                        @endforeach
                    @endif

                        <li class="item-quiz-question">
                            <div class="row">
                                <div class="col-md-10">

                                    <input class="custom-num-order" type="text" data-ques="{{ $quiz_question->id }}" value="{{ $quiz_question->num_order }}" readonly>
                                    <span class="custom-question">{!! $quiz_question->qcategory_id ? 'Ngẫu nhiên ('.strip_tags(trim(html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'), "\xc2\xa0")).')' : strip_tags(trim(html_entity_decode($question['name'], ENT_QUOTES, 'UTF-8'), "\xc2\xa0"))  !!} </span>
                                    <input type="hidden" name="question[]" value="{{ $quiz_question->id }}">

                                </div>
                                <div class="col-md-2 text-right">
                                    <a href="javascript:void(0)" class="add-qqcategory" data-num_order="{{ $quiz_question->num_order }}"><i class="fa fa-plus"></i></a>
                                    <input class="custom-score" type="text" data-ques="{{ $quiz_question->id }}" value="{{ $quiz_question->max_score }}">
                                    <a href="javascript:void(0)" class="remove-question" data-ques="{{ $quiz_question->id }}"><i class="fa fa-trash text-danger"></i></a>
                                </div>
                            </div>
                        </li>

                        @php
                        if ($index % $quiz->questions_perpage == 0) { $page ++; }
                        $index += 1;
                        @endphp

                @endforeach

                    @php
                        $qcats = $qqc($quiz->id, $index-1);
                    @endphp

                    @if($qcats)

                        @foreach($qcats as $qcat)
                            <li class="item-quiz-question category-question">
                                <div class="row">
                                    <div class="col-md-10">
                                        <span class="custom-question"> {!!  $qcat->name !!}</span>
                                    </div>

                                    <div class="col-md-2 text-right">
                                        <span><b>{{ $qcat->percent_group }} %</b></span>
                                        <a href="javascript:void(0)" class="edit-category" data-category="{{ $qcat->id }}"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" class="remove-category" data-category="{{ $qcat->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </div>
                                </div>
                                <input type="hidden" name="question[]" value="c_{{ $qcat->id }}">
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </form>

<script type="text/javascript">
    $(function () {
        /*update_question();*/
        update_page();

        $("#sortable").sortable({
            update: function (event, ui) {
                $.each($(".custom-num-order"), function (index, item) {
                    $(this).val(index+1);
                });

                update_question();
            }
        });

        $("#sortable").disableSelection();

        $("#sortable .page-name").bind('click.sortable mousedown.sortable',function(e){
            e.stopImmediatePropagation();
        });

        $(".remove-question").on('click', function(){
            let item = $(this);
            let quiz_ques_id = item.data('ques');
            item.find('i').attr('class', 'fa fa-spinner fa-spin text-danger');

            $.ajax({
                type: 'POST',
                url: "{{ route('module.quiz.question.remove_quiz_question', ['id' => $quiz->id]) }}",
                data: {
                    quiz_ques_id: quiz_ques_id,
                },
            }).done(function(data) {
                item.closest('.item-quiz-question').remove();
                update_question();
                return false;
            }).fail(function(data) {
                show_message('Không thể xóa câu hỏi', 'error');
                return false;
            });
        });

        $(".custom-score").on('change', function(){
            var max_score = $(this).closest('.item-quiz-question').find('.custom-score').val();
            var quiz_ques_id = $(this).data('ques');

            $.ajax({
                type: 'POST',
                url: "{{ route('module.quiz.question.update_max_score', ['id' => $quiz->id]) }}",
                data: {
                    quiz_ques_id: quiz_ques_id,
                    max_score : max_score,
                },
            }).done(function(data) {
                update_question();
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $("#sortable").on('click', '.add-qqcategory', function () {
            let item = $(this);
            let num_order = item.data('num_order');
            let icon = item.find('i').attr('class');
            item.find('i').attr('class', 'fa fa-spinner fa-spin');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.question.modal_qqcategory', ['id' => $quiz->id]) }}',
                dataType: 'html',
                data: {
                    'num_order': num_order
                }
            }).done(function(data) {

                item.find('i').attr('class', icon);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();

                return false;
            }).fail(function(data) {

                return false;
            });
        });

        $("#sortable").on('click', '.edit-category', function () {
            let item = $(this);
            let num_order = item.data('num_order');
            let icon = item.find('i').attr('class');
            let category_id = item.data('category');

            item.find('i').attr('class', 'fa fa-spinner fa-spin');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.question.modal_qqcategory', ['id' => $quiz->id]) }}',
                dataType: 'html',
                data: {
                    'num_order': num_order,
                    'category_id': category_id
                }
            }).done(function(data) {
                item.find('i').attr('class', icon);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $("#sortable").on('click', '.remove-category', function () {
            let item = $(this);
            let num_order = item.data('num_order');
            let category_id = item.data('category');

            item.find('i').attr('class', 'fa fa-spinner fa-spin text-danger');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.question.remove_qqcategory', ['id' => $quiz->id]) }}',
                dataType: 'html',
                data: {
                    'num_order': num_order,
                    'category_id': category_id
                }
            }).done(function(data) {
                item.closest('.item-quiz-question').remove();
                update_question();
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        function update_question() {
            let qcount = $("input[name='question[]']").length;
            if (qcount <= 0) {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.question.update_num_order', ['id' => $quiz->id]) }}',
                dataType: 'json',
                data: $("#form-question").serialize(),
            }).done(function(data) {
                if (data.status !== "success") {
                    show_message('Không thể cập nhật thứ tự câu hỏi', 'error');
                    return false;
                }
                return false;
            }).fail(function(data) {
                return false;
            });
        }

        function update_page() {

        }
    })
</script>
</div>

@stop
