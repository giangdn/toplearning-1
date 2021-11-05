@extends('layouts.app')

@section('page_title', trans('app.attendance'))

@section('header')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
@endsection

@section('content')
    @php
        $search_status = request()->get('status');
        $search_training_program = request()->get('training_program_id');
        $search_subject = request()->get('subject_id');
    @endphp
    @if (session('error'))
        <div class="alert alert-danger text-center" role="alert">
            <h2>{{ session('error') }}</h2>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center" role="alert">
            <h2>{{ session('success') }}</h2>
        </div>
    @endif
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        <a href="{{ route('frontend.attendance') }}">@lang('app.attendance')</a>
                                        <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">{{ $course->name }}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <p></p>
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <select class="form-control" type="button"  name="schedules_id" id="schedules_id">
                                    @foreach($schedules as $key => $item)
                                        {{$selected = ($item->id ==$schedule_id)?'selected':''}}
                                        <option {{$selected}} value="{{$item->id}}">
                                            {{ trans('app.session') .' '. ($key+1) .' ('. get_date($item->start_time, 'H:i') }}
                                            =>
                                            {{ get_date($item->end_time, 'H:i') .' - '. get_date($item->lesson_date, 'd/m/Y') . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center col-md-12 pb-2">
                                @if ($schedule_id>0)
{{--                                    <a href="#" data-toggle="modal" data-target="#modal-qrcode"><img src="{{asset('images/qr-code.svg')}}" width="30px" /> Quét mã điểm danh</a>--}}
                                    <a href="javascript:void(0)" id="attendance_modal" class="load2-modal" data-url="{{ route('frontend.attendance.show_modal',['course_id'=>$course->id,'schedule_id'=>$schedule_id]) }}"><img src="{{asset('images/qr-code.svg')}}" width="30px" /> {{ trans('app.scan_attendance_code') }}</a>
                                @endif
                            </div>
                        </div>
                        <div>
                            <table class="tDefault table table-hover bootstrap-table table-bordered" >
                                <thead>
                                <tr>
                                    <th data-formatter="index_formatter" data-align="center">{{ trans('app.stt') }}</th>
                                    <th data-field="code" data-sortable="true">{{ trans('app.employee_code') }}</th>
                                    <th data-field="full_name"  data-sortable="true">{{ trans('app.employees') }}</th>
                                    <th data-field="title_name" data-sortable="true">{{ trans('app.title') }}</th>
                                    <th data-field="unit_name" data-sortable="true">{{ trans('backend.work_unit') }}</th>
                                    <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                                    <th data-field="attendance" data-align="center" data-formatter="attendance_formatter">{{ trans('app.joined') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    @include('attendance::frontend.qrcode')--}}
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function attendance_formatter(value, row, index) {
                return '<input type="checkbox" checked class="text-muted" disabled name="attend" value='+row.id+' />';
        }
            var table = new LoadBootstrapTable({
                url: '{{ route('frontend.attendance.getStudents',['course_id'=>$course->id] ) }}/?schedule={{$schedule_id}}',
                locale: '{{ \App::getLocale() }}',
            });
        $("#schedules_id").on('change', function() {
            window.location = "?schedule="+ $(this).val();
        });
        $("#attendance_modal").on('click' , function () {
            let item = $(this);
            let oldtext = item.html();
            let id = item.data('id');
            let url =$(this).data('url');
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'html',
                data: {
                    course_id: {{$course->id}},
                    schedule_id: {{$schedule_id ? $schedule_id : 0}}
                }
            }).done(function(data) {
                item.html(oldtext);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.html(oldtext);
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>
@stop
