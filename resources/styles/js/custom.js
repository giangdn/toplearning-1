$(document).on("turbolinks:load", function() {
    // === Dropdown === //

    $('.ui.dropdown')
        .dropdown();

// === Model === //
    $('.ui.modal')
        .modal({
            blurring: true
        })
        .modal('show');

// === Tab === //
    $('.menu .item')
        .tab();

// === checkbox Toggle === //
    $('.ui.checkbox')
        .checkbox();

// === Toggle === //
    $('.enable.button')
        .on('click', function() {
            $(this)
                .nextAll('.checkbox')
                .checkbox('enable');
        });


// Featured Courses home
    // $('.featured_courses').owlCarousel({
    //     items:10,
    //     loop:false,
    //     margin:20,
    //     nav:true,
    //     dots:false,
    //     navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:3
    //         },
    //         1000:{
    //             items:4
    //         },
    //         1200:{
    //             items:4
    //         },
    //         1400:{
    //             items:5
    //         }
    //     }
    // });

// Featured Courses home
    // $('.top_instrutors').owlCarousel({
    //     items:10,
    //     loop:false,
    //     margin:20,
    //     nav:true,
    //     dots:false,
    //     navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:2
    //         },
    //         1000:{
    //             items:1
    //         },
    //         1200:{
    //             items:2
    //         },
    //         1400:{
    //             items:3
    //         }
    //     }
    // });

// Student Says
    // $('.Student_says').owlCarousel({
    //     items:10,
    //     loop:false,
    //     margin:30,
    //     nav:true,
    //     dots:false,
    //     navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:2
    //         },
    //         1000:{
    //             items:2
    //         },
    //         1200:{
    //             items:3
    //         },
    //         1400:{
    //             items:3
    //         }
    //     }
    // });

// features Careers
    // $('.feature_careers').owlCarousel({
    //     items:4,
    //     loop:false,
    //     margin:20,
    //     nav:true,
    //     dots:false,
    //     navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:1
    //         },
    //         1000:{
    //             items:1
    //         },
    //         1200:{
    //             items:1
    //         },
    //         1400:{
    //             items:1
    //         }
    //     }
    // });

    // Featured Courses home
    // $('.courses_performance').owlCarousel({
    //     items:10,
    //     loop:true,
    //     margin:30,
    //     nav:true,
    //     dots:false,
    //     lazyLoad: true,
    //     autoplay:true,
    //     navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:1
    //         },
    //         1000:{
    //             items:1
    //         },
    //         1200:{
    //             items:1
    //         },
    //         1400:{
    //             items:1
    //         }
    //     }
    // })

// Latest News Dashboard
    // $('.edututs_news').owlCarousel({
    //     items:10,
    //     loop:false,
    //     margin:30,
    //     nav:true,
    //     dots:false,
    //     lazyLoad: true,
    //     autoplay:true,
    //     navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:1
    //         },
    //         1000:{
    //             items:1
    //         },
    //         1200:{
    //             items:1
    //         },
    //         1400:{
    //             items:1
    //         }
    //     }
    // })

    /*Floating Code for Iframe Start*/
    if (jQuery('iframe[src*="https://www.youtube.com/embed/"],iframe[src*="https://player.vimeo.com/"],iframe[src*="https://player.vimeo.com/"]').length > 0) {
        /*Wrap (all code inside div) all vedio code inside div*/
        jQuery('iframe[src*="https://www.youtube.com/embed/"],iframe[src*="https://player.vimeo.com/"]').wrap("<div class='iframe-parent-class'></div>");
        /*main code of each (particular) vedio*/
        jQuery('iframe[src*="https://www.youtube.com/embed/"],iframe[src*="https://player.vimeo.com/"]').each(function(index) {

            /*Floating js Start*/
            var windows = jQuery(window);
            var iframeWrap = jQuery(this).parent();
            var iframe = jQuery(this);
            var iframeHeight = iframe.outerHeight();
            var iframeElement = iframe.get(0);
            windows.on('scroll', function() {
                var windowScrollTop = windows.scrollTop();
                var iframeBottom = iframeHeight + iframeWrap.offset().top;
                //alert(iframeBottom);

                if ((windowScrollTop > iframeBottom)) {
                    iframeWrap.height(iframeHeight);
                    iframe.addClass('stuck');
                    jQuery(".scrolldown").css({"display": "none"});
                } else {
                    iframeWrap.height('auto');
                    iframe.removeClass('stuck');
                }
            });
            /*Floating js End*/
        });
    }

    /*Floating Code for Iframe End*/

// expand/collapse all Start

    var headers = $('#accordion .accordion-header');
    var contentAreas = $('#accordion .ui-accordion-content ').hide()
        .first().show().end();
    var expandLink = $('.accordion-expand-all');

// add the accordion functionality
    headers.click(function() {
        // close all panels
        contentAreas.slideUp();
        // open the appropriate panel
        $(this).next().slideDown();
        // reset Expand all button
        expandLink.text('Mở rộng')
            .data('isAllOpen', false);
        // stop page scroll
        return false;
    });

// hook up the expand/collapse all
    expandLink.click(function(){
        var isAllOpen = !$(this).data('isAllOpen');
        console.log({isAllOpen: isAllOpen, contentAreas: contentAreas});
        contentAreas[isAllOpen? 'slideDown': 'slideUp']();
        expandLink.text(isAllOpen? 'Thu lại': 'Mở rộng')
            .data('isAllOpen', isAllOpen);
    });


// Payment Method Accordion
    $('input[name="paymentmethod"]').on('click', function () {
        var $value = $(this).attr('value');
        $('.return-departure-dts').slideUp();
        $('[data-method="' + $value + '"]').slideDown();
    });

//Infinity Load
    //$('ul.pagination').hide();
    // $(function() {
    //     $('.infinite-scroll').jscroll({
    //         autoTrigger: true,
    //         loadingHtml: '<div class="col-md-12">\n' +
    //             '                            <div class="main-loader mt-50">\n' +
    //             '                                <div class="spinner">\n' +
    //             '                                    <div class="bounce1"></div>\n' +
    //             '                                    <div class="bounce2"></div>\n' +
    //             '                                    <div class="bounce3"></div>\n' +
    //             '                                </div>\n' +
    //             '                            </div>\n' +
    //             '                        </div>',
    //         padding: 0,
    //         nextSelector: '.pagination li.active + li a',
    //         contentSelector: 'div.infinite-scroll',
    //         callback: function() {
    //             //$('ul.pagination').remove();
    //             $('.more-loader').show();
    //         },
    //         loadingFunction: function () {
    //             $('.more-loader').hide();
    //         },
    //     });
    // });
});

