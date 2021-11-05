@extends('layouts.app')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('content')
    <div class="calendar_body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <span class="font-weight-bold">@lang('app.training_calendar')</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="row">
                <div class="col-10">
                    <button type="button" class="btn btn-info" id="my-calendar" data-type="1">@lang('app.my_calendar')</button>
                    <button type="button" class="btn btn-primary" id="online_course_calendar" data-type="2">@lang('app.online_course_calendar')</button>
                    <button type="button" class="btn btn-primary" id="offline_course_calendar" data-type="3">@lang('app.offline_course_calendar')</button>
                </div>
                <div class="col-2 text-right">
                    <a href="{{ route('frontend.calendar.week') }}?type=1" class="btn btn-info"> Lịch tuần </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var type = 1;
        var calendarEl = document.getElementById('calendar');
        $.ajax({
            url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
            success: function (res) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    /*headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,dayGridDay'
                    },*/
                    resourceRender: function(info) {
                        info.el.querySelector('.fc-cell-text').innerHTML = '<button>test</button>'
                    },
                    eventDidMount: function(info) {
                        $(info.el).tooltip({
                            title: info.event.extendedProps.description,
                        });
                    },
                    events: res,
                    editable: true,
                    selectable: true,
                    businessHours: true,
                });
                calendar.render();
            }
        });

        $('#my-calendar').on('click', function () {
           type = $(this).data('type');
            $.ajax({
                url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        /*headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },*/
                        events: res,
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description
                            });
                        },
                    });
                    calendar.render();
                }
            });
        });

        $('#online_course_calendar').on('click', function () {
            type = $(this).data('type');

            $.ajax({
                url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        /*headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },*/
                        events: res,
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description
                            });
                        },
                    });
                    calendar.render();
                }
            });
        });

        $('#offline_course_calendar').on('click', function () {
            type = $(this).data('type');

            $.ajax({
                url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        /*headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },*/
                        events: res,
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description,
                            });
                        },
                    });
                    calendar.render();
                }
            });
        });

    </script>
@endsection
