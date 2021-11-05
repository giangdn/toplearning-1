$(document).ready(function() {
    var tempalate = document.getElementById('question-template').innerHTML;
    var template_chosen = document.getElementById('answer-template-chosen').innerHTML;
    var template_essay = document.getElementById('answer-template-essay').innerHTML;
    var template_qqcategory = document.getElementById('qqcategory-template').innerHTML;
    var template_correct_answer = document.getElementById('correct-answer-template-chosen').innerHTML;
    var template_answer_matching = document.getElementById('answer-template-matching').innerHTML;
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

    $(".button-next").on('click', function () {
        disabled_button(1);
        let formData = $("#form-question").serialize();

        $.ajax({
            type: 'POST',
            url: quiz_url + '/save',
            dataType: 'json',
            data: formData
        }).done(function(data) {

            if (data.status === "error") {
                show_message('Không thể lưu đáp án của bạn', 'error');
                return false;
            }

            current_page += 1;
            load_questions(current_page);
            return false;
        }).fail(function(data) {
            show_message('Không thể lưu đáp án của bạn', 'error');
            disabled_button(0);
            return false;
        });
    });

    $(".button-back").on('click', function () {
        disabled_button(1);
        let formData = $("#form-question").serialize();

        $.ajax({
            type: 'POST',
            url: quiz_url + '/save',
            dataType: 'json',
            data: formData
        }).done(function(data) {

            if (data.status === "error") {
                show_message('Không thể lưu đáp án của bạn', 'error');
                return false;
            }

            if (current_page > 1) {
                current_page -= 1;
                load_questions(current_page);
            }

            return false;
        }).fail(function(data) {
            show_message('Không thể lưu đáp án của bạn', 'error');
            disabled_button(0);
            return false;
        });
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
            let formData = $("#form-question").serialize();
            $(".select-question").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: quiz_url + '/save',
                dataType: 'json',
                data: formData
            }).done(function(data) {

                if (data.status === "error") {
                    show_message('Không thể lưu đáp án của bạn', 'error');
                    return false;
                }

                load_questions(question_page, "q"+ quiz_id);
                current_page = question_page;
                $(".select-question").removeClass('disabled');

                return false;
            }).fail(function(data) {
                show_message('Không thể lưu đáp án của bạn', 'error');
                disabled_button(0);
                return false;
            });
        }
    });

    function load_questions(page, scroll = null) {
        let text_page = '?page='+ page;
        $.ajax({
            type: 'POST',
            url: quiz_url +'/question-quiz'+ text_page,
            dataType: 'json',
            data: {}
        }).done(function(data) {
            if (data.rows.length <= 0) {
                show_form_submit(1);
                disabled_button(0);
                return false;
            }

            show_form_submit(0);
            let rhtml = '';
            $.each(data.rows, function (i, item) {

                if (qqcategory['num_'+ (item.qindex-1)]) {
                    rhtml += replacement_template(template_qqcategory, {
                        'name': qqcategory['num_'+ (item.qindex-1)],
                        'percent': qqcategory['percent_'+ (item.qindex-1)],
                    });
                }

                let question = item.question;
                let feedback_ques = item.feedback_ques;
                let answer_matching = item.answer_matching;
                let answers = '';
                let correct = '';
                let prompt = '';
                var matching_answer = [];
                if (item.type == 'matching'){
                    let anwsers = item.answers;
                    let index = 0;
                    $.each(anwsers, function (i, a) {
                        matching_answer += '<option value="'+ a.matching_answer +'">' + a.matching_answer +'</option>';
                    });

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_answer_matching, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'option': matching_answer,
                            'matching': a.matching_answer,
                            'correct': (a.matching_answer == answer_matching[i]) ? '<i class="text-success fa fa-check"></i>' : '<i class="text-danger fa fa-times"></i>',
                        });

                        correct += a.title + ' ' + answer_matching[i] + '. ';

                        answers += anwser;
                        index++;
                    });
                    let feedback = '';
                    if (feedback_ques){
                        if (lang === 'en'){
                            feedback += '<div class="card"><div class="card-header bg-info text-white"> General feedback </div>';
                        }else {
                            feedback += '<div class="card"><div class="card-header bg-info text-white"> Phản hồi chung </div>';
                        }
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
                    prompt = (lang === 'en') ? 'Choose an answer:' : 'Chọn một đáp án:';
                    if (item.multiple == 1) {
                        prompt = (lang === 'en') ? 'Select one or more answers:' : 'Chọn một hoặc nhiều đáp án:';
                    }

                    let anwsers = item.answers;
                    let index = 0;
                    let correct = '';
                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_chosen, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'input_type': (item.multiple == 1) ? 'checkbox' : 'radio',
                            'qindex': item.qindex,
                            'checked': (a.selected == 1) ? 'checked' : '',
                            'correct': a.selected == 1 ? (a.correct_answer == 1 || a.percent_answer != 0) ? '<i class="text-success fa fa-check"></i>' : '<i class="text-danger fa fa-times"></i>' : '',
                            'feedback': a.feedback_answer ? a.feedback_answer : '',
                        });

                        if (a.correct_answer == 1 || a.percent_answer > 0){
                            correct += '<textarea type="text" class="form-control" disabled>'+ a.title +'</textarea>';
                        }

                        answers += anwser;
                        index++;
                    });
                    let correct_answer = replacement_template(template_correct_answer, {
                        'correct_answer': correct,
                    });
                    answers += correct_answer;
                }
                if (item.type === 'essay') {
                    let feedback = '';
                    if (feedback_ques){
                        if (lang === 'en'){
                            feedback += '<div class="card"><div class="card-header bg-info text-white"> General feedback </div>';
                        }else {
                            feedback += '<div class="card"><div class="card-header bg-info text-white"> Phản hồi chung </div>';
                        }

                        $.each(feedback_ques, function (i, a) {
                            feedback += '<input type="text" class="form-control" disabled value="'+a+'">';
                        });
                        feedback += '</div>';
                    }
                    answers = replacement_template(template_essay, {
                        'id': item.id,
                        'qid': item.id,
                        'text_essay': (item.text_essay ? item.text_essay : ''),
                        'feedback': feedback,
                    });
                }
                let newtemp = replacement_template(tempalate, {
                    'qid': item.id,
                    'index': item.qindex,
                    'max_score': item.max_score,
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

    function show_form_submit(status) {
        if (status == 1) {
            $("#form-question").hide('slow');
            $(".button-page").hide('slow');
            $("#form-submit").show('slow');
        }
        else {
            $("#form-question").show('slow');
            $(".button-page").show('slow');
            $("#form-submit").hide('slow');
        }
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

    function submit_success(form) {
        form.find('button[type=submit]').prop('disabled', true);
    }

    if (countDownDate)
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var text_time = "";
        if (days > 0) {
            text_time += days + " ngày ";
        }

        if (hours > 0) {
            text_time += hours + " giờ ";
        }

        text_time += minutes + " phút ";
        text_time += seconds + " giây ";

        document.getElementById("clockdiv").innerHTML = text_time;

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("clockdiv").innerHTML = "Hết giờ làm bài";
            disabled_button(1);

            let formData = $("#form-question").serialize();
            $.ajax({
                type: 'POST',
                url: quiz_url + '/save',
                dataType: 'json',
                data: formData
            }).done(function(data) {

                if (data.status === "error") {
                    show_message('Không thể lưu đáp án của bạn', 'error');
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: quiz_url + '/submit',
                    dataType: 'json',
                    data: {}
                }).done(function(data) {

                    if (data.status === "error") {
                        show_message('Không thể lưu bài của bạn', 'error');
                        return false;
                    }

                    window.location = data.redirect;

                    return false;
                }).fail(function(data) {
                    show_message('Không thể lưu đáp án của bạn', 'error');
                    disabled_button(0);
                    return false;
                });

                return false;
            }).fail(function(data) {
                show_message('Không thể lưu đáp án của bạn', 'error');
                disabled_button(0);
                return false;
            });
        }
    }, 1000);
});
