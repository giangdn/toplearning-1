$(document).ready(function(){
    $('.publish').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 bài viết', 'error');
            return false;
        }
        $.ajax({
            type: 'POST',
            url : ajax_save_status,
            data : {
                ids : ids,
            }
        }).done(function(data) {
            $(table.table).bootstrapTable('refresh');
            return false;

        }).fail(function(data) {
            
            Swal.fire(
                'Lỗi hệ thống',
                '',
                'error'
            );
            return false;
        });
     });
})