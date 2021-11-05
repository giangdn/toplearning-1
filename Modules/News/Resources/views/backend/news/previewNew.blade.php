@extends('layouts.app')

@section('page_title', $item->title)

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="back_detail">
                        <i class="fas fa-chevron-left pr-2"></i><a href="{{route('module.news.edit',['id'=>$item->id])}}">Quay lại</a>
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        {{ trans('app.news') }}
                                        <i class="uil uil-angle-right"></i>
                                        <span>{{ $cate_new->name }}</span>
                                        <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">{{ $item->title }}</span>
                                    </h2>
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-12">
                                            <div class="news-header">
                                                <h2 class="crse14s">{!! $item->description !!}</h2>
                                                <div class="news-meta">
                                                    <span class="news-author">{{ $author }},</span>
                                                    <span class="news-time">{{ \Carbon\Carbon::parse($item->created_at)->format('H:s d/m/Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="news-content text-justify">
                                                @if ($item->type == 2)
                                                    <div class="mt-2">
                                                        <center>
                                                            <video width="75%" height="auto" controls>
                                                                <source src="{{ image_file($item->content) }}" type="video/mp4">
                                                            </video>
                                                        </center>
                                                    </div>
                                                @elseif($item->type == 3)
                                                    <div class="row">
                                                        @php
                                                        $pictures = json_decode($item->content);
                                                        @endphp
                                                        @foreach ($pictures as $picture)
                                                            <div class="col-4 mt-2">
                                                                <img data-enlargeable 
                                                                style="cursor: zoom-in;object-fit: cover;" 
                                                                src="{{ image_file($picture) }}" alt="" width="100%" height="100%">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    {!! $item->content !!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="blog_pagination">
                                                @if ($prev_post)
                                                    <span class="bp_left">
                                                        <i class="uil uil-angle-double-left"></i>
                                                        <div class="kslu15">
                                                            <div class="prevlink">{{ trans('app.older') }}</div>
                                                            <div class="prev_title">{{ Str::words($prev_post->title,8) }}</div>
                                                        </div>
                                                    </span>
                                                @endif
                                                @if ($next_post)
                                                    <span class="bp_right">
                                                        <div class="kslu16">
                                                            <div class="prevlink1">{{ trans('app.newer') }}</div>
                                                            <div class="prev_title1">{{ Str::words($next_post->title,8) }}</div>
                                                        </div>
                                                        <i class="uil uil-angle-double-right"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.news-content a').attr('target', '_blank')
        $('.vertical-fontend').addClass('vertical_nav__minify');
        $('.wrapper').addClass('wrapper__minify');
        $('#collapse_menu').attr('disabled',true);
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
                'background-size': '65% 80%',
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
    </script>
@stop
