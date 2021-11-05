@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.attendance'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-3">
                <form method="get" class="form-search border-0" id="form-status">
                    <input type="hidden" name="type" value="">

                    <div class="row border-bottom pb-2" id="check-attendance">
                        <div class="col-4 text-center status p-0" data-type="1">
                            <img src="{{ asset('themes/mobile/img/loading.png') }}" alt="" class="avatar-20"> @lang('app.no_attendance') <br>
                            (<span id="no_attendance">0</span>)
                        </div>
                        <div class="col-4 text-center status p-0" data-type="2">
                            <img src="{{ asset('themes/mobile/img/check.png') }}" alt="" class="avatar-20"> @lang('app.attendance') <br>
                            (<span id="attendance">0</span>)
                        </div>
                        <div class="col-4 text-center status p-0" data-type="3">
                            <img src="{{ asset('themes/mobile/img/padlock.png') }}" alt="" class="avatar-20"> @lang('app.end') <br>
                            (<span id="end">0</span>)
                        </div>
                    </div>
                </form>
            </div>
            @if(count($list) > 0)
                <div class="col-12" id="list-attendance">
                    @foreach($list as $item)
                        @php
                            $total_register = \Modules\Offline\Entities\OfflineRegister::where('course_id', '=', $item->id)->count();

                            $check_attendance = \Modules\Offline\Entities\OfflineAttendance::leftJoin('el_offline_register as register', 'register.id', '=', 'el_offline_attendance.register_id')->where('register.course_id', '=', $item->id)->count();
                        @endphp
                        <div class="row p-2 border-bottom bg-white">
                            <div class="col-2 p-0 text-center">
                                {{ $check_attendance .'/'. $total_register }}
                            </div>
                            <div class="col-8 p-0">
                                @if($item->end_date && $item->end_date < date('Y-m-d H:i:s'))
                                    <b>{{ $item->name }} </b>
                                @else
                                    <a href="{{ route('frontend.attendance.course',['course_id' => $item->id]) }}">
                                        <b>{{ $item->name }} </b>
                                    </a>
                                @endif
                                <br>
                                {{ $item->code }} <br>
                                <img src="{{ asset('themes/mobile/img/time.png') }}" alt="" class="avatar-20">
                                {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                            </div>
                            <div class="col-2 p-0 text-center">
                                @if($item->end_date && $item->end_date < date('Y-m-d H:i:s'))
                                    <img src="{{ asset('themes/mobile/img/padlock.png') }}" alt="" class="avatar-20 end">
                                @elseif($total_register == $check_attendance)
                                    <img src="{{ asset('themes/mobile/img/check.png') }}" alt="" class="avatar-20 attendance">
                                @elseif ($check_attendance < $total_register)
                                    <img src="{{ asset('themes/mobile/img/loading.png') }}" alt="" class="avatar-20 no-attendance">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="col-12 text-center pt-1">
                    <span>@lang('app.not_found')</span>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="seachCourseInAttendace" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="form-search" class="input-group border-0">
                <div class="modal-content">
                    <div class="modal-header theme-header border-0">
                        <h6 class="">@lang('app.search')</h6>
                    </div>
                    <div class="modal-body" style="border-top: 1px solid #dee2e6;">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập tên / mã khóa', 'Enter code / name course') }}" value="">
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 text-center align-self-center">
                            <button type="submit" class="btn btn-info">
                                OK
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#check-attendance').on('click', '.status', function () {
            var type = $(this).data('type');
            $('input[name=type]').val(type);

            $("#form-status").submit();
        });

        var num_no_attendance = $("#list-attendance").find(".no-attendance").length;
        $('#no_attendance').text(num_no_attendance);

        var num_attendance = $("#list-attendance").find(".attendance").length;
        $('#attendance').text(num_attendance);

        var num_end = $("#list-attendance").find(".end").length;
        $('#end').text(num_end);

    </script>
@endsection
