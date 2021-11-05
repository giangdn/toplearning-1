$(document).ready(function () {
    $('.status').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
            return false;
        }

        $.ajax({
            url: ajax_status,
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            show_message('Trạng thái đã thay đổi', 'success');
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('.result').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
            return false;
        }

        $.ajax({
            url: ajax_view_result,
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            show_message('Xem kết quả đã thay đổi', 'success');
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('.copy').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
            return false;
        }

        $.ajax({
            url: ajax_copy_quiz,
            type: 'post',
            data: {
                ids: ids,
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
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
            return false;
        }

        Swal.fire({
            title: '',
            text: 'Gửi mail cho cấp duyệt yêu cầu duyệt kỳ thi',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url +'/admin-cp/quiz/send-mail-approve',
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
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
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
                    url: base_url +'/admin-cp/quiz/send-mail-change',
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

    $("#send-mail-invitation").on('click', function () {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
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
                    url: base_url +'/admin-cp/quiz/send-mail-invitation',
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
                    show_message('Lỗi dữ liệu', 'error');
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
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