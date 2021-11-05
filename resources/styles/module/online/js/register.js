$(document).ready(function() {
    $('#button-register').on('click', function() {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: ajax_get_user,
            dataType: 'json',
            data: {
                ids: ids
            },
        }).done(function(data) {
            show_message(
                'Ghi danh thành công',
                'success'
            );
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });
});