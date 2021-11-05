$(document).ready(function () {

    $("#change-avatar").on('click', function (event) {
        event.preventDefault();
        $("#modal-change-avatar").modal();
        return false;
    });
    $("#change-pass").on('click', function (event) {
        event.preventDefault();
        $("#modal-change-pass").modal();
        return false;
    });

    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#modal-change-avatar .show-demo img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("input[name=selectavatar]").change(function () {
        readURL(this);
    });

    $("#form-change-avatar").on('submit', function (event) {
        event.preventDefault();
        var btn = "#form-change-avatar button[type=submit]";
            btn_text = $(btn).html();
            $(btn).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý');

        $.ajax({
            url: base_url+"/user/change-avatar",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (result) {
                $(btn).prop('disabled', false).html(btn_text);
                if (result.status=='ok'){
                    $("#modal-change-avatar").modal('hide');
                    $('.profile-image img').attr('src',result.img);
                }else{
                    $('#error-msg').html(result.message);
                }
            },error: function (result) {
                $(btn).prop('disabled', false).html(btn_text);
            }
        });
        return false;
    });

    $("#form-change-pass").on('submit', function (event) {
        event.preventDefault();
        var btn = "#form-change-pass button[type=submit]";
        btn_text = $(btn).html();
        $(btn).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý');

        $.ajax({
            url: base_url+"/user/change-pass",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (result) {
                $(btn).prop('disabled', false).html(btn_text);
                if (result.status=='ok'){
                    $("#modal-change-pass").modal('hide');
                    window.location = '';
                }else{
                    $('#error-msg-pass').html(result.message);
                }
            },error: function (result) {
                $(btn).prop('disabled', false).html(btn_text);
            }
        });
        return false;
    });
});
