$(document).ready(function() {
    $('.select2').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $('.select2-default').select2({
        dropdownAutoWidth : true,
        width: '100%',
    });

    $('.select2-json').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: 'auto',
        ajax: {
            url: $(this).data('url'),
            dataType: 'json'
        }
    });
    initSelect2();
    $('body').on('change', '.load-unit',function () {
        var loadchild = $(this).data('loadchild');
        var parent_id = $(this).val();

        if (loadchild) {
            $("#"+loadchild).data('parent', parent_id);
            $("#"+loadchild).trigger('change');
        }
    });
    $('body').on('change', '.load-area',function () {
        var loadchild = $(this).data('loadchild');
        var parent_id = $(this).val();

        if (loadchild) {
            $("#"+loadchild).data('parent', parent_id);
            $("#"+loadchild).trigger('change');
        }
    });
    $('body').on('change', '.load-training-program',function () {
        var loadsubject = $(this).data('loadsubject');
        var parent_id = $(this).val();

        if (loadsubject) {
            $("#"+loadsubject).data('training-program', parent_id);
            $("#"+loadsubject).trigger('change');
        }
    });

    $('.load-coure-bc05').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    course_type:$('select[name=course_type]').val()
                };
                return query;
            }
        }
    });

    $('.load-course-bc07').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: 2,
                };
                return query;
            }
        }
    });

    $('.load-course-bc08').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: 1,
                };
                return query;
            }
        }
    });

    $('.load-course-bc09').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: $('select[name=type]').val(),
                };
                return query;
            }
        }
    });

    $('select[name=teacher]').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'teacher',
                };
                return query;
            }
        }
    });
    $('.load-course-bc43').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: $('select[name=course_type]').val(),
                };
                return query;
            }
        }
    });


});
function initSelect2() {
    load_title_select2();
    load_title_rank_select2();
    load_user_select2();
    load_area_select2();
    load_subject_select2();
    load_level_subject_select2();
    load_unit_select2();
    load_status_profile_select2();
    load_position_select2();
    load_category_new_select2();
    load_category_new_outside_select2();
    load_teacher_select2();
    load_quiz_select2();
    load_quiz_online_select2();
    load_part_quiz_online_select2();
    load_training_form_select2();
    load_quiz_type_select2();
    load_training_object_select2();
    load_training_partner_select2();
    load_teacher_type_select2();
    load_training_type_select2();
    load_group_permission_select2();
    load_training_program_select2();
    load_table_select2();
}
function load_title_select2() {
    $('.load-title').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTitle',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    position_id: $(this).data('position_id'),
                };

                return query;
            }
        }
    });
}
function load_title_rank_select2() {
    $('.load-title-rank').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTitleRank',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_user_select2() {
    $('.load-user').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadUser',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
    $('.load-all-user').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadAllUser',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_area_select2() {
    $('.load-area').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadAreaByLevel',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    level: $(this).data('level'),
                    parent_id: $(this).data('parent'),
                };

                return query;
            }
        }
    });
}
function load_subject_select2() {
    $('.load-subject').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadSubject',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    training_program: $(this).data('training-program'),
                    level_subject_id: $(this).data('level-subject'),
                    course_type: $(this).data('course_type'),
                };

                return query;
            }
        }
    });
}
function load_level_subject_select2(){
    $('.load-level-subject').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadLevelSubject',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    training_program: $(this).data('training-program')
                };

                return query;
            }
        }
    });
}
function load_unit_select2(){
    $('.load-unit').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadUnitByLevel',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    level: $(this).data('level'),
                    parent_id: $(this).data('parent'),
                };

                return query;
            }
        }
    });
}
function load_status_profile_select2(){
    $('.load-status-profile').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadStatusProfile',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_position_select2() {
    $('.load-position').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadPosition',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}

function load_category_new_select2() {
    $('.load-category-new').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadCategoryNew',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}

function load_category_new_outside_select2() {
    $('.load-category-new-outside').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadCategoryNewOutside',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}

function load_teacher_select2() {
    $('.load-teacher').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTeacher',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_quiz_select2() {
    $('.load-quiz').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadQuizCourse',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    course_id:$(this).data('course'),
                };
                return query;
            }
        }
    });
}
function load_quiz_online_select2() {
    $('.load-quiz-online').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadQuizCourseOnline',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    course_id:$(this).data('course'),
                };
                return query;
            }
        }
    });
}
function load_part_quiz_online_select2() {
    $('.load-part-quiz-online').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadPartQuizCourseOnline',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    quiz_id:$(this).data('quiz_id'),
                };
                return query;
            }
        }
    });
}
function load_training_form_select2(){
    $('.load-training-form').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingForm',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_quiz_type_select2() {
    $('.load-quiz-type').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadQuizType',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    training_program: $(this).data('training-program')
                };

                return query;
            }
        }
    });
}
function load_training_object_select2(){
    $('.load-training-object').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingObject',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_training_partner_select2() {
    $('.load-training-partner').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingPartner',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_teacher_type_select2(){
    $('.load-teacher-type').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTeacherType',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_training_type_select2(){
    $('.load-training-type').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingType',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_group_permission_select2(){
    $('.load-group-permission').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadGroupPermission',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_training_program_select2(){
    $('.load-training-program').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingProgram',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_table_select2(){
    $('.load-table').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTable',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };
                return query;
            }
        }
    });
}
