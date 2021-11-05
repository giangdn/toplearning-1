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