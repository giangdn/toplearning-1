$(function () {
    $('button[type=submit]').on('click', function () {
        event.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        var btn = $(this);
        var btn_text = $(this).html();
        // btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
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
    });
    $('.add_item').on('click',function (e) {
        let instance = $(this).data('cate');
        e.preventDefault();
        let html="";
        html+='<tr>\
            <td><input type="text" class="form-control" name="name['+instance+'][]"></td>\
            <td><input type="text" class="form-control" name="item_1['+instance+'][]"></td>\
            <td><input type="text" class="form-control" name="item_2['+instance+'][]"></td>\
            <td>\
                <input type="text" class="form-control" name="item_3['+instance+'][]">\
            </td>\
            <td><a href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>\
            </tr>';
        $('.tblBinding_'+instance+' tbody').append(html);
    });
    $('table').on('click','a',function (e) {
        let id =parseInt($(this).data('id')),
            $this=$(this);
        if (isNaN(id))
            $(this).closest('tr').remove();
        else {
            let icon = $(this),
                origin_icon = icon.html();
            icon.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'post',
                url: base_url + '/plan-app/delete',
                dataType: 'json',
                data: {'id': id}
            }).done(function (data) {
                $this.closest('tr').remove();
                icon.prop('disabled', false).html(origin_icon);
            }).fail(function (data) {
                icon.prop('disabled', false).html(origin_icon);
                return false;
            });
        }
    })
})
