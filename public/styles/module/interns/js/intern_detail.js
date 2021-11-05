$(document).ready(function() {
    $('#unit_id').on('change', function () {
        var unit_id = $('#unit_id option:selected').val();
        
        $.ajax({
            type: 'POST',
            url: ajax_get_type_unit,
            dataType: 'json',
            data: {
                unit_id: unit_id
            },
        }).done(function(data) {
            $("#type").val(data.name);
            return false;

        }).fail(function(data) {
            
            Swal.fire(
                'Lỗi hệ thống',
                '',
                'error'
            );
            return false;
        });
    });
    $('#unit_to').on('change', function () {
        var unit_to = $('#unit_to option:selected').val();
        console.log(unit_to);

        $.ajax({
            type: 'POST',
            url: ajax_get_type_unit_to,
            dataType: 'json',
            data: {
                unit_to: unit_to
            },
        }).done(function(data) {
            $("#type_to").val(data.name);
            return false;

        }).fail(function(data) {
            
            Swal.fire(
                'Lỗi hệ thống',
                '',
                'error'
            );
            return false;
        });
    });
    $('#intern_id').on('change', function () {
        var intern_id = $('#intern_id option:selected').val();
        console.log(intern_id);

        $.ajax({
            type: 'POST',
            url: ajax_get_intern_detail,
            dataType: 'json',
            data: {
                intern_id: intern_id
            },
        }).done(function(data) {
            $("#start_date").datepicker('setDate', data.start_date);
            $("#end_date").datepicker('setDate', data.end_date);
            $("#year").val(data.year);
            $("#school").val(data.school);
            $("#faculty").val(data.faculty);

            var newOption = new Option(data.title_name, data.title_id, false, false);
            $('#title_id').empty();
            $('#title_id').append(newOption).trigger('change');
            return false;

        }).fail(function(data) {
            
            Swal.fire(
                'Lỗi hệ thống',
                '',
                'error'
            );
            return false;
        });
    });

});