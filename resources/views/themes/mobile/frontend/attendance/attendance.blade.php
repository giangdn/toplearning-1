@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.attendance'))

@section('header')
    <script language="javascript" src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script language="javascript" src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 bg-white">
                <h6>@lang('app.course_info')</h6>
                <p>
                    @lang('app.course'): {{ $course->name .' ('. $course->code .')' }} <br>
                    @lang('app.time'): {{ get_date($course->start_date, 'd/m') .' - '. get_date($course->end_date) }} <br>
                    @lang('app.register'): {{ $total_register }} - @lang('app.joined'): {{ $total_attendance }} <br>
                    @lang('app.teacher'):
                    @foreach($teacher as $item)
                        <span>{{ $item->name .' ('. $item->code .')' }}</span>;
                    @endforeach
                </p>
            </div>
            <div class="col-12 pb-2 pt-2">
                <select class="form-control select2" name="schedules_id" id="schedules_id">
                    @foreach($schedules as $key => $item)
                        {{ $selected = ($item->id == $schedule_id) ? 'selected' : '' }}
                        <option {{ $selected }} value="{{ $item->id }}">
                            {{ trans('app.session') .' '. ($key+1) .' ('. get_date($item->start_time, 'H:i') }}
                            =>
                            {{ get_date($item->end_time, 'H:i') .' - '. get_date($item->lesson_date, 'd/m/Y') . ')' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="text-center col-12 pb-2">
                @if ($schedule_id>0)
                    <a href="#" data-toggle="modal" data-target="#modal-qrcode">
                        <img src="{{asset('images/qr-code.svg')}}" width="30px"/> @lang('app.scan_attendance_code')
                    </a>
                @endif
            </div>
        </div>
        <div>
            <table class="tDefault table table-hover bootstrap-table table-bordered">
                <thead>
                <tr>
                    <th data-formatter="index_formatter" data-align="center">#</th>
                    <th data-field="full_name" data-formatter="user_formatter">@lang('app.employees')</th>
                    <th data-field="attendance" data-align="center" data-width="10%" data-formatter="attendance_formatter">@lang('app.joined')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('modal')
    @include('themes.mobile.frontend.attendance.qrcode')
@endsection
@section('footer')
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return '<img src="'+ row.img_user +'" alt="" class="avatar avatar-30 border-0 shadow-sm" />';
        }

        function attendance_formatter(value, row, index) {
            return '<img src="{{ asset('themes/mobile/img/approve.png') }}" alt="" class="avatar-20">';
        }

        function user_formatter(value, row, index) {
            return  row.full_name +' ('+ row.code +') <br>'+ row.title_name;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.attendance.getStudents',['course_id'=>$course->id] ) }}/?schedule={{$schedule_id}}',
            locale: '{{ data_locale('vi-VN', 'en-US') }}',
        });
        $("#schedules_id").on('change', function () {
            window.location = "?schedule=" + $(this).val();
        });

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
                    var url = '{{url('qrcode/process') }}?schedule={{$schedule_id}}&course={{$course->id}}&user='+param.user_id+'&type=teacher_attendance';
                    window.location.href = url;
                    this.decodedContent = content;
                },

                onInit (promise) {
                    promise.then(() => {
                        console.log('Successfully initilized! Ready for scanning now!')
                    })
                        .catch(error => {
                            if (error.name === 'NotAllowedError') {
                                this.errorMessage = 'Hey! I need access to your camera'
                            } else if (error.name === 'NotFoundError') {
                                this.errorMessage = 'Do you even have a camera on your device?'
                            } else if (error.name === 'NotSupportedError') {
                                this.errorMessage = 'Seems like this page is served in non-secure context (HTTPS, localhost or file://)'
                            } else if (error.name === 'NotReadableError') {
                                this.errorMessage = 'Couldn\'t access your camera. Is it already in use?'
                            } else if (error.name === 'OverconstrainedError') {
                                this.errorMessage = 'Constraints don\'t match any installed camera. Did you asked for the front camera although there is none?'
                            } else {
                                this.errorMessage = 'UNKNOWN ERROR: ' + error.message
                            }
                        })
                }
            }
        })
    </script>
@endsection
