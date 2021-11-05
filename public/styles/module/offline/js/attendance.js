$(document).ready(function () {
    $("#attendance").on('change', '.check-all-type', function() {

        var schedule_id = $('#schedules_id option:selected').val();
        if (schedule_id == ''){
            show_message(
                'Chưa chọn buổi học',
                'error'
            );

            $(this).closest('table').find('tbody').find('.check-item').prop('checked', false);
            $(this).prop("checked", false);
            return false;
        }
        $(this).closest('table').find('tbody').find('.check-item').prop('checked', true);
        var register_id = $('input[name=type]:checked').map(function () {
            return $(this).val();
        }).get();

        $.ajax({
            url: ajax_save_all_register,
            type: 'post',
            data: {
                register_id: register_id,
                schedule_id : schedule_id,
            },

        }).done(function(data) {

            $(this).closest('table').find('tbody').find('.change-percent').val(100);
            $(this).closest('table').find('tbody').find('.change-percent').prop('disabled', false);

            table.refresh();
            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });


    $("#attendance").on('change', '.check-item', function() {

        var register_id = $(this).val();
        var schedule_id = $('#schedules_id option:selected').val();
        var status = $(this).is(':checked') ? 1 : 0;
        var item = $(this);

        $.ajax({
            url: ajax_save_register,
            type: 'post',
            data: {
                register_id: register_id,
                schedule_id : schedule_id,
                status : status,
            },

        }).done(function(data) {
            if(status == 1){
                item.closest('tr').find('.change-percent').val(100);
                item.closest('tr').find('.change-percent').prop('disabled', false);
                return false;
            }else {
                item.closest('tr').find('.change-percent').prop('disabled', true);
                item.closest('tr').find('.change-percent').val(null);
                return false;
            }

            show_message(
                'Chưa chọn buổi học',
                'error'
            );

            $(this).prop("checked", false);
            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    $('#attendance').on('change', '.change-percent', function() {
        var percent = $(this).val();
        var regid = $(this).data('id');

        if(percent < 0 || percent > 100){
            show_message(
                'Phần trăm phải lớn hơn 0 và nhỏ hơn 100',
                'error'
            );

            $(this).val(100);
            return false;
        }

        $.ajax({
            url: ajax_save_percent,
            type: 'post',
            data: {
                percent: percent,
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

    $('#attendance').on('change', '.change-note', function() {
        var note = $(this).val();
        var regid = $(this).data('id');

        $.ajax({
            url: ajax_attendance_save_note,
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

    $('#attendance').on('click', '.import-reference', function() {
        var regid = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: ajax_get_reference,
            dataType: 'html',
            data: {
                'regid': regid,
            },
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #modal-reference").modal();

            return false;
        }).fail(function(data) {

            Swal.fire(
                '',
                'Lỗi dữ liệu',
                'error'
            );
            return false;
        });
    });

    $("#schedules_id").on('change', function() {
        window.location = "?schedule="+ $(this).val();
    });

    $('#attendance').on('change', '.absent', function() {
        var absent_id = $(this).val();
        var regid = $(this).data('regid');

        $.ajax({
            url: ajax_save_absent,
            type: 'post',
            data: {
                absent_id: absent_id,
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

    $('#attendance').on('change', '.absent_reason', function() {
        var absent_reason_id = $(this).val();
        var regid = $(this).data('regid');

        $.ajax({
            url: ajax_save_absent_reason,
            type: 'post',
            data: {
                absent_reason_id: absent_reason_id,
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

    $('#attendance').on('change', '.discipline', function() {
        var discipline_id = $(this).val();
        var regid = $(this).data('regid');

        $.ajax({
            url: ajax_save_discipline,
            type: 'post',
            data: {
                discipline_id: discipline_id,
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
