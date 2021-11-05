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

    $('body').on('change', '.load-unit',function () {
        var loadchild = $(this).data('loadchild');
        var parent_id = $(this).val();

        if (loadchild) {
            $("#"+loadchild).data('parent', parent_id);
            $("#"+loadchild).trigger('change');
        }
    });

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

    $('body').on('change', '.load-area',function () {
        var loadchild = $(this).data('loadchild');
        var parent_id = $(this).val();

        if (loadchild) {
            $("#"+loadchild).data('parent', parent_id);
            $("#"+loadchild).trigger('change');
        }
    });

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
                };

                return query;
            }
        }
    });

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

    $('body').on('change', '.load-training-program',function () {
        var loadsubject = $(this).data('loadsubject');
        var parent_id = $(this).val();

        if (loadsubject) {
            $("#"+loadsubject).data('training-program', parent_id);
            $("#"+loadsubject).trigger('change');
        }
    });

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
                    training_program: $(this).data('training-program')
                };

                return query;
            }
        }
    });

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
});
