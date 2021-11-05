$(document).on("turbolinks:load", function() {

    $('body').on('click', '.teacher-register', function () {
        let btn = $(this);
        let icon = btn.find('i').attr('class');
        let id = $(this).data('id');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '/training-action/register-teacher',
            dataType: 'json',
            data: {
                'training_action_id': id,
            }
        }).done(function (data) {

            btn.find('i').attr('class', icon);
            btn.prop('disabled', false);

            if (data.status === "error") {
                show_message(data.message, 'error');
                return false;
            }

            btn.prop('disabled', true);
            btn.html('<i class="fa fa-check-circle"></i> Đã đăng ký');

            return false;
        }).fail(function (data) {
            btn.find('i').attr('class', icon);
            btn.prop("disabled", false);
            return false;
        });
    });

    $('body').on('click', '.student-register', function () {
        let btn = $(this);
        let icon = btn.find('i').attr('class');
        let id = $(this).data('id');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '/training-action/register-student',
            dataType: 'json',
            data: {
                'training_action_id': id,
            }
        }).done(function (data) {

            btn.find('i').attr('class', icon);
            btn.prop('disabled', false);

            if (data.status === "error") {
                show_message(data.message, 'error');
                return false;
            }

            btn.prop('disabled', true);
            btn.html('<i class="fa fa-check-circle"></i> Đã đăng ký');

            return false;
        }).fail(function (data) {
            btn.find('i').attr('class', icon);
            btn.prop("disabled", false);
            return false;
        });
    });

});
