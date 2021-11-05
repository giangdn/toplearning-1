@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.your_QR_code'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                @php
                    $img_user = \App\Profile::avatar(Auth::id());
                @endphp
                {!! QrCode::size(150)->generate($info_qrcode) !!}
            </div>
        </div>
        <div class="row border-bottom">
            <div class="col-12 text-justify mt-2">
                <p>@lang('app.notify_your_qr_code')</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-justify mt-2">
                <h6>@lang('app.promotion_suitable_you') (<span id="num-promotion"></span>)</h6>
            </div>
            <div class="col-12 px-0">
                @if($promotions->count() > 0)
                <ul class="list-group list-group-flush border-bottom" id="promotion-user">
                    @foreach($promotions as $promotion)
                        <li class="list-group-item" id="item{{$promotion->id}}">
                            <div class="row align-items-center">
                                <div class="col-auto pr-0">
                                    <img src="{{ image_file($promotion->image) }}" class="avatar avatar-40" alt="">
                                </div>
                                <div class="col align-self-center pr-0">
                                    <form action="{{ route('module.front.promotion.get', ['id' => $promotion->id]) }}" method="post" class="form-ajax">
                                        @csrf
                                        <button type="submit" class="p-0" style="background: white; border: none; outline: none;">
                                            <h6>{{ $promotion->name }}</h6>
                                        </button>
                                    </form>
                                    <span class="small">
                                        @lang('app.period'):
                                        {{ \Carbon\Carbon::parse($promotion->period)->format('d/m/Y H:i:s') }}
                                    </span>
                                    <br>
                                    <span class="small">
                                        @lang('app.remaining'):
                                        <b class="text-danger">{{ $promotion->amount }}</b>
                                    </span>
                                </div>
                                <div class="col-auto text-center">
                                    {{ $promotion ? $promotion->point : '' }} <br>
                                    <img class="point vm avatar-20 no-shadow" src="{{ asset('images/level/point.png') }}" alt="">
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @else
                    <p class="text-center bg-white">@lang('app.not_found')</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var num = $("#promotion-user").find("li").length;
        $('#num-promotion').text(num);
    </script>
@endsection
