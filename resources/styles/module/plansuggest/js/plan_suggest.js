$(document).ready(function () {
    bsCustomFileInput.init();
    $("#intend").datepicker( {
        format: "mm/yyyy",
        startView: "year",
        minViewMode: "months"
    });
    $('#btnDelete, #btnApproved, #btnDeny').on('click', function () {
        let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        if ($("input[name=btSelectItem]:checked").length<=0){
            alert('Chưa chọn dòng cần thực hiện');
            return false;
        }
        if ($(this).val()==3)
            if (!confirm('Bạn có chắc chắn muốn xóa không')) {
                return false;
            }
        var btn = $(this),
            btn_text = btn.html();
        $.ajax({
            type: "POST",
            url: $(this).data('url'),
            dataType: 'json',
            data: {
                'ids': ids
            },
            beforeSend:function(result){
                btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"><i>');
            },
            success: function (result) {
                show_message(result.message, result.status);
                $('.bootstrap-table').bootstrapTable('refresh');
                btn.attr('disabled', false).html(btn_text);
                if (result.status !== "success") {
                    Swal.fire({
                        width:'45%',
                        html: result.content,
                        title: result.message,
                        type:result.status
                    });
                    return false;
                }
            },
            complete: function() {
                btn.attr('disabled', false).html(btn_text);
            },
            error:function (result) {
                btn.attr('disabled', false).html(btn_text);
                console.log(result);
            }
        });

        return false;
    });

    $('#title').on('change', function () {
        let title_ids = $("#title option:selected").map(function(){return $(this).val();}).get();

        $.ajax({
            type: "POST",
            url: ajax_user_by_title,
            dataType: 'json',
            data: {
                'title_ids': title_ids
            },
        }).done(function(data) {
            $("#student").empty();
            $.each(data, function(i, item){
                $("#student").append('<option value="'+ item.user_id +'" >'+ item.full_name +'</option>')
            });
            $("#student").trigger('change');
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
    /*$('button').on('click', function () {
        event.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        var btn = $(this);
        var btn_text = $(this).html();
        if ($(this).attr('name')) {
            formData.append($(this).attr('name'),$(this).val());
        }
        btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>Đang lưu ...');
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: formData,
            cache:false,
            contentType: false,
            processData: false
        }).done(function(data) {
            btn.prop('disabled', false).html(btn_text);
            show_message(
                data.message,
                data.status
            );

            if (data.redirect) {
                setTimeout(function () {
                    window.location = data.redirect;
                }, 1000);
                return false;
            }
            if (data.status === "error") {
                return false;
            }
            return false;
        }).fail(function(data) {
            btn.prop('disabled', false).html(btn_text);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });*/
});