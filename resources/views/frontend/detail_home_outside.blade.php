@extends('layouts.app_outside')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('header')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
@endsection

@section('content')
    @php
        $date_now = date('Y-m-d H:s');
    @endphp
    <div class="banner_outside">
        <div class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <a href="{{ $slider->url }}">
                            <img src="{{ image_file($slider->image) }}" alt="" class="w-100" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container home-page-outside">
        <div class="body_outside row">
            <div class="content_left col-md-8 col-12 mt-3">    
                <div class="breadcum row mb-3">
                    <div class="col-md-6 col-12">
                        <a href="{{ route('module.frontend.news_outside', ['cate_id' => 0, 'parent_id' => $get_category_parent->id, 'type' => 0]) }}">
                            <span class="title_cate_parent mr-2">{{ $get_category_parent->name }} </span>
                        </a>
                        <i class="fa fa-angle-right mr-2" aria-hidden="true"></i>
                        <a href="{{ route('module.frontend.news_outside', ['cate_id' => $get_category->id, 'parent_id' => $get_category->parent_id, 'type' => 1]) }}">
                            {{ $get_category->name }}
                        </a>
                    </div>
                    <div class="col-md-6 col-12 date_now">
                        @php
                            $dt = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                        @endphp
                        {{ $dt->format('d/m/Y h:i A') }}
                    </div>
                </div>
                <div class="new_detail">
                    <h5>{{$get_new_outside->title}}
                        @if ($date_now < $get_new_outside->date_setup_icon)
                            .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                        @endif
                    </h5>
                    <div class="row">
                        <div class="date_category col-md-9 col-12">
                            <span>Ngày đăng: {{ \Carbon\Carbon::parse($get_new_outside->created_at)->format('H:s d/m/Y') }}</span> | 
                            <span><strong>{{ $get_category->name }}</strong></span>
                        </div>
                        <div class="like col-md-3 col-12">
                            <span onclick="like_new( {{ $get_new_outside->id }} )" id="like_new">
                                @if (!empty(session()->has('like')) && in_array($get_new_outside->id,session()->get('like')))
                                    <i class="fa fa-check"></i> like {{ $get_new_outside->like_new }}
                                @else
                                    <i class="fa fa-thumbs-o-up"></i> like {{ $get_new_outside->like_new }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="new_detail_description mt-3 news-content">
                        @if ($get_new_outside->type == 2)
                            <div class="mt-2">
                                <center>
                                    <video width="100%" height="auto" controls>
                                        <source src="{{ image_file($get_new_outside->content) }}" type="video/mp4">
                                    </video>
                                </center>
                            </div>
                        @elseif($get_new_outside->type == 3)
                            <div class="row">
                                @php
                                $pictures = json_decode($get_new_outside->content);
                                @endphp
                                @foreach ($pictures as $picture)
                                    <div class="col-4 mt-2">
                                        <img class="image_details"
                                        data-enlargeable
                                        style="cursor: zoom-in;object-fit: cover;" 
                                        src="{{ image_file($picture) }}" alt="" width="100%" height="100%">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="content_description_new">
                                {!! $get_new_outside->content !!}
                            </div>
                        @endif
                    </div>
                </div>      
                <div class="row mt-2 return">
                    <div class="col-12">
                        <a href="{{ route('module.frontend.news_outside', ['cate_id' => $get_category->id, 'parent_id' => $get_category->parent_id, 'type' => 1]) }}">
                            <button type="button" class="btn btn-light button_return">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                            </button>
                        </a>
                        <div class="back_cate">
                            <span>Trở lại {{ $get_category->name }}</span>
                        </div>
                    </div>
                </div>      
                <div class="related_new" id="related_new">
                    @livewire('newsoutside.related', ['category_id' => $get_new_outside->category_id, 'get_new_outside_id' => $get_new_outside->id, 'type' => $type])
                </div>
            </div>
            <div class="content_right col-md-4 col-12">
                @include('frontend.news_outside_right')
                @include('frontend.exchange_rate')
            </div>
        </div>
        @include('layouts.footer_outside')
    </div>
<script>
    $('.news-content a').attr('target', '_blank')
    $('img[data-enlargeable]').addClass('img-enlargeable').click(function(){
        var src = $(this).attr('src');
        var modal;
        function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
        modal = $('<div>').css({
            background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
            backgroundSize: 'contain',
            width:'100%', height:'100%',
            position:'fixed',
            zIndex:'10000',
            'background-size': '90% auto%',
            'object-fit': 'cover',
            top:'0', left:'0',
            cursor: 'zoom-out'
        }).click(function(){
            removeModal();
        }).appendTo('body');
        //handling ESC
        $('body').on('keyup.modal-close', function(e){
        if(e.key==='Escape'){ removeModal(); } 
        });
    });

    $(window).on('load', function () {
        $('html,body').animate({scrollTop: 290}, 900, 'swing');
    });

    function like_new(id) {
        document.querySelector('#like_new').style.pointerEvents = 'none';
        $.ajax({
            url: '{{ route('like_new_outside') }}',
            type: 'post',
            dataType: "json",
            data: {
                id: id,
                "_token": "{{ csrf_token() }}",
            }
        }).done(function(data) {
            console.log(data);
            if (data.check_like == 1) {
                $('#like_new').html('<i class="fa fa-check"></i> like '+ data.view_like);
            } else {
                $('#like_new').html('<i class="fa fa-thumbs-o-up"></i> like ' + data.view_like);
            }
            document.querySelector('#like_new').style.pointerEvents = 'auto';
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    $(document).mousemove(function(){
         if($(".button_return:hover").length != 0){
            $(".back_cate").css("display",'block');
        } else{
            $(".back_cate").css("display",'none');
        }
    });

    $(window).scroll(function() { 
        if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
            window.livewire.emit('load-more'); 
        }
    });
</script>
@endsection
