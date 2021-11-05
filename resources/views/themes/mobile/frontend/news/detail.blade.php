@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.news'))

@section('content')
    @php
        $content = str_replace(config('app.url'), config('app.mobile_url'), $item->content);
    @endphp

    <div class="" id="news">
        <div class="card shadow border-0 mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col align-self-center">
                        <h5 class="mt-1 font-weight-bold">{{ $item->title }}</h5>
                        <div class="row">
                            <div class="col-auto pr-0">
                                <img src="{{ \App\Profile::avatar($item->created_by) }}" alt="" class="avatar avatar-40">
                            </div>
                            <div class="col align-self-center">
                                {{ $author }}
                                <br>
                                {{ $item->views }} @lang('app.view') - {{ \Carbon\Carbon::parse($item->created_at)->format('H:s d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- <div class="row">
                    <div class="col-12 p-0">
                        <img src="{{ image_file($item->image) }}" alt="" class="w-100 border-0">
                    </div>
                </div> --}}
                @if ($item->type == 2)
                    <div class="mt-2">
                        <center>
                            <video width="100%" height="auto" controls>
                                <source src="{{ image_file($content) }}" type="video/mp4">
                            </video>
                        </center>
                    </div>
                @elseif($item->type == 3)
                    <div class="row">
                        @php
                        $pictures = json_decode($content);
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
                    <div class="content_new_mobile p-1">
                        {!! $content !!}
                    </div>
                @endif
                <div class="mt-2" id="list-link">
                    @if($news_links->count() > 0)
                        @foreach($news_links as $news_link)
                            @if($news_link->type == 'file')
                                @if(isFilePdf($news_link->link))
                                    <a href="{{ route('module.news.view_pdf').'?path='.upload_file($news_link->link) }}" target="_blank" class=" mb-2" style="color: #1b4486 !important; font-size: 16px;">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        {{ $news_link->title ? $news_link->title : basename($news_link->link) }}
                                    </a>
                                @else
                                    <a href="javascript:void(0)" data-link_down="{{ link_download('uploads/'.$news_link->link) }}" data-turbolinks="false" class="down-link mb-2" style="color: #1b4486 !important; font-size: 16px;">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        {{ $news_link->title ? $news_link->title : basename($news_link->link) }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ str_replace(config('app.url'), config('app.mobile_url'), $news_link->link) }}" target="_blank" class=" mb-2" style="color: #1b4486 !important; font-size: 16px;">
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    {{ $news_link->title ? $news_link->title : basename($news_link->link) }}
                                </a>
                            @endif
                            <p></p>
                        @endforeach
                    @endif
                </div>
                <hr>
                <div class="blog_pagination">
                    <h6>@lang('app.related_news')</h6>
                    @if(count($categories) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($categories as $key => $news)
                                @php
                                    $user_id = $news->created_by;
                                    $time = $news->view_time ? $news->view_time : $news->created_at;
                                @endphp
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-5 p-0" id="laster-news">
                                            <img src="{{ image_file($news->image) }}" alt="" class="w-100 border-0" height="auto" style="object-fit: cover;max-height:120px">
                                        </div>
                                        <div class="col-7 pr-0 align-self-center">
                                            <a href="{{ route('module.news.detail', ['id' => $news->id]) }}">
                                                <h6 class="font-weight-normal mb-1 title_hot_new_left">{{ sub_char($news->title, 15) }}</h6>
                                            </a>
                                            <div class="row">
                                                <div class="col-auto pr-1">
                                                    <img src="{{ \App\Profile::avatar($user_id) }}" alt="" class="avatar avatar-30 mt-1">
                                                </div>
                                                <div class="col pl-1">
                                                    <span class="small">{{ \App\Profile::fullname($user_id) }}</span>
                                                    <p class="text-mute">
                                                        @if(\Carbon\Carbon::parse($time)->diffInDays() > 10)
                                                            {{ get_date($time) }}
                                                        @elseif(\Carbon\Carbon::parse($time)->diffInHours() > 24)
                                                            {{ \Carbon\Carbon::parse($time)->diffInDays() .' '. trans('app.day_ago') }}
                                                        @elseif(\Carbon\Carbon::parse($time)->diffInMinutes() > 30)
                                                            {{ \Carbon\Carbon::parse($time)->diffInHours() .' '. trans('app.hour_ago') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($time)->diffForHumans() }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
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
                'background-size': '90% auto',
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

    $('#list-link').on('click', '.down-link', function () {
        var link = $(this).data('link_down');

        Swal.fire({
            title: title_message,
            text: 'Vui lòng tải về để xem !',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                window.location.href = link;
                return false;
            }
            return false;
        });
    });

    /*$('.content_new_mobile a').on('click', function () {
        var path = $(this).attr('href');
        var link = "{{ route('module.news.view_pdf') }}?path="+path;
        $(this).prop('href', link);
    });*/
</script>
@endsection
