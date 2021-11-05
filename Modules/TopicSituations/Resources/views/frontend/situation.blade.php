@extends('layouts.app')

@section('page_title', 'Xử lý tình huống')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox-content forum-container">
                <h2 class="st_title">
                    <i class="uil uil-apps"></i>
                    <a href="{{ route('frontend.topic_situations') }}">Xử lý tình huống</a>
                    <i class="uil uil-angle-right"></i>
                    <span class="font-weight-bold">{{$topic->name}}</span>
                </h2>
            </div>
        </div>
    </div>
    <div class="row search-course pb-2 my-2">
        <div class="col-12 form-inline">
            <form action="{{ route('frontend.get.situations',['id' => $topic->id]) }}" method="get" class="form-inline" id="form-search">
                {{ csrf_field() }}
                <input type="text" name="search" value="" class="form-control search_text mr-1" placeholder="Nhập Mã/Tên tình huống">
                <input name="date_created" type="text" class="datepicker form-control search_start_date mr-1" placeholder="Ngày tạo" autocomplete="off">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
            </form>
        </div>
    </div>
    <div class="row m-0">
        <div class="col-12 all_situations">
            @if ($situations->count() > 0)
                @foreach($situations as $situation)
                    <div  class="row wrapped_situation">
                        <a href="{{ route('frontend.situations.detail',['id' => $topic->id, 'situation_id' => $situation->id]) }}" class="col-11">
                            <div class="row">
                                <div class="col-md-3 col-12 py-2 situation_name">
                                    <span>{{ $situation->name }}</span>
                                </div>
                                <div class="col-md-3 col-12 py-2 situation_description">
                                    <span>{!! $situation->description !!}</span>
                                </div>
                                <div class="col-md-4 col-9 py-2 pr-0">
                                    @php
                                        $count_comment_situation = Modules\TopicSituations\Entities\CommentSituation::where('topic_id',$topic->id)
                                        ->where('situation_id',$situation->id)
                                        ->count();
                                    @endphp
                                    <ul class="comment_like_view">
                                        <li>
                                            <span>{{ $count_comment_situation }} Bình luận</span>
                                        </li>
                                        <li>
                                            <span id="view_like_{{ $situation->id }}">{{ $situation->like }} Lượt thích</span>
                                        </li>
                                        <li>
                                            <span>{{ $situation->view }} <i class="fas fa-eye"></i></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-2 col-3 py-2">
                                    <span>{{ \Carbon\Carbon::parse($situation->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="col-1 like_situation">
                            @php
                                $profile = \Modules\TopicSituations\Entities\LikeSituation::where('user_id',\Auth::id())->first();
                                if ($profile !== null) {
                                    $get_profile_like_situation = json_decode($profile->situation_id);
                                }
                            @endphp
                            <div class="like" id="like_situation_{{ $situation->id }}" onclick="likeSituation({{$situation->id}})">
                                @if (!empty($get_profile_like_situation) && in_array($situation->id, $get_profile_like_situation))
                                    <span style="color: blue"><i class="fas fa-thumbs-up"></i></span>
                                @else
                                    <span><i class="far fa-thumbs-up"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="fcrse_1 mb-20">
                    <div class="text-center">
                        <span>@lang('app.not_found')</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    function likeSituation(id) {
        $.ajax({
            url: "{{ route('frontend.like.situations') }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            console.log(data);
            if (data.check_like == 1) {
                $('#like_situation_'+id).html('<span style="color: blue"><i class="fas fa-thumbs-up"></i></span>');
            } else {
                $('#like_situation_'+id).html('<i class="far fa-thumbs-up"></i>');
            }
            $('#view_like_'+id).html(data.view_like + ' Lượt thích');
            return false;
        }).fail(function(data) {
            show_message('{{ trans('lageneral.data_error ') }}', 'error');
            return false;
        });
    }
</script>
@stop
