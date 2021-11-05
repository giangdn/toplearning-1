@extends('layouts.app')

@section('page_title', trans('app.my_course'))

@section('content')

    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25 mb-20">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="mhs_title">@lang('app.my_course')</h4>

                                @foreach($items as $item)
                                <div class="fcrse_1 mt-30">
                                    <a href="{{ $item->course_url }}" class="hf_img">
                                        <img src="{{ image_file($item->image) }}" alt="">
                                        <div class="course-overlay">
                                            <div class="badge_seller">Bestseller</div>
                                            <div class="crse_reviews">
                                                <i class="uil uil-star"></i>
                                            </div>
                                            <span class="play_btn1"><i class="uil uil-play"></i></span>
                                            <div class="crse_timer">
                                                28 hours
                                            </div>
                                        </div>
                                    </a>
                                    <div class="hs_content">

                                        <div class="vdtodt">
                                            <span class="vdt14">5M views</span>
                                            <span class="vdt14">15 days ago</span>
                                        </div>
                                        <a href="{{ $item->course_url }}" class="crse14s title900">{{ $item->name }}</a>
                                        <a href="{{ $item->course_url }}" class="crse-cate">Development | JavaScript</a>
                                        <div class="auth1lnkprce">
                                            <p class="cr1fot">By <a href="#">Jassica William</a></p>
                                            <div class="prce142">$5</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop