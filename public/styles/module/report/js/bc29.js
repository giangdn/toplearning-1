$(document).ready(function () {
    var table = new BootstrapTable({
        url: $('#bootstraptable').data('url'),
    });
    var form = $('#form-search');

    form.validate({
        ignore: [],
        rules : {
            survey_id : {required : true},
        },
        messages : {
            survey_id : {required : "Chọn kháo sát"},
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($(element).parent());
        },
    });

    $('#btnSearch').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
        {
            $(this).closest('form').submit();
        }
    });
    $("select").on("select2:close", function (e) {
        $(this).valid();
    });
    $('#btnExport').on('click',function (e) {
        e.preventDefault();
        $(this).closest('#form-export').submit();
        return false
    });
});