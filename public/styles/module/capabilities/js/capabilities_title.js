$(document).ready(function() {
    $('#category_id').on('change', function () {
        var category_id = $('#category_id option:selected').val();
        
        $.ajax({
            type: 'POST',
            url: ajax_get_capabilities,
            dataType: 'json',
            data: {
                category_id: category_id
            },
        }).done(function(data) {
            $("#capabilities_id").empty();
            $.each(data, function(i, item){
                $("#capabilities_id").append('<option value="'+ item.id +'" >'+ item.code + ' - ' + item.name +'</option>')            
            });
            $("#capabilities_id").triger('change');
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