<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="schedule" id="schedule" value="{{ $schedule_id }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('app.scan_attendance_code')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="app">
                    <qrcode-stream @init="onInit" @decode="onDecode" :paused="paused">
                        <div v-if="decodedContent !== null" class="decoded-content"></div>
                    </qrcode-stream>

                    <div class="error">
                        @{{ errorMessage }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>
