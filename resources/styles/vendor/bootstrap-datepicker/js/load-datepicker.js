$(document).ready(function(){

    $(".datepicker").datepicker({
        format: "dd/mm/yyyy",
        language: "vi"
    });

    $(".datepicker-month").datepicker({
        format: "mm/yyyy",
        language: "vi"
    });

    $(".datepicker-year").datepicker({
        format: "yyyy",
        language: "vi"
    });
});