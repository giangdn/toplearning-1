$(document).ready(function () {
    var table = new BootstrapTable({
        url: $('#bootstraptable').data('url'),
    });
    var form = $('#form-search');
    form.validate({
        rules : {
            fromDate : {required : true},
            toDate : {required : true},
        },
        messages : {
            fromDate : {required : "Nhập ngày bắt đầu"},
            toDate : {required : "Nhập ngày kết thúc"},
        },
    });
    $('#btnSearch').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
            table.submit();

    })
    $('#btnExport').on('click',function (e) {
        e.preventDefault();
        if(form.valid())
            $(this).closest('form').submit();
        return false
    })
});