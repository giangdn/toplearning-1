$(function () {
    autoHeight('.courses_news .course-description .desc-1');
    autoHeight('.courses_news .course-description h3');
    autoHeight('.courses_news .course-description .desc-2');
    autoHeight('.courses_news .course-description .desc-3');
    
    $('.courses_news #tab-offline').click(function(){
        $(this).addClass('active');
        $('.courses_news #tab-online').removeClass('active');
        $('.tab-content #offline').addClass('active');
        $('.tab-content #online').removeClass('active');
        return false;
    });
    
    $('.courses_news #tab-online').click(function(){
        $(this).addClass('active');
        $('.courses_news #tab-offline').removeClass('active');
        $('.tab-content #online').addClass('active'); 
        $('.tab-content #offline').removeClass('active');
        return false;
    });
});

function autoHeight(el) {
    var highest = 0;
    $(el).each(function () {
        if ($(this).height() > highest)
            highest = $(this).height();
    });
    $(el).height(highest);
}