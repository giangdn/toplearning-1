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
            teacher_type : {required : true},
        },
        messages : {
            from_date : {required : "Chọn thời gian bắt đầu"},
            to_date : {required : "Chọn thời gian kết thúc"},
            teacher_type : {required : "Chọn hình thức"},
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
    /*$('select[name=teacher]').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'teacher',
                };
                return query;
            }
        }
    });*/
});