function turnOffCamera(){
    const video = document.querySelector('video');
    const mediaStream = video.srcObject;
    const tracks = mediaStream.getTracks();
    tracks[0].stop();
    tracks.forEach(track => track.stop());
}
function ratingStars(element) {
    element.on('mouseover',function () {
        var onStar = parseInt($(this).data('value'), 10);
        $(this).parent().children('.rating-star').each(function(e){
            if (e < onStar) {
                $(this).addClass('full-star');
            }
            else {
                $(this).removeClass('full-star');
            }
        });
    }).on('mouseout', function(){
        $(this).each(function(e){
            $(this).removeClass('full-star');
        });
    })

    element.on("click", function(){
        var onStar = parseInt($(this).data('value'), 10);
        var stars = $(this).parent().children('.rating-star');
        for (i = 0; i < stars.length; i++) {
            $(stars[i]).removeClass('full-star');
        }

        for (i = 0; i < onStar; i++) {
            $(stars[i]).addClass('selected');
            $(stars[i]).removeClass('full-star');
        }

        var ratingValue = parseInt($('.rating-star.selected').last().data('value'), 10);
        sendRatingStars(ratingValue);
    })

    function sendRatingStars(ratingValue) {
        var url = Rating.route;
        $.ajax({
            url: url,
            type: 'POST',
            data: {'star':ratingValue},
            dataType: 'json',
            success: function(data) {
                Swal.fire({
                    title: data.message,
                }).then(() => {
                    Turbolinks.visit(location.toString());
                });
            }
        })
    }
};

/*Calendar*/
/*    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        $.ajax({
            url: calendarUrl,
            success: function (res) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: res
                });
                calendar.render();
            }
        });
    });*/
/***********/
