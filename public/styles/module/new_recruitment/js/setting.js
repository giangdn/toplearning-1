$(document).ready(function () {

    $('#title-setting').on('change', '.set-max-date', function () {
        var title_id = $(this).data('id');
        var max_date = $(this).val();

        $.ajax({
            url: save_max_date,
            type: 'post',
            data: {
                max_date: max_date,
                title_id : title_id,
            },

        }).done(function(data) {

            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi dữ liệu',
                'error'
            );
            return false;
        });
    });

    $('#title-setting').on('change', '.set-probation', function () {
        var title_id = $(this).data('id');
        var probation = $(this).val();

        $.ajax({
            url: save_probation,
            type: 'post',
            data: {
                probation: probation,
                title_id : title_id,
            },

        }).done(function(data) {

            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi dữ liệu',
                'error'
            );
            return false;
        });
    });

    $('#title-setting').on('change', '.set-form-evaluate', function () {
        var title_id = $(this).data('id');
        var form_evaluate = $(this).val();

        $.ajax({
            url: save_form_evaluate,
            type: 'post',
            data: {
                form_evaluate: form_evaluate,
                title_id : title_id,
            },

        }).done(function(data) {

            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi dữ liệu',
                'error'
            );
            return false;
        });
    });

});