@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.training_video'))

@section('header')
    <style>
        .dropdown-content {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container video-mobile-slide">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        @foreach($categories as $key => $category)
                            <a class="daily_nav_tab swiper-slide nav-item nav-link {{ $id == $category->id ? 'active' : '' }}" id="nav-{{ $category->id }}-tab" data-toggle="tab" href="#nav-{{ $category->id }}" role="tab" aria-selected="{{ $id == $category->id ? true : false }}">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-1">
                <div class="news_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @foreach($categories as $key => $category)
                            <div class="tab-pane fade {{ $id == $category->id ? 'active show' : '' }}" id="nav-{{ $category->id }}" role="tabpanel">
                                @php
                                    $videos = \Modules\DailyTraining\Entities\DailyTrainingVideo::getVideoByCategory($category->id);
                                @endphp
                                <div class="list-group list-group-flush">
                                    @if(count($videos) > 0)
                                        <div class="row m-0">
                                            @foreach($videos as $key => $video)
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-2">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="{{ route('module.daily_training.frontend.detail_video', ['id' => $video->id]) }}">
                                                                <img src="{{ image_file($video->avatar) }}" alt="" class="w-100" height="220px" style="object-fit: cover">
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <div class="row">
                                                                <div class="col-auto pr-0">
                                                                    <img src="{{ \App\Profile::avatar($video->created_by) }}" alt="" class="avatar avatar-40">
                                                                </div>
                                                                <div class="col">
                                                                    <a href="{{ route('module.daily_training.frontend.detail_video', ['id' => $video->id]) }}">{{ $video->name }}</a>
                                                                    <p class="text-mute small">
                                                                        {{ \App\Profile::fullname($video->created_by) .' - '. $video->view .' '. trans('app.view') .' - '. \Carbon\Carbon::parse($video->created_at)->diffForHumans()}}
                                                                    </p>
                                                                </div>
                                                                <div class="col-auto pl-0">
                                                                    <img src="{{ asset('themes/mobile/img/heart.png') }}" alt="" width="15px" height="15px">
                                                                    @if(\Auth::id() == $video->created_by)
                                                                        <span class="disable-video text-danger" data-video_id="{{ $video->id }}">
                                                                            <i class="material-icons vm">delete</i>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-center">@lang('app.not_found')</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        // $(document).ready(function(){
        //     $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        //         localStorage.setItem('activeTab-video', $(e.target).attr('href'));
        //     });
        //     var activeTab = localStorage.getItem('activeTab-video');
        //     if(activeTab){
        //         $('a[data-toggle="tab"]').removeClass('active');
        //         $('#nav-tab a[href="' + activeTab + '"]').tab('show');
        //         $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
        //     }
        // });

        var swiper = new Swiper('.video-mobile-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
        });

        $(".disable-video").on('click', function () {
            let id = $(this).data('video_id');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.frontend.disable_video') }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id
                }
            }).done(function(data) {
                window.location = '';
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        var i = 1;
        $('.dropdown').on('click', function () {
            i += 1;
            if (i%2 == 0){
                $(this).find('.dropdown-content').css('display', 'block');
            }else{
                $(this).find('.dropdown-content').css('display', 'none');
            }
        });
    </script>
@endsection
