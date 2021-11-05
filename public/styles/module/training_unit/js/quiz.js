$(document).ready(function () {

    $('.publish').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 kỳ thi', 'error');
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
            show_message('Mở đã thay đổi', 'success');
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

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
});