$(document).ready(function () {
    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $('#subject_id').trigger('change');
    });

    $('#subject_id').on('change', function() {
        var subject_id = $('#subject_id option:selected').val();
        var subject_name = $('#subject_id option:selected').text();
        $.ajax({
            url: ajax_get_course_code,
            type: 'post',
            data: {
                subject_id: subject_id,
            },
        }).done(function(data) {
            var d = new Date();
            if(subject_id != null){
                $('#code').val(data.subject_code + ((d.getMonth() + 1) < 10 ? '0'+(d.getMonth() + 1) : (d.getMonth() + 1)) + d.getFullYear() + (data.id + 1));
                $("input[name=name]").val(subject_name);
            }
            return false;
        }).fail(function(data) {
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    $('#manager-course-child').on('change', '.change-type', function () {
        var course_child = $(this).data('course_child');
        var type = $(this).val();

        $.ajax({
            url: change_type,
            type: 'post',
            dataType: 'html',
            data: {
                course_child: course_child,
                type: type,
            },
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #modal-type").modal();
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#manager-course-child').on('change', '.change-teacher', function () {
        var course_child = $(this).data('course_child');
        var teacher_id = $(this).val();

        $.ajax({
            url: change_teacher,
            type: 'post',
            data: {
                course_child: course_child,
                teacher_id: teacher_id,
            },
        }).done(function(data) {
            show_message(data.message, data.status);
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#manager-course-child').on('change', '.change-start-date', function () {
        var course_child = $(this).data('course_child');
        var start_date = $(this).val();

        $.ajax({
            url: change_start_date,
            type: 'post',
            data: {
                course_child: course_child,
                start_date: start_date,
            },
        }).done(function(data) {
            show_message(data.message, data.status);
            window.location = '';
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#manager-course-child').on('change', '.change-end-date', function () {
        var course_child = $(this).data('course_child');
        var end_date = $(this).val();

        $.ajax({
            url: change_end_date,
            type: 'post',
            data: {
                course_child: course_child,
                end_date: end_date,
            },
        }).done(function(data) {
            show_message(data.message, data.status);
            window.location = '';
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });
});
