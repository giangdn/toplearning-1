@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.news_mobile'))

@section('content')
    @php
        if($cate_id) {
            $daily_cate = $categories_item;
        } else {
            $daily_cate = $categories;
        }
    @endphp
    <div class="container" id="daily_training_mobile">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('module.daily_training.frontend') }}" id="form_search" method="GET" class="w-100">
                <select name="cate_id" class="select2 form-control w-100"  onchange="submit();">
                    <option value="" disabled selected>Danh mục</option>                                                    
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"{{ $cate_id && $cate_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="row news_item">
            <div class="col-12 mb-2">
                <h5><span class="title_type">Video mới nhất</span></h5>
            </div>
            @foreach ($get_daily_training_video_news as $get_daily_training_video_new)
                <div class="col-6 pb-2">
                    <a href="{{ route('module.daily_training.frontend.detail_video', ['id' => $get_daily_training_video_new->id]) }}">
                        <img src="{{ image_file($get_daily_training_video_new->avatar) }}" alt="" class="w-100" height="120px" style="object-fit: cover">
                    </a>
                    <span class="title_daily_video">{{ $get_daily_training_video_new->name }}</span>
                </div>
            @endforeach
        </div>
        @foreach ($daily_cate as $category)
            @php
                $query = Modules\DailyTraining\Entities\DailyTrainingVideo::query();
                $query->where('category_id',$category->id);
                $query->where('status',1);
                $query->orderByDesc('created_at');
                if (!$cate_id) {
                    $query->take(4);
                }
                $get_daily_training_videos =  $query->get();
            @endphp
            <div class="row news_item">
                <div class="col-12 mb-2">
                    <h5><span class="title_type">{{ $category->name }}</span></h5>
                </div>
                @foreach ($get_daily_training_videos as $get_daily_training_video)
                    <div class="col-6 pb-2">
                        <a href="{{ route('module.daily_training.frontend.detail_video', ['id' => $get_daily_training_video->id]) }}">
                            <img src="{{ image_file($get_daily_training_video->avatar) }}" alt="" class="w-100" height="120px" style="object-fit: cover">
                        </a>
                        <span class="title_daily_video">{{ $get_daily_training_video->name }}</span>
                    </div>
                @endforeach
                @if (count($get_daily_training_videos) == 4 && !$cate_id)
                    <div class="col-12 my-2">
                        <a href="{{ route('module.daily_training.frontend').'?cate_id='.$category->id }}" class="see_more">
                            <p class="text-center">XEM THÊM</p>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endsection
