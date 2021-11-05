$(document).ready(function () {
    var table = new BootstrapTable({
        url: $('#bootstraptable').data('url'),
    });
    var form = $('#form-search');
    form.validate({
        ignore: [],
        rules : {
            course : {required : true},
        },
        messages : {
            course : {required : "Chọn khóa học"},
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
    /*$('select[name=course]').select2({
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
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: 1,
                };
                return query;
            }
        }
    });*/
});
