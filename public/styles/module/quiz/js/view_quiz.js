$(document).ready(function() {
    var tempalate = document.getElementById('question-template').innerHTML;
    var template_chosen = document.getElementById('answer-template-chosen').innerHTML;
    var template_essay = document.getElementById('answer-template-essay').innerHTML;
    var template_answer_matching = document.getElementById('answer-template-matching').innerHTML;
    var template_qqcategory = document.getElementById('qqcategory-template').innerHTML;
    var template_fill_in = document.getElementById('fill-in-template').innerHTML;
    var template_fill_in_correct = document.getElementById('fill-in-correct-template').innerHTML;
    var template_correct_answer = document.getElementById('correct-answer-template-chosen').innerHTML;
    var template_matching_feedback = document.getElementById('matching-feedback-template').innerHTML;

    var answer_text = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
    var current_page = parseInt(get_query_string('page'));

    if (current_page < 1 || isNaN(current_page)) {
        current_page = 1;
    }

    pageloadding(1);
    load_questions(current_page);

    $("#questions").on('change', '.selected-answer', function () {
        let quiz_id = $(this).closest('.question-item').data('qid');
        $("#select-q"+ quiz_id).addClass('question-selected');
    });

    $("#questions").on('click', '.add-comment', function () {
        let form_comment = $(this).closest('.form-grading').find('.form-comment');
        if (form_comment.is(':visible')) {
            form_comment.addClass('d-none');
        }
        else {
            form_comment.removeClass('d-none');
        }
    });


    $(".button-next").on('click', function () {
        disabled_button(1);
        current_page += 1;
        load_questions(current_page);
    });

    $(".button-back").on('click', function () {
        disabled_button(1);
        if (current_page > 1) {
            current_page -= 1;
            load_questions(current_page);
        }
    });

    $(".select-question").on('click', function () {
        let quiz_id = $(this).data('id');
        let question_page = $(this).data('quiz-page');
        if (question_page == current_page) {
            let elmnt = document.getElementById("q"+ quiz_id);
            elmnt.scrollIntoView({
                behavior: 'smooth'
            });
        }
        else {
            load_questions(question_page, "q"+ quiz_id);
            current_page = question_page;
        }
    });

    function load_questions(page, scroll = null) {
        let text_page = '?page='+ page;
        $.ajax({
            type: 'POST',
            url: quiz_url + text_page,
            dataType: 'json',
            data: {}
        }).done(function(data) {
            if (data.rows.length <= 0) {
                disabled_button(0);
                show_message('Đã hết bài thi', 'warning');
                if (current_page > 1) {
                    current_page -= 1;
                }
                return false;
            }

            let rhtml = '';
            $.each(data.rows, function (i, item) {
                /*if (qqcategory['num_'+ (item.qindex-1)]) {
                    rhtml += replacement_template(template_qqcategory, {
                        'name': qqcategory['num_'+ (item.qindex-1)],
                        'percent': qqcategory['percent_'+ (item.qindex-1)],
                    });
                }*/

                let question = item.question;
                let feedback_ques = item.feedback_ques;
                let answer_matching = item.answer_matching;
                let answers = '';
                let correct = '';
                let prompt = '';
                var matching_answer = [];
                if (item.type === 'matching'){
                    let anwsers = item.answers;
                    let index = 0;

                    $.each(anwsers, function (i, a) {
                        matching_answer += '<option value="'+ a.matching_answer +'" >' + a.matching_answer +'</option>';
                    });

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_answer_matching, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'option': matching_answer,
                            'matching': item.matching ? item.matching[a.id] : '',
                            'correct': (item.matching && a.matching_answer) ? (item.matching[a.id] == a.matching_answer ? '<i class="text-success fa fa-check"></i>' : '<i class="text-danger fa fa-times"></i>') : '',
                        });

                        correct += a.title + ' ' + a.matching_answer + '. ';

                        answers += anwser;
                        index++;
                    });

                    let feedback = '';
                    if (feedback_ques){
                        feedback += '<div class="card"><div class="card-header bg-info text-white"> Phản hồi chung </div>';
                        $.each(feedback_ques, function (i, a) {
                            feedback += '<input type="text" class="form-control" disabled value="'+a+'">';
                        });
                        feedback += '</div>';
                    }
                    let matching_feedback = replacement_template(template_matching_feedback,{
                        'feedback': feedback,
                        'correct_answer': correct,
                    });
                    answers += matching_feedback;
                }
                if (item.type === 'multiple-choise') {
                    prompt = 'Chọn một đáp án:';
                    if (item.multiple == 1) {
                        prompt = 'Chọn một hoặc nhiều đáp án:';
                    }

                    let anwsers = item.answers;
                    let index = 0;
                    let correct = '';
                    let selected = item.answer;
                    let answer_horizontal = parseInt(item.answer_horizontal);
                    if (answer_horizontal != 0){
                        answers += '<div class="row">';
                    }
                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_chosen, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': (a.title != null) ? a.title : '',
                            'input_type': (item.multiple == 1) ? 'checkbox' : 'radio',
                            'qindex': item.qindex,
                            'checked': selected ? (selected.includes(a.id.toString()) ? 'checked' : '') : '',
                            'correct': item.correct_answers ? (item.correct_answers.includes(a.id)) ? '<i class="text-success fa fa-check"></i>' : '<i class="text-danger fa fa-times"></i>' : '',
                            'feedback': a.feedback_answer ? '<textarea type="text" class="form-control" disabled>'+a.feedback_answer+'</textarea><p></p>' : '',
                            'image_answer': a.image_answer ? '<img src="'+a.image_answer+'" class="w-100 img-responsive" />' : '',
                        });

                        if (item.correct_answers){
                            if (item.correct_answers.includes(a.id)){
                                correct += (a.title != null ? a.title : '');
                                correct += a.image_answer ? '<br> <img src="'+a.image_answer+'" class="w-100 img-responsive" />' : '';
                            }
                        }

                        if (answer_horizontal != 0){
                            answers += '<div class="col-'+ (12/answer_horizontal) +' p-1">' + anwser +'</div>';
                        }else{
                            answers += anwser;
                        }
                        index++;
                    });

                    if (answer_horizontal != 0) {
                        answers += '</div>';
                    }

                    let correct_answer = replacement_template(template_correct_answer, {
                        'correct_answer': correct,
                    });
                    answers += correct_answer;
                }
                if (item.type === 'essay') {
                    answers = replacement_template(template_essay, {
                        'id': item.id,
                        'qid': item.id,
                        'text_essay': (item.text_essay ? item.text_essay : ''),
                        'max_score': item.max_score,
                        'score': item.score,
                        'grading_comment': (item.grading_comment ? item.grading_comment : '')
                    });
                }
                if (item.type === 'fill_in') {
                    let anwsers = item.answers;
                    let index = 0;

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_fill_in, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'qindex': item.qindex,
                            'text_essay': item.text_essay ? (item.text_essay[i] ? item.text_essay[i] : '') : '',
                            'feedback': a.feedback_answer ? '<textarea type="text" class="form-control" disabled>'+a.feedback_answer+'</textarea><p></p>' : '',
                        });

                        answers += anwser;
                        index++;
                    });
                }

                if (item.type === 'fill_in_correct') {
                    let anwsers = item.answers;
                    let index = 0;

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_fill_in_correct, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'qindex': item.qindex,
                            'text_essay': item.text_essay ? (item.text_essay[i] ? item.text_essay[i] : '') : '',
                        });

                        answers += anwser;
                        index++;
                    });
                }

                let newtemp = replacement_template(tempalate, {
                    'qid': item.id,
                    'index': item.qindex,
                    'max_score': item.score_group.toFixed(2),
                    'name': item.name,
                    'answers': answers,
                    'prompt': prompt,
                });

                rhtml += newtemp;

            });

            window.history.pushState({page: page}, "", text_page);
            document.getElementById('questions').innerHTML = "";
            document.getElementById('questions').innerHTML = rhtml;
            pageloadding(0);
            disabled_button(0);
            if (current_page == 1) {
                $(".button-back").prop('disabled', true);
            } else {
                $(".button-back").prop('disabled', false);
            }

            if (scroll) {
                let elmnt = document.getElementById(scroll);
                elmnt.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            return false;
        }).fail(function(data) {
            alert('Không thể tải lên câu hỏi');
            return false;
        });
    }

    function disabled_button(status) {
        if (status == 1) {
            $(".button-next").prop('disabled', true);
            $(".button-back").prop('disabled', true);
        }
        else {
            $(".button-next").prop('disabled', false);
            $(".button-back").prop('disabled', false);
        }
    }

    function get_query_string(str_query) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(str_query);
    }

    function replacement_template(template, data){
        return template.replace(
            /{(\w*)}/g,
            function( m, key ){
                return data.hasOwnProperty( key ) ? data[ key ] : "";
            }
        );
    }

    function pageloadding(status) {
        if (status == 1) {
            $("#loading").show();
        }
        else {
            $("#loading").hide();
        }
    }
});
