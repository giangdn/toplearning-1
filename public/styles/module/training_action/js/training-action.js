var teacher_template = document.getElementById('score-teacher-template').innerHTML;
var student_template = document.getElementById('score-student-template').innerHTML;

function add_score_teacher(from, to, score, id = '') {
    let item = replace_template(teacher_template, {
        'from': from,
        'to': to,
        'score': score,
        'id': id,
    });
    $('#form-score-teacher').append(item);
}

function add_score_student(from, to, score, id = '') {
    let item = replace_template(student_template, {
        'from': from,
        'to': to,
        'score': score,
        'id': id,
    });
    $('#form-score-student').append(item);
}

$('.add-new-score-teacher').on('click', function () {
    add_score_teacher('', '', '');
});

$('.add-new-score-student').on('click', function () {
    add_score_student('', '', '');
});

$('body').on('click', '.remove-score-item', function () {
    $($(this).closest('.row-item').remove());
});

if ($('input[name=id]').val() > 0) {

    $.ajax({
        type: "GET",
        url: ajax_object.get_scores_url,
        dataType: 'json',
        data: {
            'id': $('input[name=id]').val(),
            'type': 1,
        },
        success: function (result) {
            $.each(result, function (index, item) {
                add_score_teacher(item.from, item.to, item.score, item.id);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: ajax_object.get_scores_url,
        dataType: 'json',
        data: {
            'id': $('input[name=id]').val(),
            'type': 2,
        },
        success: function (result) {
            $.each(result, function (index, item) {
                add_score_student(item.from, item.to, item.score, item.id);
            });
        }
    });

}
else {

    if ($('#form-score-teacher .row-item').length <= 0) {
        add_score_teacher('', '', '');
    }

    if ($('#form-score-student .row-item').length <= 0) {
        add_score_student('', '', '');
    }

}