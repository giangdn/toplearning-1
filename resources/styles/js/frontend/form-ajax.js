$(document).on("turbolinks:load", function() {
    $('body').on('submit', '.form-ajax', function(event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        var btnsubmit = form.find("button:focus");
        var currentIcon = btnsubmit.find('i').attr('class');
        var submitSuccess = form.data('success');
        btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        btnsubmit.prop("disabled", true);

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: formData,
            cache:false,
            contentType: false,
            processData: false
        }).done(function(data) {
            if (data.status == 'error'){
                Swal.fire({
                    type: data.status,
                    html: data.message,
                    focusConfirm: false,
                    confirmButtonText: "OK",
                }).then((result) => {
                    if (result.value) {
                        if (data.redirect) {
                            window.location = data.redirect
                        }
                        return false;
                    }
                });
            }else{
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
            }

            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            if (data.status === "error") {
                return false;
            }

            if (submitSuccess) {
                eval(submitSuccess)(form);
            }

            return false;
        }).fail(function(data) {
            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
});
