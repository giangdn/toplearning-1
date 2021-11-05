'user strict'
$(window).on('load', function () {
    /* swiper slider carousel */
    var swiper = new Swiper('.icon-slide', {
        slidesPerView: 'auto',
        spaceBetween: 0,
    });
    var swiper = new Swiper('.offer-slide', {
        slidesPerView: 'auto',
        spaceBetween: 0,
    });

    var swiper = new Swiper('.two-slide', {
        slidesPerView: 2,
        spaceBetween: 0,
        pagination: {
            el: '.swiper-pagination',
        },
    });

    var swiper = new Swiper('.news-slide', {
        slidesPerView: 'auto',
        spaceBetween: 0,
        pagination: {
            el: '.swiper-pagination',
        },
        /*breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 0,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 0,
            },
            320: {
                slidesPerView: 2,
                spaceBetween: 0,
            }
        }*/
    });

});
