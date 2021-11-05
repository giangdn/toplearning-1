@extends('layouts.app')

@section('page_title', $item->name)

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="fcrse_2">
                    <div class="_14d25 mb-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="st_title"><i class="uil uil-apps"></i>
                                    <a href="{{ route('module.libraries') }}">@lang('app.libraries')</a> <i class="uil uil-angle-right"></i>
                                    <a href="{{ route('module.frontend.libraries.book',['id' => 0]) }}">@lang('app.book')</a> <i class="uil uil-angle-right"></i>
                                    <span class="font-weight-bold">{{ $item->category ? $item->category->name : $item->name }}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="{{ !empty($related_libraries) ? 'col-md-9' : 'col-md-12' }} col-12">
                                <div class="row">
                                    <div class="col-md-4 pr-0 mt-3">
                                        <div class="img-library">
                                            <img src="{{ image_library($item->image) }}" alt="" style="width: 100%;"/>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="library-container">
                                            <form action="{{ route('module.frontend.libraries.book.register', ['id' => $item->id]) }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                                                @csrf
                                                <h3>{{ $item->name }}</h3>
                                                <div class="_ttl121_custom">
                                                    <div class="_ttl123_custom">@lang('app.num_books_remaining') : 
                                                        <span class="current_book">{{ $item->current_number > 0 ? $item->current_number : "Đã hết" }}</span>
                                                    </div>
                                                </div>
                                                <div class="_ttl121_custom">
                                                    <div class="_ttl123_custom">
                                                        <div class="quantity">
                                                            @lang('app.quantity') :
                                                            <input type="button" value="-" class="minus">
                                                            <input id="quantity" type="number" step="1" min="1" max="99" name="quantity" value="1" 
                                                                title="số lượng sản phẩm muốn mua" class="input-text qty text" size="4" inputmode="number">
                                                            <input type="button" value="+" class="plus">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="_ttl121_custom">
                                                    <div class="_ttl123_custom">
                                                        <span class="text-danger">* Thời gian mượn sách là 7 ngày kể từ lúc mượn.</span>
                                                    </div>
                                                    <div class="_ttl123_custom">
                                                        <span class="text-danger">* Vui lòng mang theo mã mượn sách đến thư viện để nhận sách.</span>
                                                    </div>
                                                    <div class="_ttl123_custom">
                                                        <span class="text-danger">* Quá thời gian trả sách vui lòng liên hệ : {{$item->phone_contact}}</span>
                                                    </div>
                                                </div>
                                                <div class="_ttl121_custom">
                                                    <div class="_ttl123_custom">
                                                        <button type="submit" class="btn btn_adcart register_book">@lang('app.register')</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (!empty($related_libraries))
                                <div class="col-md-3 col-12">
                                    <div class="col-12 my-2 pl-0">
                                        <h3 class="related_title">
                                            <span>Sách cùng danh mục</span>
                                        </h3>
                                    </div>
                                    <div class="row mr-0 related_libraries">
                                        @foreach ($related_libraries as $related_library)
                                            <div class="col-12">
                                                <div class="img-library">
                                                    <a href="{{ route('module.libraries.book.detail', ['id' => $related_library->id]) }}">
                                                        <img src="{{ image_library($related_library->image) }}" alt="" width="100%" height="auto"/>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <h2 class="crse14s">@lang('app.description')</h2>
                                <img class="line-title" src="{{ asset('images/line.svg') }}" alt="">
                                <br>
                                <p class="text-justify">{!! $item->description !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(function(){
    $('[data-toggle="tooltip"]').tooltip();

    $('input.minus').on('click', function(){
        var quantity = $('#quantity');
        var num = parseInt(quantity.val());

        if (num > 1){
            num -= 1;
            quantity.val(num);
        }
    });

    $('input.plus').on('click', function(){
        var quantity = $('#quantity'),
            num = parseInt(quantity.val()),
            current = parseInt($('.current_book').text());

        if (num < 10 && num < current){
            num += 1;
            quantity.val(num);
        }else{
            Swal.fire({
                title: 'Số lượng sách phải nhỏ hơn số sách còn lại và tối đa là 10 quyển.'
            })
        }
    });

    $('#quantity').on('change',function () {
        var current = parseInt($('.current_book').text()),
            num = $(this).val();
        if(current < num){
            Swal.fire({
                title: 'Số lượng sách phải nhỏ hơn số sách còn lại và tối đa là 10 quyển.'
            });
            $(this).val(current);
        }
    })

});
</script>
@stop

