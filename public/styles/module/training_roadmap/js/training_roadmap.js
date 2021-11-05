$(document).ready(function () {

    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
       /* $("#level_subject_id").empty();
        $("#level_subject_id").data('training-program', training_program_id);
        $('#level_subject_id').trigger('change');*/

        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        /*$("#subject_id").data('level-subject', level_subject_id);*/
        $('#subject_id').trigger('change');
    });

    $('#level_subject_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        var level_subject_id = $('#level_subject_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $("#subject_id").data('level-subject', level_subject_id);
        $('#subject_id').trigger('change');
    });
});
