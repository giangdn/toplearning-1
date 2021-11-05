$(document).ready(function () {
    var table = new BootstrapTable({
        url: $('#bootstraptable').data('url'),
    });
    var form = $('#form-search');
    form.validate({
        ignore: [],
        rules : {
            from_date : {required : true},
            to_date : {required : true},
            quiz_id : {required : true},
        },
        messages : {
            from_date : {required : "Chọn thời gian bắt đầu"},
            to_date : {required : "Chọn thời gian kết thúc"},
            quiz_id : {required : "Chọn kỳ thi"},
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($(element).parent());
        },
    });
    $('#btnSearch').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
            table.submit();

    });
    $("select").on("select2:close", function (e) {
        $(this).valid();
    });
    $('#btnExport').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
            $(this).closest('form').submit();
        return false
    });
});