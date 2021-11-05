$(document).ready(function () {
    //I. Hướng dẫn hội nhập
    var content_integration_item = $('.content-integration-item').length;
    var content_integration_template = document.getElementById('content-integration-template').innerHTML;
    $("#add-content-integration").on('click', function () {
        content_integration_item += 1;
        let newtemp = replacement_template(content_integration_template, {'index': content_integration_item});
        $("#content-integration-list").append(newtemp);
    });

    $('#content-integration-list').on('click', '.remove-content-integration-item', function(){
        $(this).closest('.content-integration-item').remove();
    });

    //Tập huấn ngiệp vụ
    var content_training_item = $('.content-training-item').length;
    var content_training_template = document.getElementById('content-training-template').innerHTML;
    $("#add-content-training").on('click', function () {
        content_training_item += 1;
        let newtemp = replacement_template(content_training_template, {'index': content_training_item});
        $("#content-training-list").append(newtemp);
    });

    $('#content-training-list').on('click', '.remove-content-training-item', function(){
        $(this).closest('.content-training-item').remove();
    });

    //Làm quen công việc
    var content_work_item = $('.content-work-item').length;
    var content_work_template = document.getElementById('content-work-template').innerHTML;;
    $("#add-content-work").on('click', function () {
        content_work_item += 1;
        let newtemp = replacement_template(content_work_template, {'index': content_work_item});
        $("#content-work-list", 'body').append(newtemp);
    });

    $('#content-work-list', 'body').on('click', '.remove-content-work-item', function(){
        $(this).closest('.content-work-item').remove();
    });

    function replacement_template( template, data ){
        return template.replace(
            /{(\w*)}/g,
            function( m, key ){
                return data.hasOwnProperty( key ) ? data[ key ] : "";
            }
        );
    }

    $("#employees-send").on('click', function () {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        let id = btn.data('user_id');

        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        Swal.fire({
            title: '',
            text: 'Gửi mail cho Trưởng đơn vị duyệt báo cáo?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url +'/admin-cp/new-recruitment/send-mail-unit-approve',
                    type: 'post',
                    data: {
                        user_id: id,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
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

    $("#manager-send").on('click', function () {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        let id = btn.data('user_id');

        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        Swal.fire({
            title: '',
            text: 'Gửi mail cho Phòng nhân sự duyệt báo cáo?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url +'/admin-cp/new-recruitment/send-mail-approve',
                    type: 'post',
                    data: {
                        user_id: id,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
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

});