$(document).ready(function () {
    $(".datepicker-date").datepicker({
        format: "dd/mm/yyyy",
        minViewMode: 0
    });
    $(".datepicker-year").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode:'years'
    });
    $(".datepicker-month").datepicker({
        format: "mm/yyyy",
        viewMode: "months",
        minViewMode:'months'
        // autoclose: true,
    });
});