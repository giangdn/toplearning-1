$(document).ready(function () {

    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        /*$("#level_subject_id").empty();
        $("#level_subject_id").data('training-program', training_program_id);
        $('#level_subject_id').trigger('change');*/

        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
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

    $('#subject_id').on('change', function() {
        var subject_id = $('#subject_id option:selected').val();
        var subject_name = $('#subject_id option:selected').text();
        var id = $('input[name=id]').val()
        $.ajax({
            url: ajax_get_course_code,
            type: 'post',
            data: {
                subject_id: subject_id,
                id: id,
            },
        }).done(function(data) {
            var d = new Date();
            if(subject_id != null){
                $('#code').val(data.course_code);
                $("input[name=name]").val(subject_name);
                $('#level_subject').val(data.level_subject_name).trigger('change');
                $('#description').text(data.description);
                CKEDITOR.instances['content'].setData(data.content);
            }
            return false;
        }).fail(function(data) {

            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    $('#has_cert').on('change', function() {
        if($(this).is(':checked')) {
            $("#cert_code").prop('disabled', false);
            $("input[name=has_cert]").val(1);
        }
        else {
            $("#cert_code").prop('disabled', true);
            $("input[name=has_cert]").val(0);
        }
    });

    $('#commit').on('change', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
            $("input[name=commit_date]").prop('disabled',false).fadeIn();
            $("input[name=coefficient]").prop('disabled',false).fadeIn();
        }
        else {
            $(this).val(0);
            $("input[name=commit_date]").fadeOut();
            $("input[name=commit_date]").val('');
            // $("input[name=commit_date]").prop('disabled',true).fadeOut();

            $("input[name=coefficient]").fadeOut();
            $("input[name=coefficient]").val('');
            // $("input[name=coefficient]").prop('disabled',true).fadeOut();
        }
    });

    $('.approve').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
            return false;
        }

        $.ajax({
            url: base_url +'/admin-cp/offline/approve',
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

    $("#send-mail-approve").on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
            return false;
        }

        Swal.fire({
            title: '',
            text: 'Gửi mail cho cấp duyệt yêu cầu duyệt khóa học',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url +'/admin-cp/offline/send-mail-approve',
                    type: 'post',
                    data: {
                        ids: ids,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    table.refresh();
                    return false;
                }).fail(function(data) {
                    show_message('Lỗi hệ thống', 'error');
                    return false;
                });
            }
        });
    });

    $("#send-mail-change").on('click', function () {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
            return false;
        }

        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        Swal.fire({
            title: '',
            text: 'Gửi mail báo khóa học đã được thay đổi?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url +'/admin-cp/offline/send-mail-change',
                    type: 'post',
                    data: {
                        ids: ids,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                    table.refresh();
                    return false;
                }).fail(function(data) {
                    show_message('Lỗi hệ thống', 'error');
                    return false;
                });
            }
            else {
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
            }
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

        lfm({type: 'file'}, function (url, path) {
            var path2 =  path.split("/");
            $("#document-review").html(path2[path2.length - 1]);
            $("#document-select").val(path);
        });
    });

    $('#action_plan').on('change', function() {

        if($(this).val()==1) {
            // $("select[name=plan_app_template]").next(".select2-container").fadeIn();
            $(".contain_plan_app_template").fadeIn();
            $('input[name=plan_app_day]').fadeIn();
        }
        else {
            $("select[name=plan_app_template]").val(0).trigger('change');
            // $("select[name=plan_app_template]").next(".select2-container").fadeOut();
            $(".contain_plan_app_template").fadeOut();
            $("input[name=plan_app_day]").val('');
            $('input[name=plan_app_day]').fadeOut();
        }

    }).trigger('change');

    $('select[name=province]').on('change',function (e) {
        var url = $(this).data('url');
        $.get(url,{province_id:$(this).val()})
            .done(function(result){
                if (result && result.length) {
                    var data = [{ id:'',text:'Chọn quận huyện'}];
                    $.each(result, function (index, obj) {
                        data.push({
                            id: obj.id,
                            text: obj.name,
                        });
                    });
                    $('select[name=district]').empty().select2({
                        data: data,
                        width: '100%',
                    });
                }
        });
        loadTranginingLocation($(this).val(),0)
    });

    $('select[name=district]').on('change',function (e) {
        console.log($(this).val());
        loadTranginingLocation($('select[name=province]').val(),$(this).val())
    });

    function loadTranginingLocation(province,district){
        data ={province_id:province,district_id:district};
        $.ajax({
            type: "GET",
            url: $('select[name=training_location_id]').data('url'),
            dataType: 'json',
            data: data,
            success: function (result) {
                var data=[];
                $.each(result, function (index, obj) {
                    data.push({
                        id: obj.id,
                        text: obj.name,
                    });
                });
                $('select[name=training_location_id]').empty().select2({
                    data: data,
                    width: '100%',
                }).val('').trigger('change');
            }
        });
    }

    // Laraberg.init('content', {
    //     height: '300px',
    //     laravelFilemanager: {prefix: '/filemanager'},
    //     sidebar: true,
    // });

    // SAO CHÉP KHÓA HỌC
    $('.copy').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
            return false;
        }

        $.ajax({
            url: base_url +'/admin-cp/offline/copy',
            type: 'post',
            data: {
                ids: ids,
            }
        }).done(function(data) {
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
});
