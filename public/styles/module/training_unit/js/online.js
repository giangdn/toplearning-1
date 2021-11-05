$(document).ready(function () {
    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $('#subject_id').trigger('change');
    });

    $('#subject_id').on('change', function() {
        var subject_id = $('#subject_id option:selected').val();
        var subject_name = $('#subject_id option:selected').text();
        $.ajax({
            url: ajax_get_course_code,
            type: 'post',
            data: {
                subject_id: subject_id,
            },
        }).done(function(data) {
            var d = new Date();
            if(subject_id != null){
                $('#code').val(data.subject_code + "_" + (d.getMonth() + 1) + "_" + d.getFullYear() + "_" + (data.id + 1));
                $("input[name=name]").val(subject_name);
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#has_cert').on('change', function() {
        if($(this).is(':checked')) {
            $("#cert_code").prop('disabled', false);
            $("#has_cert").val(1);
        }
        else {
            $("#cert_code").prop('disabled', true);
            $("#has_cert").val(0);
        }
    });

    $('.publish').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
            return false;
        }

        $.ajax({
            url: ajax_isopen_publish,
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('.approve').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
            return false;
        }

        $.ajax({
            url: base_url +'/admin-cp/training-unit/online/approve',
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'">');
            $("#image-select").val(path);
        });
    });

    $("#select-document").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            $("#document-review").html(path2[path2.length - 1]);
            $("#document-select").val(path);
        });
    });

    $('#action_plan').on('change', function() {

        if($(this).val()==1) {
            $(".contain_plan_app_template").fadeIn();
            $('input[name=plan_app_day]').fadeIn();
        }
        else {
            $("select[name=plan_app_template]").val(0).trigger('change');
            $(".contain_plan_app_template").fadeOut();
            $("input[name=plan_app_day]").val('');
            $('input[name=plan_app_day]').fadeOut();
        }

    }).trigger('change');

    Laraberg.init('content', {
        height: '300px',
        laravelFilemanager: {prefix: '/filemanager'},
        sidebar: true,
    });

});
