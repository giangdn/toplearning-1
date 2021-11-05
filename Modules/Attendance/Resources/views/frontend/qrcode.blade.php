
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
            <input type="hidden" name="schedule" id="schedule" value="{{ $schedule_id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{!! __('app.scan_qrcode_attendance') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="app" class="container">
                        <qrcode-stream @init="onInit" @decode="onDecode" :paused="paused" >
                            <div v-if="decodedContent !== null" class="decoded-content"></div>
                        </qrcode-stream>

                        <div class="error">
                            @{{ errorMessage }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{--                    <span class="text-danger">Vui lòng bật camera trên trình duyệt</span>--}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{!! __('app.close') !!}</button>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript">
Vue.use(VueQrcodeReader);
new Vue({
    el: '#app',
            data () {
                return {
                    paused: false,
                    decodedContent: null,
                    errorMessage: ''
                }
            },
            methods: {
                async onDecode (content) {
                    this.camera = false;
                    var param = JSON.parse(content);
                    // var param = $.param(JSON.parse(content));
                    var url = '{{url('qrcode/process') }}?schedule={{$schedule_id}}&course={{$course_id}}&user='+param.user_id+'&type=teacher_attendance';
                    window.location.href = url;
                    this.decodedContent = content;
                },
                onInit (promise) {
                    promise.then(() => {
                        console.log('Successfully initilized! Ready for scanning now!')
                    })
                        .catch(error => {
                            if (error.name === 'NotAllowedError') {
                                this.errorMessage = "{!!__('app.qrcode_NotAllowedError')!!}"
                            } else if (error.name === 'NotFoundError') {
                                this.errorMessage = "{!!__('app.qrcode_NotFoundError')!!}"
                            } else if (error.name === 'NotSupportedError') {
                                this.errorMessage = "{!!__('app.qrcode_NotSupportedError')!!}"
                            } else if (error.name === 'NotReadableError') {
                                this.errorMessage = "{!!__('app.qrcode_NotReadableError') !!}"
                            } else if (error.name === 'OverconstrainedError') {
                                this.errorMessage = "{!!__('app.qrcode_OverconstrainedError')!!}"
                            } else {
                                this.errorMessage = "{!!__('app.qrcode_error_unknow')!!}"
                            }
                        })
                }
            }
        });
$('#myModal').on('hidden.bs.modal', function () {
    turnOffCamera();
    // window.location.reload();
})
</script>
