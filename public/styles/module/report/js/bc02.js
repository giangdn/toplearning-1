$(document).ready(function () {
    var table = new BootstrapTable({
        url: $('#bootstraptable').data('url'),
    });
    var form = $('#form-search');
    form.validate({
        rules : {
            date : {required : true},
        },
        messages : {
            date : {required : "Chọn tháng báo cáo"},
        },
    });
    $('#btnSearch').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
            table.submit();

    });
    $('#btnExport').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
            $(this).closest('form').submit();
        return false
    });
});
