$(document).ready(function () {
    $('#result').on('change', '.change-score', function() {
        var score = $(this).val();
        var regid = $(this).data('id');

        $.ajax({
            url: ajax_save_score,
            type: 'post',
            data: { 
                score: score,
                regid : regid,
            },
        }).done(function(data) {

            if (data.status === "error") {
                show_message(data.message, 'error');
                return false;
            }

            window.location = '';
            return false;
            
        }).fail(function(data) {
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    $('#result').on('change', '.change-note', function() {
        var note = $(this).val();
        var regid = $(this).data('id');

        $.ajax({
            url: ajax_result_save_note,
            type: 'post',
            data: { 
                note: note,
                regid : regid,
            },
            
        }).done(function(data) {
            
            return false;
            
        }).fail(function(data) {
            
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });
});