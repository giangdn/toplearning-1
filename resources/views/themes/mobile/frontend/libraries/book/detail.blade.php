@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.book'))

@section('content')
    <div class="container">
        <div class="row border-0 p-2 bg-image">
            <div class="col-3"></div>
            <div class="col-6 p-0">
                <img src="{{ image_file($item->image) }}" alt="" class="w-100">
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row bg-white pb-2 pt-3">
            <div class="col-auto align-self-center mt-1">
                <h6 class="mb-2 font-weight-bold">{{ $item->name }}</h6>
                <p class="text-mute">
                    <span>{{ $item->views }} <i class="material-icons vm">remove_red_eye</i></span>
                    <span class="pl-2">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                </p>
                <div class="_ttl123_custom">
                    @lang('app.num_books_remaining') :
                    <span class="current_book">
                        {{ $item->current_number > 0 ? $item->current_number : trans('app.it_over') }}
                    </span>
                </div>

                <form action="{{ route('module.frontend.libraries.book.register', ['id' => $item->id]) }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="quantity">
                        @lang('app.quantity') :
                        <input type="button" value="-" class="minus" id="minus">
                        <input id="quantity" type="number" step="1" min="1" max="99" name="quantity" value="1" title="số lượng sản phẩm muốn mua" class="input-text qty text" size="4" inputmode="number">
                        <input type="button" value="+" class="plus" id="plus">
                    </div>
                    <div class="note">
                        <span>
                            @lang('app.notify_regester_book')
                        </span>
                    </div>
                    <button type="submit" class="btn btn-danger register_book" {{ $item->current_number > 0 ? '' : 'disabled' }}>
                        @lang('app.register')
                    </button>
                </form>
            </div>
        </div>
        <div class="row pt-1">
            <div class="col-md-12">
                <h5>@lang('app.description')</h5>
                <img class="line-title" src="{{ asset('images/line.svg') }}" alt="">
                <br>
                <p class="text-justify">{!! $item->description !!}</p>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var current_number = '{{ $item->current_number }}';

        if(current_number == 0){
            $('#minus').prop('disabled', true);
            $('#quantity').prop('disabled', true);
            $('#plus').prop('disabled', true);
        }

        $('#minus').on('click', function () {
            var quantity = $('#quantity').val();
            if (quantity == 1){
                $('#minus').prop('disabled', true);
            }else{
                quantity = parseInt(quantity) - 1;
                $('#quantity').val(quantity);
            }
            $('#minus').prop('disabled', false);
        });

        $('#plus').on('click', function () {
            var quantity = $('#quantity').val();
            if (quantity == current_number){
                $('#plus').prop('disabled', true);
            }else{
                quantity = parseInt(quantity) + 1;
                $('#quantity').val(quantity);
            }
            $('#plus').prop('disabled', false);
        });

    </script>
@endsection
