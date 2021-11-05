$(document).ready(function () {

    $('.status').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 đối tượng', 'error');
            return false;
        }

        $.ajax({
            url: base_url +'/admin-cp/libraries/book/status',
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {

            show_message(data.message, data.status)

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
            show_message('Vui lòng chọn ít nhất 1 đối tượng', 'error');
            return false;
        }

        $.ajax({
            url: base_url +'/admin-cp/libraries/book/approve',
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

});
