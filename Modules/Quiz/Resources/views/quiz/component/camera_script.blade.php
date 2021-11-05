<script language="JavaScript">
    window.addEventListener("DOMContentLoaded", function() {
        // Grab elements, create settings, etc.
        var canvas = document.getElementById('canvas');
        context = canvas.getContext('2d');
        var video = document.getElementById('video');
        var errorVideo = document.getElementById('error');
        var mediaConfig =  { video: true };
        var errBack = function(e) {
            console.log('An error has occurred!', e)
        };

        // Put video listeners into place
        if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
                //video.src = window.URL.createObjectURL(stream);
                console.log(stream);
                video.srcObject = stream;
                video.play();
            }).catch(error => {
                var errorMessage = '';
                console.log(error.name);
                if (error.name === 'NotAllowedError') {
                    errorMessage = "{!!__('app.qrcode_NotAllowedError')!!}"
                } else if (error.name === 'NotFoundError') {
                    errorMessage = "{!!__('app.qrcode_NotFoundError')!!}"
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = "{!!__('app.qrcode_NotSupportedError')!!}"
                } else if (error.name === 'NotReadableError') {
                    errorMessage = "{!!__('app.qrcode_NotReadableError') !!}"
                } else if (error.name === 'OverconstrainedError') {
                    errorMessage = "{!!__('app.qrcode_OverconstrainedError')!!}"
                } else {
                    errorMessage = "{!!__('app.qrcode_error_unknow')!!}"
                }
                errorVideo.innerHTML = errorMessage;
            });
        }

        /* Legacy code below! */
        else if(navigator.getUserMedia) { // Standard
            navigator.getUserMedia(mediaConfig, function(stream) {
                video.src = stream;
                video.play();
            }, errBack);
        } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
            navigator.webkitGetUserMedia(mediaConfig, function(stream){
                video.src = window.webkitURL.createObjectURL(stream);
                video.play();
            }, errBack);
        } else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
            navigator.mozGetUserMedia(mediaConfig, function(stream){
                video.src = window.URL.createObjectURL(stream);
                video.play();
            }, errBack);
        }

        // Trigger photo take
        /*document.getElementById('snap').addEventListener('click', function() {
            context.drawImage(video, 0, 0, 640, 480);
            var image = document.getElementById("canvas").toDataURL("image/png");

            $.ajax({
                type: 'POST',
                url: url_save_webcam,
                data: {
                    'image': image,
                },
                dataType: 'json',
                success: function (msg) {
                    console.log(msg);
                }
            });
        });*/
    }, false);
</script>
