var media_page = 1;
var media_parent = 0;
var media_move = true;

function showLoading(loading = 1) {
    if (loading === 1) {

    }
    else {

    }
}

function loadResults(media_parent, media_page) {
    $.ajax({
        url: base_url + "/load-ajax-media",
        type: "POST",
        dataType: 'json',
        data: {
            'file_type': 'image',
            'page': media_page,
            'parent': media_parent,
        },
        beforeSend: function(xhr) {

            if (media_move) {
                showLoading();
            }
        },
        success: function(data) {
            showLoading(0);

            var $results = $("#results");
            if (data.move == false) {
                media_move = false;
            }
            
            $.each(data.rows, function (i, val) {
                var html_item = '<div class="media-item col-md-2" title="'+ val.file_name +' '+ (val.type == 1 ? '('+ val.file_size +')' : '') +'" data-id="'+ val.id +'"><div class="item-detail"><div class="thumb-icon"><img src="https://share5s.com/themes/flow/images/folder_fm_grid.png" alt=""></div><span class="file-name">'+ val.file_name +' '+ (val.type == 2 ? '('+ val.file_count +')' : '') +'</span></div></div>';
                $results.append(html_item);
            });


        }
    });
};

$(function() {
    loadResults(media_parent, media_page);

    $(".scrollpane").scroll(function() {
        var $this = $(this);
        var $results = $("#results");

        if (!$results.data("loading")) {

            if ($this.scrollTop() + $this.height() == $results.height()) {
                if (media_move) {
                    media_page += 1;
                    loadResults(media_parent, media_page);
                }
            }
        }
    });

    $('body').on('click', '#modal-media .media-item', function () {
        media_parent = $(this).data('id');
        $("#modal-media #results").empty();
        media_move = true;
        loadResults(media_parent, media_page);
    });
});