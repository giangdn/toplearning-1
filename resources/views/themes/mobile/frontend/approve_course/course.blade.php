@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.approve_register'))

@section('content')
    <div class="container">
        <div class="row no-gutters">
            <div class="col-12 mt-2">
                <form method="get" class="input-group form-search border-0">
                    <input type="text" name="q" class="form-control" placeholder="{{ data_locale('Nhập tên / mã khóa', 'Enter code / name course') }}" value="{{ request()->get('q') }}">
                    <button type="submit" class="btn btn-link text-white position-relative text-right">
                        <i class="material-icons vm">search</i>
                    </button>
                </form>
            </div>
            <div class="col-12 mt-3">
                <form method="get" class="form-search border-0" id="form-status">
                    <input type="hidden" name="type" value="">

                    <div class="row border-bottom pb-2" id="approve">
                        <div class="col-4 p-0 text-center status" data-type="1">
                            <img src="{{ asset('themes/mobile/img/loading.png') }}" alt="" class="avatar-20"> @lang('app.unapproved') <br>
                            (<span id="unapproved">0</span>)
                        </div>
                        <div class="col-4 p-0 text-center status" data-type="2">
                            <img src="{{ asset('themes/mobile/img/check.png') }}" alt="" class="avatar-20"> @lang('app.approved') <br>
                            (<span id="approved">0</span>)
                        </div>
                        <div class="col-4 p-0 text-center status" data-type="3">
                            <img src="{{ asset('themes/mobile/img/padlock.png') }}" alt="" class="avatar-20"> @lang('app.end') <br>
                            (<span id="end">0</span>)
                        </div>
                    </div>
                </form>
            </div>
            @if(count($list) > 0)
            <div class="col-12" id="list-approve">
                @foreach($list as $item)
                    @php
                        if ($item->type == 1){
                            $approved = \Modules\Online\Entities\OnlineRegister::whereCourseId($item->id)->whereStatus(1)->count();
                            $register = \Modules\Online\Entities\OnlineRegister::countRegisters($item->id);
                            $type = @trans('app.online_course');
                        }else{
                            $approved = \Modules\Offline\Entities\OfflineRegister::whereCourseId($item->id)->whereStatus(1)->count();
                            $register = \Modules\Offline\Entities\OfflineRegister::countRegisters($item->id);
                            $type = @trans('app.in_house');
                        }
                    @endphp
                    <div class="row p-2 border-bottom bg-white">
                        <div class="col-2 p-0 text-center">
                            {{ $approved .'/'. $register }}
                        </div>
                        <div class="col-8 p-0">
                            @if($item->end_date && $item->end_date < date('Y-m-d H:i:s'))
                                <b>{{ $item->course_name }} </b>
                            @else
                                <a href="{{ route('themes.mobile.frontend.approve_course.user', ['id' => $item->id, 'type' => $item->type]) }}">
                                    <b>{{ $item->course_name }} </b>
                                </a>
                            @endif
                           <br>
                            {{ $item->course_code }} <br>
                            <img src="{{ asset('themes/mobile/img/time.png') }}" alt="" class="avatar-20">
                            {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                        </div>
                        <div class="col-2 p-0 text-center">
                            @if($item->end_date && $item->end_date < date('Y-m-d H:i:s'))
                                <img src="{{ asset('themes/mobile/img/padlock.png') }}" alt="" class="avatar-20 end">
                            @elseif($approved >= $register)
                                <img src="{{ asset('themes/mobile/img/check.png') }}" alt="" class="avatar-20 approved">
                            @elseif ($approved < $register)
                                <img src="{{ asset('themes/mobile/img/loading.png') }}" alt="" class="avatar-20 unapproved">
                            @endif
                            <br><br>
                            {{ $type }}
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
@section('footer')
    <script type="text/javascript">
        $('#approve').on('click', '.status', function () {
            var type = $(this).data('type');
            $('input[name=type]').val(type);

            $("#form-status").submit();
        });

        var num_unapproved = $("#list-approve").find(".unapproved").length;
        $('#unapproved').text(num_unapproved);

        var num_approved = $("#list-approve").find(".approved").length;
        $('#approved').text(num_approved);

        var num_end = $("#list-approve").find(".end").length;
        $('#end').text(num_end);
    </script>
@endsection
