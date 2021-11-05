$(document).ready(function() {
    $(".tabs-content .tab a").click(function(){      
        var tab = $(this).data('tab');
        
        $(".tabs").hide();
        $(".tab").removeClass('active');
        
        $(".tabs.tab-"+tab).show();        
        $(this).closest(".tab").addClass('active');
        return false;
    });

    var sliders = $('.slider-top').bxSlider({
        auto: false,
        pager: false,
    });

    $('a.pager-prev').click(function () {
        var current = sliders.getCurrentSlide();
        sliders.goToPrevSlide(current) - 1;
    });

    $('a.pager-next').click(function () {
        var current = sliders.getCurrentSlide();
        sliders.goToNextSlide(current) + 1;
    });

    $(".evaluate").on('click', function () {
        let rate = $(this).data('s');
        $.ajax({
            type: 'POST',
            url: rating_url,
            dataType: 'json',
            data: {
                'star': rate
            }
        }).done(function(data) {
            if (data.status !== "success") {
                show_message(data.message, data.status);
                return false;
            }

            window.location = "";
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    $("#go-course").on('click', function () {
        $(this).addClass('disabled');
        $(".block-image .thumbnail-image").hide('slow');
        $("#activity").show('slow');
    });

    $('.active_file').on('click', function () {
        $(this).closest('.row').find('.file').prop('checked', true);
    });
    $('.active_url').on('click', function () {
        $(this).closest('.row').find('.url').prop('checked', true);
    });
});
