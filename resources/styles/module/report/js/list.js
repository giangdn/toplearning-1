$(".report-title").on('click', function () {
    $('.card-body').hide('slow');
    let report_body = $(this).closest('.card-report').find('.card-body');
    if (report_body.is(':hidden')) {
        report_body.show('slow');
    }
    else {
        report_body.hide('slow');
    }
});