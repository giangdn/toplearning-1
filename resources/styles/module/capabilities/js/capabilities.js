$(document).ready(function() {
    $('#category_id').on('change', function () {
        var category_id = $('#category_id option:selected').val();
        
        $.ajax({
            type: 'POST',
            url: ajax_get_group_name,
            dataType: 'json',
            data: {
                category_id: category_id
            },
        }).done(function(data) {
            $("#category_group_id").empty();
            $.each(data, function(i, item){
                $("#category_group_id").append('<option value="'+ item.id +'" >'+ item.name +'</option>')            
            });
            $("#category_group_id").triger('change');
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
});