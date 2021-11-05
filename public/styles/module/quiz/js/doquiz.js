$(document).ready(function() {
    var tempalate = document.getElementById('question-template').innerHTML;
    var template_chosen = document.getElementById('answer-template-chosen').innerHTML;
    var template_essay = document.getElementById('answer-template-essay').innerHTML;
    var template_qqcategory = document.getElementById('qqcategory-template').innerHTML;
    var template_correct_answer = document.getElementById('correct-answer-template-chosen').innerHTML;
    var template_answer_matching = document.getElementById('answer-template-matching').innerHTML;
    var template_matching_feedback = document.getElementById('matching-feedback-template').innerHTML;
    var template_fill_in = document.getElementById('fill-in-template').innerHTML;
    var template_fill_in_correct = document.getElementById('fill-in-correct-template').innerHTML;

    var answer_text = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
    var current_page = parseInt(get_query_string('page'));

    if (current_page < 1 || isNaN(current_page)) {
        current_page = 1;
    }

    pageloadding(1);
    load_questions(current_page);

    $('#questions').on('click','a.flag', function (e) {
        var $this = $(this);
        let question_id = $(this).data('id');

        var date_flag = parseInt($this.data('flag'));
        var flag = (date_flag == 0 || isNaN(date_flag)) ? 1 : 0;

        var html = "", question_flag="" ;
        if (flag==1) {
            html = '<i class="fa fa-flag fa-flag-red" aria-hidden="true"></i>';
            question_flag = 'flag-item';
        }
        else{
            html='<i class="fa fa-flag fa-flag" aria-hidden="true"></i>';
            question_flag = '';
        }
        $.ajax({
            type: 'POST',
            url: quiz_url + '/saveflag',
            dataType: 'json',
            data: {
                question_id: question_id,
                flag: flag
            }
        }).done(function(data) {
            if (data.status === "success") {
                $this.html(html);
                $this.attr('data-flag', flag);
                $('#select-q'+question_id+ ' div').attr('class',question_flag);
            }

        }).fail(function(data) {
            show_message('Không thể lưu đáp án của bạn', 'error');
            return false;
        });
    });

    $("#questions").on('change', '.selected-answer', function () {
        let question_id = $(this).closest('.question-item').data('qid');
        $("#select-q"+ question_id).addClass('question-selected');

        var numItems = $("#select-q"+ question_id).closest('.card-body').find('.question-selected').length;

        $('#num-question-selected').html(numItems);
    });

    $("#questions").on('change', '.file-essay', function () {
        let question_id = $(this).closest('.question-item').data('qid');
        let file_path = $("#qf_"+question_id).prop('files')[0];

        var form_data = new FormData();
        form_data.append('file_path', file_path);
        form_data.append('question_id', question_id);

        $.ajax({
            type: 'POST',
            url: quiz_url + '/save/file',
            dataType: 'text',
            data: form_data,
            contentType: false,
            processData: false,
        }).done(function(data) {

            if (data.status === "error") {
                show_message('Không thể lưu đáp án của bạn', 'error');
                return false;
            }

            return false;
        }).fail(function(data) {
            show_message('Không thể lưu đáp án của bạn', 'error');
            disabled_button(0);
            return false;
        });
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
                // window.location = quiz_url + "?page=" + current_page;
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

    $('.send-quiz').on('click', function () {
        if(context){
            context.drawImage(video, 0, 0, 640, 480);
            var image = document.getElementById("canvas").toDataURL("image/png");

            $.ajax({
                type: 'POST',
                url: quiz_url + '/save-webcam',
                data: {
                    'image': image,
                },
                dataType: 'json',
                success: function (msg) {
                    console.log(msg);
                }
            });
        }
    });

    $('#quiz-content').on('click', '.send-quiz', function () {
        var question_selected = $("#quiz-content").find('#info-number-question').find('.question-selected').length;
        var numItems = total_question - question_selected;
        var text = 'Bạn chắc chắn muốn nộp bài không?';
        if(numItems > 0){
            text = 'Còn lại '+numItems+ ' câu chưa làm. Bạn chắc chắn muốn nộp bài không?'
        }

        Swal.fire({
            title: '',
            text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                var form = $(this).closest('form');
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    data: {},
                    cache:false,
                    contentType: false,
                    processData: false
                }).done(function(data) {

                    if (data.status == 'error'){
                        Swal.fire({
                            type: data.status,
                            html: data.message,
                            focusConfirm: false,
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.value) {
                                if (data.redirect) {
                                    window.location = data.redirect
                                }
                                return false;
                            }
                        });
                    }else{
                        show_message(
                            data.message,
                            data.status
                        );

                        if (data.redirect) {
                            setTimeout(function () {
                                window.location = data.redirect;
                            }, 1000);
                            return false;
                        }
                    }

                }).fail(function(data) {
                    show_message('Lỗi dữ liệu','error');
                    return false;
                });
            }
        });
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
                if (item.type == 'matching'){
                    let anwsers = item.answers;
                    let index = 0;
                    let selected = '';
                    $.each(anwsers, function (i, a) {
                        matching_answer += '<option value="'+ a.matching_answer +'" >' + a.matching_answer +'</option>';
                    });

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_answer_matching, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index] + '. ',
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
                            'index_text': answer_text[index] + (a.title != null ? '. ' : ''),
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
                                correct += '<textarea type="text" class="form-control" disabled>'+ (a.title != null ? a.title : '') +'</textarea>';
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
                    let feedback = '';
                    if (feedback_ques){
                        feedback += '<div class="card"><div class="card-header bg-info text-white"> Phản hồi chung </div>';
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
                        'file_essay': (item.file_essay ? item.file_essay : ''),
                        'link_file_essay': (item.file_essay ? item.link_file_essay : ''),
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
                            'index_text': anwsers.length > 2 ? answer_text[index] + '. ' : '',
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
                            'index_text': anwsers.length > 2 ? answer_text[index] + '. ' : '',
                            'title': a.title,
                            'qindex': item.qindex,
                            'text_essay': item.text_essay ? (item.text_essay[i] ? item.text_essay[i] : '') : '',
                            'feedback': a.feedback_answer ? '<textarea type="text" class="form-control" disabled>'+a.feedback_answer+'</textarea><p></p>' : '',
                        });

                        answers += anwser;
                        index++;
                    });
                }

                let newtemp = replacement_template(tempalate, {
                    'qid': item.id,
                    'index': (page > 1) ? ((i + 1) + (parseInt(questions_perpage) * (page - 1))) : (i + 1),
                    'max_score': item.max_score,
                    'name': item.name,
                    'answers': answers,
                    'prompt': prompt,
                    'class_flag': (item.flag && item.flag==1?"fa-flag-red":""),
                    'flag': item.flag ? item.flag : 0,
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

            $('video').prop('controlsList', 'nodownload');

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
    var ticker = 0;
    if (countDownDate)
        var x = setInterval(function() {
            ticker++;
            var now = new Date(timeServer.getTime()+ ticker*1000);
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (enddate){
                var end_time = enddate - now;
            }

            if (times_shooting_webcam){
                if (now.getTime() == (startTime.getTime() + times_shooting_webcam) || distance == 0 || (end_time && end_time == 0)){
                    context.drawImage(video, 0, 0, 640, 480);
                    var image = document.getElementById("canvas").toDataURL("image/png");

                    $.ajax({
                        type: 'POST',
                        url: quiz_url + '/save-webcam',
                        data: {
                            'image': image,
                        },
                        dataType: 'json',
                        success: function (msg) {
                            console.log(msg);
                        }
                    });

                    times_shooting_webcam += times_shooting_webcam;
                }
            }

            if (times_shooting_question){
                if (now.getTime() == (startTime.getTime() + times_shooting_question)){
                    $("#modal-check-user-question").modal();

                    times_shooting_question += times_shooting_question;
                }
            }

            var text_time = "";
            if (days > 0) {
                text_time += days + " ngày ";
            }

            if (hours > 0) {
                text_time += hours + " giờ ";
            }

            text_time += minutes + " phút ";
            text_time += seconds + " giây ";

            document.getElementById("clockdiv").innerHTML = text_time!=null?text_time:'';

            if (distance < 0 || (end_time && end_time <= 0)) {
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

    /****** refresh token csrf********/
    function refreshToken(){
        $.ajax({
            url: base_url+ '/refresh-csrf',
            method: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).then(function (d) {
            $('meta[name="csrf-token"]').attr('content', d);
        });
    }
    setInterval(refreshToken, session_time*60*1000); // 1 phut

    $('#check-user').on('click', function () {
        $.ajax({
            url: quiz_url + '/check-info-user',
            type: 'post',
            data: $('#check-user-question').serialize(),
        }).done(function (data) {
            if (data.status == 'error') {
                $.ajax({
                    url: quiz_url + '/save-error-user',
                    type: 'post',
                    data: {},
                }).done(function (data) {
                    if (data.status == 'error') {
                        show_message(data.message, data.status);
                    }
                    if (data.attempt == 3){
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
                    return false;
                }).fail(function (data) {
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });
            }else {
                var arr_key = ['code', 'identity_card', 'month', 'day', 'year','join_company','phone','unit_code','title_code'];
                var arr_text = [
                    'Mã số NV của bạn là gì',
                    'CMND của bạn',
                    'Bạn sinh vào tháng mấy',
                    'Bạn sinh vào ngày mấy',
                    'Bạn sinh vào năm mấy',
                    'Ngày bạn vào làm là ngày nào',
                    'Số điện thoại của bạn',
                    'Lựa chọn Đơn vị trực tiếp bạn đang làm việc',
                    'Lựa chọn Chức danh của bạn',
                ];

                var random = Math.floor(Math.random() * arr_key.length);
                $('#modal-check-user-question').find('input[name=key]').val(arr_key[random]);
                $('#modal-check-user-question').find('.title').html(arr_text[random]);

                if (arr_key[random] != 'unit_code'){
                    $('#unit').next(".select2-container").hide();
                    $('#unit').attr('name', '');
                }else {
                    $('#unit').next(".select2-container").show();
                    $('#unit').attr('name', 'answer');
                }

                if (arr_key[random] != 'title_code'){
                    $('#title').next(".select2-container").hide();
                    $('#title').attr('name', '');
                }else {
                    $('#title').next(".select2-container").show();
                    $('#title').attr('name', 'answer');
                }

                if (arr_key[random] == 'unit_code' || arr_key[random] == 'title_code'){
                    $('#question_orther').prop('hidden', true);
                    $('#question_orther').val('').trigger('change');
                    $('#question_orther').attr('name', '');
                }else{
                    $('#question_orther').prop('hidden', false);
                    $('#question_orther').attr('name', 'answer');
                }

                if(arr_key[random] == 'join_company'){
                    $('#question_orther').addClass('datepicker');
                }else{
                    $('#question_orther').removeClass('datepicker');
                    $('#question_orther').val('').trigger('change');
                }

                $("#modal-check-user-question").modal('toggle');
            }

        }).fail(function (data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    var key_question = $('#modal-check-user-question input[name=key]').val();
    if (key_question != 'unit_code'){
        $('#unit').next(".select2-container").hide();
        $('#unit').attr('name', '');
    }else {
        $('#unit').next(".select2-container").show();
        $('#unit').attr('name', 'answer');
    }

    if (key_question != 'title_code'){
        $('#title').next(".select2-container").hide();
        $('#title').attr('name', '');
    }else {
        $('#title').next(".select2-container").show();
        $('#title').attr('name', 'answer');
    }

    if (key_question == 'unit_code' || key_question == 'title_code'){
        $('#question_orther').prop('hidden', true);
        $('#question_orther').val('').trigger('change');
        $('#question_orther').attr('name', '');
    }else{
        $('#question_orther').prop('hidden', false);
        $('#question_orther').attr('name', 'answer');
    }

    if(key_question == 'join_company'){
        $('#question_orther').addClass('datepicker');
    }else{
        $('#question_orther').removeClass('datepicker');
    }
});
