$(document).ready(function () {

    $("select").on("select2:close", function (e) {
        $(this).valid();
    });

    $('#btnExport').on('click',function (e) {
        e.preventDefault();
        if($("#form-search").valid())
            $(this).closest('form').submit();
        return false
    });
});