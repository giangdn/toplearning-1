
var open = false;
/*
$(".submenu").click(function(){
    var thisid = $(this).attr("id");
    if(open === false)
    {
        $("#"+thisid+" i").attr("class", "fa fa-angle-right");
        $(".submenu-content").show();
        open = true;
    }
    else
    {
        $("#"+thisid+" i").attr("class", "fa fa-angle-down");
        $(".submenu-content").hide();
        open = false;
    }
    return false;
});*/

$(".nav-mobile .navbar-toggler").click(function(){
    $("#navbarSupportedContentMobile").toggle();
    return false;
});

$("#close-menu").click(function(){
    $("#navbarSupportedContentMobile").hide(200);
});

var mntop_isopen = false;
$(".menu-top .sub-item a.show-sub").click(function(){
    if(mntop_isopen === false)
    {
        $("#"+$(this).data('mid')).show();
        mntop_isopen = true;
        return false;
    }

    if(mntop_isopen === true)
    {
        $("#"+$(this).data('mid')).hide();
        mntop_isopen = false;
        return false;
    }
});


var scrollTrigger = 100,
backToTop = function () {
    var scrollTop = $(window).scrollTop();
    if (scrollTop > scrollTrigger) {
        $('#back-to-top').addClass('show');
        // $('header .head-header').hide();
        $('.nav-desktop .navbar-brand img').attr('style', 'width: 100px;');
    } else {
        // $('header .head-header').show();
        $('#back-to-top').removeClass('show');
        $('.nav-desktop .navbar-brand img').attr('style', '');
    }
};

// backToTop();
$(window).on('scroll', function () {
    backToTop();
});

$('#back-to-top').on('click', function (e) {
    e.preventDefault();
    $('html,body').animate({
        scrollTop: 0
    }, 700);
});

$('body').on('click', function (e) {

    if(mntop_isopen === true)
    {
        $("#sub-menu-top1").hide();
        mntop_isopen = false;
    }

    return true;
});



