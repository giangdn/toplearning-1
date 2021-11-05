@extends('layouts.app')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('content')
    <style>
        .list-inline {
            text-align: right;
            margin-bottom: 30px;
        }
        th {
            text-align: center;
        }
        td {
            min-height: 100px;
            vertical-align:middle !important;
        }
        th:nth-of-type(8), td:nth-of-type(8) {
            color: red;
        }
    </style>

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
                    <form id="form-search">
                        <input type="hidden" name="type" value="">
                        <input type="hidden" name="week" value="{{ request()->get('week') }}">
                        <input type="hidden" name="year" value="{{ request()->get('year') }}">

                        <button type="button" class="btn btn-info" id="my-calendar" data-type="1">
                            @lang('app.my_calendar')
                        </button>
                        <button type="button" class="btn btn-primary" id="online_course_calendar" data-type="2">
                            @lang('app.online_course_calendar')
                        </button>
                        <button type="button" class="btn btn-primary" id="offline_course_calendar" data-type="3">
                            @lang('app.offline_course_calendar')
                        </button>
                    </form>
                </div>
                <div class="col-2 text-right">
                    <a href="{{ route('frontend.calendar') }}" class="btn btn-info"> Lịch tháng </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-2">
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="{{ route('frontend.calendar.week') }}" class="btn btn-link">Today</a></li>
                        <li class="list-inline-item"><a href="{{ route('frontend.calendar.week') }}?type={{ request()->get('type') }}&week={{ ($week-1) }}&year={{ $year }}" class="btn btn-link"> <i class="fa fa-arrow-left"></i></a></li>
                        <li class="list-inline-item"><a href="{{ route('frontend.calendar.week') }}?type={{ request()->get('type') }}&week={{ ($week+1) }}&year={{ $year }}" class="btn btn-link"> <i class="fa fa-arrow-right"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-12 mt-2">
                    <div id='calendar-week'>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    @php
                                        $arr_l = ['Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy', 'Chủ nhật'];
                                        $arr_day = [];
                                    @endphp
                                    @for($i = 0; $i < 7; $i++)
                                        <th>
                                            {{ $arr_l[$i] }} <br>
                                            {{ $dt->format('d/m/Y') }}
                                        </th>
                                        @php
                                            $arr_day[] = $dt->format('Y-m-d');
                                            $dt->addDay();
                                        @endphp
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                            @if($list_course->count() > 0)
                                @foreach($list_course as $course)
                                    <tr>
                                        <td>{{ $course->name .' ('. $course->code .')' }}</td>
                                        @for($i = 0; $i < 7; $i++)
                                            @if(get_date($course->start_date, 'Y-m-d') <= $arr_day[$i])
                                                @if(isset($course->end_date))
                                                    @if(get_date($course->end_date, 'Y-m-d') >= $arr_day[$i])
                                                    <td style="text-align: center;">
                                                            <i class="fa fa-check-circle w-75 p-1 m-auto" style="color: #1b4486; font-size:35px"></i>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td style="text-align: center;">
                                                        <i class="fa fa-check-circle w-75 p-1 m-auto" style="color: #1b4486; font-size:35px"></i>
                                                    </td>
                                                @endif
                                            @else
                                                <td></td>
                                            @endif
                                        @endfor
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#my-calendar').on('click', function () {
            var type = $(this).data('type');
            $('input[name=type]').val(type);

            $('#form-search').submit();
        });

        $('#online_course_calendar').on('click', function () {
            var type = $(this).data('type');
            $('input[name=type]').val(type);

            $('#form-search').submit();
        });

        $('#offline_course_calendar').on('click', function () {
            var type = $(this).data('type');
            $('input[name=type]').val(type);

            $('#form-search').submit();
        });
    </script>
@endsection
