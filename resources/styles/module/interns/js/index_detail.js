function intern_name_formatter(value, row, index) {
    return '<a href="'+ row.edit_url +'">'+ row.intern_name +'</a>';
}

function receive_formatter(value, row, index) {
    return value == 1 ? 'Được nhận' : value == 0 ? 'Không được nhận' : ' ';
}

function created_by_formatter(value, row, index) {
    return row.lastname + " " + row.firstname;
}

function report_formatter(value, row, index) {
    return '<a href="javascript:void(0)" class="btn btn-primary upload-report" data-id="'+ row.id +'"><i class="fa fa-upload"></i></a> <a href="'+row.download_report+'" class="btn btn-warning"><i class="fa fa-download"></i></a>';
}

var table = new LoadBootstrapTable({
    url: data_url,
    remove_url: remove_url
});

$("#interns-detail").on('click', '.upload-report', function () {
    let id = $(this).data('id');
    var lfm = function (options, cb) {
        var route_prefix = base_url + '/filemanager';
        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = cb;
    };

    lfm({type: 'file'}, function (url, path) {
        $.ajax({
            type: 'POST',
            url: upload_url,
            dataType: 'json',
            data: {
                'id': id,
                'file': path
            }
        }).done(function(data) {

            show_message(data.message, data.status);
            if (data.status === "error") {
                return false;
            }

            table.refresh();

            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
});