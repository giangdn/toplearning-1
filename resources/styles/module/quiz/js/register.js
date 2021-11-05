$(document).ready(function() {
    $('#button-register').on('click', function() {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');
        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        if (ids.length <= 0) {
            setTimeout(function(){ 
                button.find('i').attr('class', icon);
                button.prop("disabled", false); 
            }, 500);

            show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
            return false;
        }

        $('#modal-part').modal();

        button.find('i').attr('class', icon);
        button.prop("disabled", false); 
        
        $('#button-part').on('click', function(){
            var part_id = $('#part option:selected').val();
            let button = $(this);
            let icon = button.find('i').attr('class');
            button.find('i').attr('class', 'fa fa-spinner fa-spin');
            button.prop("disabled", true); 

            if (part_id.length <= 0) {
                show_message('Vui lòng chọn ca thi', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: ajax_get_user,
                data: {
                    ids: ids,
                    part_id: part_id,
                },
            }).done(function(data) {
                
                button.find('i').attr('class', icon);
                button.prop("disabled", false); 

                $('#part').val(null).trigger('change.select2');
                show_message(
                    'Ghi danh thành công',
                    'success'
                );
                $("#modal-part").modal('hide');
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

    $('#button-register-secondary').on('click', function() {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');
        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        if (ids.length <= 0) {
            setTimeout(function(){ 
                button.find('i').attr('class', icon);
                button.prop("disabled", false); 
            }, 500);

            show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
            return false;
        }

        $('#modal-part').modal();

        button.find('i').attr('class', icon);
        button.prop("disabled", false); 
        
        $('#button-part-secondary').on('click', function(){
            var part_id = $('#part-secondary option:selected').val();
            let button = $(this);
            let icon = button.find('i').attr('class');
            button.find('i').attr('class', 'fa fa-spinner fa-spin');
            button.prop("disabled", true); 

            if (part_id.length <= 0) {
                show_message('Vui lòng chọn ca thi', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: ajax_get_user_secondary,
                data: {
                    ids: ids,
                    part_id: part_id,
                },
            }).done(function(data) {
                
                button.find('i').attr('class', icon);
                button.prop("disabled", false); 

                $('#part-secondary').val(null).trigger('change.select2');
                show_message(
                    'Ghi danh thành công',
                    'success'
                );
                $("#modal-part").modal('hide');
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
});