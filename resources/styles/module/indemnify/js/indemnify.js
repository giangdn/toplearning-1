$(document).ready(function() {

    $('#compensated').on('click', function () {
        if ($(this).is(':checked')){
            var compensated = 1;
        }
        else {
            var compensated = 0;
        }

        $.ajax({
            type: 'POST',
            url: save_compensated,
            dataType: 'json',
            data: {
                compensated: compensated,
            },
        }).done(function(data) {
            show_message(data.message, data.status);

            window.location = '';
            return false;
        }).fail(function(data) {

            Swal.fire(
                'Lỗi dữ liệu',
                '',
                'error'
            );
            return false;
        });
    });

    $("#indem").on('change', '.save-contract', function () {
        var contract = $(this).val();
        var course = $(this).data('course');

        $.ajax({
            type: 'POST',
            url: save_contract,
            dataType: 'json',
            data: {
                contract: contract,
                course : course
            },
        }).done(function(data) {
            show_message(data.message, data.status);

            window.location = '';
            return false;
        }).fail(function(data) {

            Swal.fire(
                'Lỗi dữ liệu',
                '',
                'error'
            );
            return false;
        });
    });

    $('#percent').on('change', function () {
        var percent = $(this).val();

        $.ajax({
            type: 'POST',
            url: save_percent,
            dataType: 'json',
            data: {
                percent: percent
            },
        }).done(function(data) {
            show_message(data.message, data.status);

            window.location = '';
            return false;
        }).fail(function(data) {

            Swal.fire(
                'Lỗi dữ liệu',
                '',
                'error'
            );
            return false;
        });
    });

    $('#exemption_amount').on('change', function () {
        var exemption_amount = $(this).val();

        $.ajax({
            type: 'POST',
            url: save_exemption_amount,
            dataType: 'json',
            data: {
                exemption_amount: exemption_amount
            },
        }).done(function(data) {
            show_message(data.message, data.status);

            window.location = '';
            return false;
        }).fail(function(data) {

            Swal.fire(
                'Lỗi dữ liệu',
                '',
                'error'
            );
            return false;
        });
    });

    $('#total_cost').on('change', function () {
        var total_cost = $(this).val();

        $.ajax({
            type: 'POST',
            url: save_total_cost,
            dataType: 'json',
            data: {
                total_cost: total_cost
            },
        }).done(function(data) {
            show_message(data.message, data.status);

            window.location = '';
            return false;
        }).fail(function(data) {

            Swal.fire(
                'Lỗi dữ liệu',
                '',
                'error'
            );
            return false;
        });
    });
});