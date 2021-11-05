$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
        if (jqxhr.status === 401) {
            window.location = "/";
        }

        if (jqxhr.status === 419) {
            alert('Token đã hết hạn');
            window.location = "";
        }
    });
});

function show_message(message, status = 'success') {
    if (message) {
        Swal.fire({
            'html': message,
            'type': status,
            'timer': 1500,
        });
    }
}

function replace_template( template, data ){
    return template.replace(
        /{(\w*)}/g,
        function( m, key ){
            return data.hasOwnProperty( key ) ? data[ key ] : "";
        }
    );
}

function open_center_popup(url, title, w, h, set_url = null) {
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
    var systemZoom = width / window.screen.availWidth;
    var left = (width - w) / 2 / systemZoom + dualScreenLeft;
    var top = (height - h) / 2 / systemZoom + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);
    window.SetUrl = set_url;
    return newWindow;
}

var open_filemanager = function (options, cb) {
    let url = base_url + '/filemanager?type=' + options.type;
    open_center_popup(url, 'Quản lý tệp tin', 900, 600, cb);
};
jQuery.each( [ "put", "delete" ], function( i, method ) {
    jQuery[ method ] = function( url, data, callback, type ) {
        if ( jQuery.isFunction( data ) ) {
            type = type || callback;
            callback = data;
            data = undefined;
        }

        return jQuery.ajax({
            url: url,
            type: method,
            dataType: type,
            data: data,
            success: callback
        });
    };
});
