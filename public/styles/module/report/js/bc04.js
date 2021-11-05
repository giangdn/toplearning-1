$(document).ready(function () {

    var table = new BootstrapTable({
        url: $('#bootstraptable').data('url'),
    });
    var form = $('#form-search');
    form.validate({
        rules : {
            fromDate : {required : true},
            toDate : {required : true},
            // training_from:{required : true}
        },
        messages : {
            fromDate : {required : "Nhập ngày bắt đầu"},
            toDate : {required : "Nhập ngày kết thúc"},
            // training_from : {required : "Chọn hình thức đào tạo"}
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