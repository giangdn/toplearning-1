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
            error.appendTo($("." + name));
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
    $('select[name=course_type]').on('change',function(){
        $('select[name=course]').empty();
    }); 
    $('select[name=course]').select2({
        dropdownAutoWidth : true,
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
                    course_type:$('select[name=course_type]').val()
                };
                return query;
            }
        }
    });
});