@extends('layouts.test')
@section('page_title', trans('app.attendance'))

@section('header')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
@endsection

@section('content')
    <div id="app" class="container">
        <qrcode-stream @init="onInit" @decode="onDecode" :paused="paused" >
            <div v-if="decodedContent !== null" class="decoded-content"></div>
        </qrcode-stream>

        <div class="error">
            @{{ errorMessage }}
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
                    errorMessage: 'Vui lòng bật camera'
                }
            },

            methods: {
                async onDecode (content) {
                    this.camera = false;
                    var param = JSON.parse(content);
                    // var param = $.param(JSON.parse(content));
                    var url = '?schedule=3&course=2&user='+param.user_id+'&type=teacher_attendance';
                    window.location.href = url;
                    this.decodedContent = content;
                },

                onInit (promise) {
                    promise.then(() => {
                        console.log('Successfully initilized! Ready for scanning now!')
                    })
                        .catch(error => {
                            if (error.name === 'NotAllowedError') {
                                this.errorMessage = 'Bạn cần cho phép quyền truy cập camera'
                            } else if (error.name === 'NotFoundError') {
                                this.errorMessage = 'Thiết bị của bạn không hỗ trợ camera'
                            } else if (error.name === 'NotSupportedError') {
                                this.errorMessage = 'Trang này được cung cấp trong ngữ cảnh không an toàn (HTTPS, localhost hoặc tệp: //)'
                            } else if (error.name === 'NotReadableError') {
                                this.errorMessage = 'Vui lòng mở quyền cho ứng dụng có thể truy cập camera'
                            } else if (error.name === 'OverconstrainedError') {
                                this.errorMessage = 'Constraints don\'t match any installed camera. Did you asked for the front camera although there is none?'
                            } else {
                                this.errorMessage = 'Please, turn on app permission for your camera'
                            }
                        })
                }
            }
        });
    </script>
@stop
