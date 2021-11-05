$(document).ready(function () {

    $('.save').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var group_permission = $("input[name=group-permission]:checked").map(function(){return $(this).val();}).get();
        var ids_uncheck = $("input[name=btSelectItem]:not(:checked)").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');
        var btn = $(this),
            btn_text =btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        $.ajax({
            url: ajax_save,
            type: 'post',
            data: {
                ids: ids,
                ids_uncheck,
                group_permission: $('form').serializeArray(group_permission)
            }
        }).done(function(result) {
            btn.prop('disabled', false).html(btn_text);
            show_message(result.message);
            // $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            btn.prop('disabled', false).html(btn_text);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

});
