$(document).ready(function(){

    $('body').on('submit', '.form-ajax', function(event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        var btnsubmit = form.find("button:focus");
        var oldText = btnsubmit.text();
        var currentIcon = btnsubmit.find('i').attr('class');
        var submitSuccess = form.data('success');
        var exists = btnsubmit.find('i').length;
        if (exists>0)
            btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        else
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);

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

            if (exists>0)
                btnsubmit.find('i').attr('class', currentIcon);
            else
                btnsubmit.html(oldText);
            btnsubmit.prop("disabled", false);

            if (data.status === "error") {
                return false;
            }

            if (submitSuccess) {
                eval(submitSuccess)(form);
            }

            return false;
        }).fail(function(data) {
            if (exists>0)
                btnsubmit.find('i').attr('class', currentIcon);
            else
                btnsubmit.html(oldText);
            btnsubmit.prop("disabled", false);

            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    $('body').on('click', '.load-modal', function () {
        let item = $(this);
        let url = $(this).data('url');
        let icon = item.find('i').attr('class');

        item.find('i').attr('class', 'fa fa-spinner fa-spin');
        item.prop("disabled", true);
        item.addClass('disabled');

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'html',
            data: {},
        }).done(function(data) {
            item.find('i').attr('class', icon);
            item.prop("disabled", false);
            item.removeClass('disabled');
            $("#app-modal").html(data);
            $("#app-modal #myModal").modal();
        }).fail(function(data) {
            item.find('i').attr('class', icon);
            item.prop("disabled", false);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    $('body').on('click', '.load-media', function () {
        let item = $(this);
        let icon = item.find('i').attr('class');

        item.find('i').attr('class', 'fa fa-spinner fa-spin');

        $.ajax({
            type: 'POST',
            url: base_url + '/load-media',
            dataType: 'html',
            data: {},
        }).done(function(data) {
            item.find('i').attr('class', icon);
            $("#app-modal").html(data);
            $("#app-modal #modal-media").modal();
        }).fail(function(data) {
            item.find('i').attr('i', icon);
            show_message(
                'Lỗi dữ liệu',
                'error'
            );
            return false;
        });
    });

    $("body").on('keypress', '.is-number', function () {
        return validate_isNumberKey(this);
    });

    $("body").on('keyup', '.number-format', function () {
        return validate_FormatNumber(this);
    });

    function validate_isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode == 59 || charCode == 46)
            return true;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function validate_FormatNumber(a) {
        a.value = a.value.replace(/\./gi, "");
        a.value = a.value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    }

});
function load_modal(url,element) {
    let item = $(element);
    let icon = item.find('i').attr('class');

    item.find('i').attr('class', 'fa fa-spinner fa-spin');
    item.prop("disabled", true);
    item.addClass('disabled');

    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'html',
        data: {},
    }).done(function(data) {
        item.find('i').attr('class', icon);
        item.prop("disabled", false);
        item.removeClass('disabled');
        $("#app-modal").html(data);
        $("#app-modal #myModal").modal();
    }).fail(function(data) {
        item.find('i').attr('class', icon);
        item.prop("disabled", false);
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}
function load_modal_approve_note(model,element) {
    let item = $(element);
    let icon = item.find('i').attr('class');

    item.find('i').attr('class', 'fa fa-spinner fa-spin');
    item.prop("disabled", true);
    item.addClass('disabled');

    $.ajax({
        type: 'POST',
        url: base_url +'/admin-cp/approve/modal-note-approved',
        dataType: 'html',
        data: {model},
    }).done(function(data) {
        item.find('i').attr('class', icon);
        item.prop("disabled", false);
        item.removeClass('disabled');
        $("#app-modal").html(data);
        $("#app-modal #myModal").modal();
    }).fail(function(data) {
        item.find('i').attr('class', icon);
        item.prop("disabled", false);
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}
