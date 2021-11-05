$(document).ready(function () {
    var type = $('#type').val();

    if(type == 1){
        $('#training_partner').hide();
    }else {
        $('#training_partner').show();
    }

    $('#type').on('change', function() {
        var item = $('#type option:selected').val();

        if(item == 1){
            $('#form-internal').show();
            $('#training_partner').hide();
        }else{
            $('#form-internal').hide();
            $('#training_partner').show();
            $('#user_id').empty();
            $('#code').val('');
            $('#name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#unit').val('');
            $('#title').val('');
        }
    });
    $('#user_id').on('change', function() {
        var ids = $('#user_id option:selected').val();
        $.ajax({
            url: ajax_get_user,
            type: 'post',
            data: { 
                ids: ids,
            },
            success: function (ids) {
                return false;
            }
        }).done(function(data) {
            $('#code').val(data.code);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#unit').val(data.unit);
            $('#title').val(data.title);

            //table.refresh();
            return false;
        }).fail(function(data) {
            
            Swal.fire(
                '',
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });
});