$(document).ready(function () {

    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $('#subject_id').trigger('change');
    });
});