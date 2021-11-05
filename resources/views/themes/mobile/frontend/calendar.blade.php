@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.training_calendar'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <button type="button"
                    class="btn btn-info"
                    id="my-calendar"
                    data-type="1">Lịch của tôi</button>
            <button type="button"
                    class="btn btn-primary"
                    id="all-calendar"
                    data-type="2">Lịch toàn bộ</button>
        </div>
        <div class="col-md-12 mt-2">
            <div id='calendar'></div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script>
    var calendarUrl = '{{ route('frontend.calendar.getdata') }}';
        var type = 1;

        /*Calendar*/
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            $('#my-calendar').on('click', function () {
                type = $(this).data('type');
                $.ajax({
                    url: calendarUrl + '?type=' + type,
                    success: function (res) {
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            /*initialView: 'dayGridMonth',*/
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            events: res,
                            editable: true,
                            selectable: true,
                            businessHours: true,
                        });
                        calendar.render();
                    }
                });
            });
            $('#all-calendar').on('click', function () {
                type = $(this).data('type');

                $.ajax({
                    url: calendarUrl + '?type=' + type,
                    success: function (res) {
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            /*initialView: 'dayGridMonth',*/
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            events: res,
                            editable: true,
                            selectable: true,
                            businessHours: true,
                        });
                        calendar.render();
                    }
                });
            });

            $.ajax({
                url: calendarUrl + '?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        /*initialView: 'dayGridMonth',*/
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        events: res,
                        editable: true,
                        selectable: true,
                        businessHours: true,
                    });
                    calendar.render();
                }
            });
        });
        /***********/
</script>
@endsection