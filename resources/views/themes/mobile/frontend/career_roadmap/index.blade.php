@extends('themes.mobile.layouts.app')

@section('page_title', trans('career.career_roadmap'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container career-slide">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        @foreach($sub_titles as $key => $sub_title)
                            <a class="swiper-slide nav-item nav-link {{ $key == 0 ? 'active' : '' }} pl-0 pr-0" id="nav-{{$sub_title->id}}-tab" data-toggle="tab" href="#nav-{{$sub_title->id}}" role="tab" aria-selected="true" style="min-height: 81px;">
                                <img src="{{ asset('themes/mobile/img/crown.png') }}" alt="" class="avatar-20 shadow"> <br>
                                {{ $sub_title->title->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 px-0">
                <div class="career_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @foreach($sub_titles as $key => $sub_title)
                            @php
                                $course_by_titles = \Modules\CareerRoadmap\Entities\CareerRoadmapTitle::getSubjectRoadmap($sub_title->title->id);
                            @endphp
                        <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="nav-{{$sub_title->id}}" role="tabpanel">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    @if($course_by_titles->count() > 0)
                                        @foreach($course_by_titles as $course)
                                            <p class="item-success border-top">
                                                <div class="row">
                                                    <div class="col-10">
                                                        {{ $course->subject_name .' ('. $course->subject_code .')' }}
                                                    </div>
                                                    <div class="col-2">
                                                        @if($course->result == 1)
                                                            <i class="material-icons vm text-primary">done</i>
                                                        @else
                                                            <i class="material-icons vm text-danger">close</i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </p>
                                        @endforeach
                                    @else
                                        <p class="text-center">@lang('app.not_found')</p>
                                    @endif
                                </div>
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

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-career', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-career');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        var swiper = new Swiper('.career-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                320: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                }
            }
        });
    </script>
@endsection
