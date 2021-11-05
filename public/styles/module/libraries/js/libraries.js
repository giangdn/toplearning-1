$(document).ready(function () {
    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };
        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'">');
            $("#image-select").val(path);
        });
    });
    // Laraberg.init('description', {
    //     height: '300px',
    //     laravelFilemanager: {prefix: '/filemanager'},
    //     sidebar: true,
    // });
});

