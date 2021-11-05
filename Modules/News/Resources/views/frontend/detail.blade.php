@extends('layouts.app')

@section('page_title', $get_new->title)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/menu_new_inside.css') }}">
    @php
        $date_now = date('Y-m-d H:s');
    @endphp
    <div class="sa4d25">
        <div class="container-fluid">
            @include('news::frontend.menu_new')
            <div class="body_new_detail row my-3">
                <div class="content_left col-md-8 col-12">
                    <div class="breadcum row mb-3">
                        <div class="col-md-6 col-12">
                            <a href="{{ route('module.news.cate_new', ['parent_id' => $get_category_parent->id, 'id' => 0, 'type' => 0]) }}">
                                <span class="title_cate_parent mr-2">{{ $get_category_parent->name }} </span>
                            </a>
                            <i class="fa fa-angle-right mr-2" aria-hidden="true"></i>
                            <a href="{{ route('module.news.cate_new', ['parent_id' => $get_category_parent->id, 'id' => $get_category->id, 'type' => 1]) }}">
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
                        <h3><strong>{{$get_new->title}}</strong>
                            @if ($date_now < $get_new->date_setup_icon)
                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                            @endif
                        </h3>
                        <div class="row">
                            <div class="date_category col-md-9 col-12">
                                <span>Ngày đăng: {{ \Carbon\Carbon::parse($get_new->created_at)->format('H:s d/m/Y') }}</span> |
                                <span><strong>{{ $get_new_category->name }}</strong></span>
                            </div>
                            <div class="like col-md-3 col-12">
                                <span onclick="like_new( {{ $get_new->id }} )" id="like_new">
                                    @if (!empty(session()->has('like')) && in_array($get_new->id,session()->get('like')))
                                        <i class="fas fa-check"></i> like {{ $get_new->like_new }}
                                    @else
                                        <i class="far fa-thumbs-up"></i> like {{ $get_new->like_new }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="new_detail_description mt-3 news-content">
                            @if ($get_new->type == 2)
                                <div class="mt-2">
                                    <center>
                                        <video width="100%" height="auto" controls>
                                            <source src="{{ image_file($get_new->content) }}" type="video/mp4">
                                        </video>
                                    </center>
                                </div>
                            @elseif($get_new->type == 3)
                                <div class="row">
                                    @php
                                    $pictures = json_decode($get_new->content);
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
                                    {!! $get_new->content !!}
                                </div>
                            @endif

                                <div class="mt-2">
                            @if($news_links->count() > 0)
                                @foreach($news_links as $news_link)
                                    @if($news_link->type == 'file')
                                        @if(isFilePdf($news_link->link))
                                            <a href="{{ route('module.news.view_pdf').'?path='.upload_file($news_link->link) }}" target="_blank" class="mb-2" style="color: #1b4486 !important; font-size: 16px;">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                {{ $news_link->title ? $news_link->title : basename($news_link->link) }}
                                            </a>
                                        @else
                                            <a href="{{ link_download('uploads/'.$news_link->link) }}" data-turbolinks="false" class=" mb-2" style="color: #1b4486 !important; font-size: 16px;">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                {{ $news_link->title ? $news_link->title : basename($news_link->link) }}
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ $news_link->link }}" target="_blank" class=" mb-2" style="color: #1b4486 !important; font-size: 16px;">
                                            <i class="fa fa-link" aria-hidden="true"></i>
                                            {{ $news_link->title ? $news_link->title : basename($news_link->link) }}
                                        </a>
                                    @endif
                                        <p></p>
                                @endforeach
                            @endif
                                </div>
                        </div>
                    </div>

                    <div class="row mt-3 return">
                        <div class="col-12">
                            <a href="{{ route('module.news.cate_new', ['parent_id' => $get_new_category->parent_id, 'id' => $get_new_category->id, 'type' => 1]) }}">
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
                        @livewire('news.related', ['category_id' => $get_new->category_id, 'get_new_id' => $get_new->id, 'object_news_parent_cate_id' => $object_news_parent_cate_id])
                    </div>
                </div>

                @include('news::frontend.news_right')
            </div>
        </div>
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

        function like_new(id) {
            document.querySelector('#like_new').style.pointerEvents = 'none';
            $.ajax({
                url: "{{ route('module.new.like') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                console.log(data);
                if (data.check_like == 1) {
                    $('#like_new').html('<i class="fas fa-check"></i> Thích '+ data.view_like);
                } else {
                    $('#like_new').html('<i class="far fa-thumbs-up"></i> Thích ' + data.view_like);
                }
                document.querySelector('#like_new').style.pointerEvents = 'auto';
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function dateSearch() {
            var category_id = $('#category_id').val();
            var date_search = $('#date_search').val();
            var new_id = $('#new_id').val();
            $.ajax({
                type: 'POST',
                url: "{{ route('module_ajax_get_related_news') }}",
                dataType: 'json',
                data: {
                    category_id: category_id,
                    date_search: date_search,
                    new_id: new_id
                }
            }).done(function(data) {
                let rhtml = '';
                $.each(data.get_related_news, function (i, item){
                    var url_link = "{{ route('module.news.detail', ['id' => ':id']) }}";
                    url_link = url_link.replace(':id',item.id);

                    rhtml += `<div class="row mb-3 get_new">
                                <div class="col-4">
                                    <a href="`+ url_link +`">
                                        <img class="w-100" src="`+ item.image +`" alt="" height="120px">
                                    </a>
                                </div>
                                <div class="col-8">
                                    <div class="hot_new_title">
                                        <a href="`+ url_link +`">
                                            <h5 class="mb-2"> ` + item.title + ` </h5>
                                        </a>
                                    </div>
                                    <div class="hot_new_description">
                                        ` + item.description + `
                                    </div>
                                </div>
                            </div>`;
                });

                // $('#related_new .all_get_news .get_new').remove();
                $('#related_new .all_get_news').html(rhtml);
                $('.hot_new_description img').remove();
                $('.hot_new_description video').remove();
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
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

        var checkVideoNew = $('.content_description_new').find('video');
        var checkVideoImg = $('.content_description_new').find('img');
        checkVideoNew.css('width','100%');
        checkVideoImg.css('width','100%');
        checkVideoImg.css('height','auto');

        $(window).scroll(function() { 
            if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
                window.livewire.emit('load-more'); 
            }
        }); 
    </script>
@stop
