$(document).ready(function () {
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