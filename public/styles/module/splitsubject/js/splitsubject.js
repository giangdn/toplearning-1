$(document).ready(function () {
    $('.approve').on('click', function () {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 chuyên đề', 'error');
            return false;
        }
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        $.ajax({
            url: base_url +'/admin-cp/splitsubject/approve',
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });
});
