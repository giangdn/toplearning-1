@extends('layouts.app')

@section('page_title', trans('app.book'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <span class="font-weight-bold">@lang('backend.training_action')</span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <div class="section3125">
                        <div class="explore_search">
                            <div class="ui search focus">
                                <div class="ui left icon input swdh11">
                                    <input class="prompt srch_explore" type="text" placeholder="">
                                    <i class="uil uil-search-alt icon icon2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="_14d25 mb-2">
                        <div class="row">
                        @foreach($items as $item)
                            <div class="col-md-6">
                                <div class="fcrse_1">

                                    <a href="" class="hf_img">
                                        <img src="images/courses/img-1.jpg" alt="">
                                        <div class="course-overlay">
                                            <div class="badge_seller">Bestseller</div>
                                            <div class="crse_reviews">
                                                <i class="uil uil-star"></i>4.5
                                            </div>
                                            <span class="play_btn1"><i class="uil uil-play"></i></span>
                                            <div class="crse_timer">
                                                25 hours
                                            </div>
                                        </div>
                                    </a>

                                    <div class="hs_content">
                                        <div class="eps_dots eps_dots10 more_dropdown">
                                            <a href="#"><i class="uil uil-ellipsis-v"></i></a>
                                            <div class="dropdown-content">
                                                <span><i class="uil uil-times"></i>Remove</span>
                                            </div>
                                        </div>

                                        <div class="vdtodt">
                                            <span class="vdt14">109k views</span>
                                            <span class="vdt14">15 days ago</span>
                                        </div>

                                        <a href="" class="crse14s title900">{{ $item->name }}</a>
                                        <a href="" class="crse-cate">Web Development | Python</a>
                                        <div class="auth1lnkprce">
                                            <button type="button" class="btn btn-success btn-sm teacher-register" data-id="{{ $item->id }}"><i class="fa fa-check-circle"></i> Đăng ký giảng viên</button>
                                            <button type="button" class="btn btn-success btn-sm student-register" data-id="{{ $item->id }}"><i class="fa fa-check-circle"></i> Đăng ký học viên</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@stop
